<?php

namespace App\Services\RadarPipeline;

use App\Models\RawUpdate;
use App\Models\Signal;
use Illuminate\Support\Str;

class SignalRanker
{
    public function promote(RawUpdate $update): ?Signal
    {
        if ($update->status !== RawUpdate::STATUS_SUMMARIZED || ! $update->ai_summary) {
            return null;
        }

        $priority = $this->priority($update);
        $minimum = (int) config('radar.agent.min_priority', 70);

        if ($priority < $minimum) {
            $update->update(['status' => RawUpdate::STATUS_IGNORED]);

            return null;
        }

        $signal = Signal::updateOrCreate(
            ['slug' => Str::slug(Str::limit($update->title, 70, ''))],
            [
                'category_id' => $update->source->category_id,
                'source_id' => $update->source_id,
                'title' => Str::limit($update->title, 255, ''),
                'summary' => $update->ai_summary,
                'why_it_matters' => $update->ai_why_it_matters,
                'developer_impact' => $update->ai_developer_impact,
                'recommended_action' => $update->ai_recommended_action,
                'priority_score' => $priority,
                'read_time_minutes' => 3,
                'source_count' => 1,
                'published_at' => $update->published_at ?? now(),
            ],
        );

        $signal->rawUpdates()->syncWithoutDetaching([$update->id]);
        $update->update(['status' => RawUpdate::STATUS_PROMOTED]);

        return $signal;
    }

    private function priority(RawUpdate $update): int
    {
        $score = (int) ($update->ai_priority_score ?? 50);
        $trust = strtolower((string) $update->source?->trust_level);

        if (str_contains($trust, 'very high')) {
            $score += 6;
        } elseif (str_contains($trust, 'high')) {
            $score += 3;
        }

        if ($update->published_at && $update->published_at->greaterThan(now()->subDay())) {
            $score += 3;
        }

        return max(0, min(100, $score));
    }
}
