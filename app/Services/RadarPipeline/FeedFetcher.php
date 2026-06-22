<?php

namespace App\Services\RadarPipeline;

use App\Models\RawUpdate;
use App\Models\Source;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;
use Throwable;

class FeedFetcher
{
    /**
     * @return array{seen:int, created:int, updated:int, failed:int}
     */
    public function scan(Source $source): array
    {
        if (! $source->feed_url) {
            return ['seen' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0];
        }

        $response = Http::timeout(20)
            ->accept('application/rss+xml, application/atom+xml, application/xml, text/xml')
            ->get($source->feed_url);

        if (! $response->successful()) {
            throw new RuntimeException("Feed returned HTTP {$response->status()}");
        }

        $items = $this->parse($response->body());
        $counts = ['seen' => count($items), 'created' => 0, 'updated' => 0, 'failed' => 0];

        foreach ($items as $item) {
            if (! $item->url || ! $item->title) {
                $counts['failed']++;

                continue;
            }

            $hash = hash('sha256', Str::lower($item->url).'|'.Str::lower($item->title));
            $raw = RawUpdate::updateOrCreate(
                ['url' => $item->url],
                [
                    'source_id' => $source->id,
                    'title' => Str::limit($item->title, 255, ''),
                    'content_hash' => $hash,
                    'excerpt' => $item->excerpt,
                    'raw_content' => $item->rawContent,
                    'status' => RawUpdate::STATUS_COLLECTED,
                    'metadata' => ['feed_url' => $source->feed_url],
                    'published_at' => $item->publishedAt,
                ],
            );

            $raw->wasRecentlyCreated ? $counts['created']++ : $counts['updated']++;
        }

        $source->update([
            'updates_today' => RawUpdate::query()
                ->where('source_id', $source->id)
                ->where('created_at', '>=', now()->startOfDay())
                ->count(),
            'last_scanned_at' => now(),
            'last_scan_error' => null,
            'status' => 'healthy',
        ]);

        return $counts;
    }

    /**
     * @return list<FeedItem>
     */
    private function parse(string $xml): array
    {
        $previous = libxml_use_internal_errors(true);

        try {
            $feed = new SimpleXMLElement($xml);
            libxml_clear_errors();
        } catch (Throwable $exception) {
            throw new RuntimeException('Feed XML could not be parsed.', previous: $exception);
        } finally {
            libxml_use_internal_errors($previous);
        }

        if (isset($feed->channel->item)) {
            return $this->parseRss($feed);
        }

        if (isset($feed->entry)) {
            return $this->parseAtom($feed);
        }

        return [];
    }

    /**
     * @return list<FeedItem>
     */
    private function parseRss(SimpleXMLElement $feed): array
    {
        $items = [];

        foreach ($feed->channel->item as $item) {
            $content = $item->children('content', true);
            $description = $this->clean((string) ($content->encoded ?: $item->description));

            $items[] = new FeedItem(
                title: trim((string) $item->title),
                url: trim((string) $item->link),
                excerpt: Str::limit($description, 500),
                rawContent: $description,
                publishedAt: $this->parseDate((string) $item->pubDate),
            );
        }

        return $items;
    }

    /**
     * @return list<FeedItem>
     */
    private function parseAtom(SimpleXMLElement $feed): array
    {
        $items = [];

        foreach ($feed->entry as $entry) {
            $url = '';
            foreach ($entry->link as $link) {
                $attrs = $link->attributes();
                if (! isset($attrs['rel']) || (string) $attrs['rel'] === 'alternate') {
                    $url = (string) ($attrs['href'] ?? '');
                    break;
                }
            }

            $summary = $this->clean((string) ($entry->summary ?: $entry->content));

            $items[] = new FeedItem(
                title: trim((string) $entry->title),
                url: trim($url),
                excerpt: Str::limit($summary, 500),
                rawContent: $summary,
                publishedAt: $this->parseDate((string) ($entry->published ?: $entry->updated)),
            );
        }

        return $items;
    }

    private function clean(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($value))) ?? '');
    }

    private function parseDate(string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (Throwable) {
            return null;
        }
    }
}
