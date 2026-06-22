<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\RadarPipelineRun;
use App\Models\RawUpdate;
use App\Models\Source;
use App\Services\RadarPipeline\PipelineRunRecorder;
use App\Services\RadarPipeline\SignalRanker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RankSignalsJob implements ShouldQueue
{
    use Queueable;

    public function handle(SignalRanker $ranker, PipelineRunRecorder $runs): void
    {
        $run = $runs->start(RadarPipelineRun::TYPE_RANK_SIGNALS, [
            'min_priority' => config('radar.agent.min_priority'),
        ]);
        $counts = ['seen' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0];

        $categoryIds = Category::whereIn('slug', config('radar.focus_categories', ['ai']))->pluck('id');

        RawUpdate::query()
            ->with(['source.category'])
            ->whereIn('source_id', Source::whereIn('category_id', $categoryIds)->pluck('id'))
            ->where('status', RawUpdate::STATUS_SUMMARIZED)
            ->orderByDesc('ai_priority_score')
            ->limit((int) config('radar.agent.max_items', 25))
            ->get()
            ->each(function (RawUpdate $update) use ($ranker, &$counts): void {
                $counts['seen']++;
                $signal = $ranker->promote($update);
                $signal ? $counts['created']++ : $counts['updated']++;
            });

        $runs->complete($run, $counts);
    }
}
