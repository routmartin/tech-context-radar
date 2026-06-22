<?php

namespace App\Services\RadarPipeline;

use App\Models\Briefing;
use App\Models\Category;
use App\Models\RawUpdate;
use App\Models\Signal;
use Illuminate\Support\Carbon;

class BriefingBuilder
{
    public function build(?Carbon $date = null): Briefing
    {
        $date ??= today();

        $categoryIds = Category::whereIn('slug', config('radar.focus_categories', ['ai']))->pluck('id');

        $signals = Signal::query()
            ->whereIn('category_id', $categoryIds)
            ->whereDate('published_at', $date)
            ->orderByDesc('priority_score')
            ->limit(5)
            ->get();

        if ($signals->isEmpty()) {
            $signals = Signal::query()
                ->whereIn('category_id', $categoryIds)
                ->orderByDesc('priority_score')
                ->limit(5)
                ->get();
        }

        $summary = $signals->take(3)->pluck('title')->implode('; ');
        $priorityCount = $signals->where('priority_score', '>=', (int) config('radar.agent.min_priority', 70))->count();
        $lowImpact = RawUpdate::query()
            ->whereIn('status', [RawUpdate::STATUS_IGNORED, RawUpdate::STATUS_COLLECTED])
            ->where('created_at', '>=', $date->copy()->startOfDay())
            ->count();

        $briefing = Briefing::query()->whereDate('briefing_date', $date)->first() ?? new Briefing([
            'briefing_date' => $date->toDateString(),
        ]);

        $briefing->fill([
            'title' => "Today's Radar",
            'summary' => $summary ?: 'No high-priority AI or tech signals detected yet.',
            'reading_time_minutes' => min(10, max(3, $signals->sum('read_time_minutes'))),
            'priority_signal_count' => $priorityCount,
            'low_impact_filtered_count' => $lowImpact,
            'confidence_score' => (int) round($signals->avg('priority_score') ?: 70),
        ]);
        $briefing->save();

        return $briefing;
    }
}
