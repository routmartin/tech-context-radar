<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\RadarPipelineRun;
use App\Models\Source;
use App\Services\RadarPipeline\FeedFetcher;
use App\Services\RadarPipeline\PipelineRunRecorder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ScanFeedsJob implements ShouldQueue
{
    use Queueable;

    public function handle(FeedFetcher $fetcher, PipelineRunRecorder $runs): void
    {
        $run = $runs->start(RadarPipelineRun::TYPE_SCAN_FEEDS);
        $counts = ['seen' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0];

        $categoryIds = Category::whereIn('slug', config('radar.focus_categories', ['ai']))->pluck('id');

        Source::query()
            ->where('is_enabled', true)
            ->whereNotNull('feed_url')
            ->whereIn('category_id', $categoryIds)
            ->orderBy('name')
            ->each(function (Source $source) use ($fetcher, &$counts): void {
                try {
                    $result = $fetcher->scan($source);
                    foreach ($counts as $key => $value) {
                        $counts[$key] = $value + ($result[$key] ?? 0);
                    }
                } catch (Throwable $exception) {
                    $counts['failed']++;
                    $source->update([
                        'last_scanned_at' => now(),
                        'last_scan_error' => $exception->getMessage(),
                        'status' => 'degraded',
                    ]);
                }
            });

        $runs->complete($run, $counts);
    }
}
