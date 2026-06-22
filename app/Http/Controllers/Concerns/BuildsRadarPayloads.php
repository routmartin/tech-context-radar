<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Briefing;
use App\Models\Category;
use App\Models\RawUpdate;
use App\Models\Signal;
use App\Models\Source;
use App\Models\User;
use Illuminate\Support\Collection;

trait BuildsRadarPayloads
{
    protected function categories(): Collection
    {
        return Category::query()->orderBy('sort_order')->get(['id', 'name', 'slug'])->map(fn (Category $category) => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ]);
    }

    protected function briefing(): array
    {
        $briefing = Briefing::query()->latest('briefing_date')->first();

        return [
            'title' => $briefing?->title ?? 'Today’s Radar',
            'summary' => $briefing?->summary ?? 'Your 10-minute AI and tech intelligence brief.',
            'date' => $briefing?->briefing_date?->toFormattedDateString() ?? now()->toFormattedDateString(),
            'readingTime' => $briefing?->reading_time_minutes ?? 10,
            'prioritySignals' => $briefing?->priority_signal_count ?? 3,
            'lowImpactFiltered' => $briefing?->low_impact_filtered_count ?? 39,
            'confidenceScore' => $briefing?->confidence_score ?? 82,
        ];
    }

    protected function signalPayload(Signal $signal, ?User $user = null): array
    {
        return [
            'id' => $signal->id,
            'title' => $signal->title,
            'slug' => $signal->slug,
            'summary' => $signal->summary,
            'whyItMatters' => $signal->why_it_matters,
            'developerImpact' => $signal->developer_impact,
            'recommendedAction' => $signal->recommended_action,
            'priorityScore' => $signal->priority_score,
            'readTimeMinutes' => $signal->read_time_minutes,
            'sourceCount' => $signal->source_count,
            'publishedAt' => $signal->published_at?->diffForHumans(),
            'publishedDate' => $signal->published_at?->toFormattedDateString(),
            'category' => [
                'name' => $signal->category?->name ?? 'AI',
                'slug' => $signal->category?->slug ?? 'ai',
            ],
            'source' => $signal->source ? [
                'name' => $signal->source->name,
                'slug' => $signal->source->slug,
                'trustLevel' => $signal->source->trust_level,
                'homepageUrl' => $signal->source->homepage_url,
            ] : null,
            'citations' => $signal->relationLoaded('rawUpdates') ? $signal->rawUpdates->map(fn (RawUpdate $update) => [
                'title' => $update->title,
                'url' => $update->url,
                'source' => $update->source?->name,
                'publishedAt' => $update->published_at?->toFormattedDateString(),
            ])->values() : [],
            'isSaved' => $user ? $user->savedSignalRecords()->where('signal_id', $signal->id)->exists() : false,
            'isRead' => $user ? $user->readSignalRecords()->where('signal_id', $signal->id)->exists() : false,
            'url' => route('signals.show', $signal, absolute: false),
        ];
    }

    protected function signalCollection(iterable $signals, ?User $user = null): Collection
    {
        return collect($signals)->map(fn (Signal $signal) => $this->signalPayload($signal, $user))->values();
    }

    protected function sourcePayload(Source $source): array
    {
        return [
            'id' => $source->id,
            'name' => $source->name,
            'slug' => $source->slug,
            'trustLevel' => $source->trust_level,
            'feedUrl' => $source->feed_url,
            'homepageUrl' => $source->homepage_url,
            'isEnabled' => $source->is_enabled,
            'updatesToday' => $source->updates_today,
            'lastScanned' => $source->last_scanned_at?->diffForHumans(),
            'lastScanError' => $source->last_scan_error,
            'status' => $source->status,
            'category' => [
                'name' => $source->category?->name ?? 'AI',
                'slug' => $source->category?->slug ?? 'ai',
            ],
            'recentSignals' => $source->signals->take(3)->map(fn (Signal $signal) => [
                'title' => $signal->title,
                'slug' => $signal->slug,
                'priorityScore' => $signal->priority_score,
                'url' => route('signals.show', $signal, absolute: false),
            ])->values(),
            'recentUpdates' => $source->relationLoaded('rawUpdates') ? $source->rawUpdates->take(3)->map(fn (RawUpdate $update) => [
                'title' => $update->title,
                'status' => $update->status,
                'publishedAt' => $update->published_at?->diffForHumans(),
            ])->values() : [],
            'noiseFiltered' => $source->relationLoaded('rawUpdates')
                ? $source->rawUpdates->whereIn('status', [RawUpdate::STATUS_IGNORED, RawUpdate::STATUS_COLLECTED])->count()
                : max(0, $source->updates_today - $source->signals->count()),
        ];
    }
}
