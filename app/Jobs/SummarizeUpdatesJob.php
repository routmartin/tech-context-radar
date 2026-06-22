<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\RadarPipelineRun;
use App\Models\RawUpdate;
use App\Models\Source;
use App\Services\RadarPipeline\MissingAiConfigurationException;
use App\Services\RadarPipeline\PipelineRunRecorder;
use App\Services\RadarPipeline\UpdateSummarizer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SummarizeUpdatesJob implements ShouldQueue
{
    use Queueable;

    public function handle(UpdateSummarizer $summarizer, PipelineRunRecorder $runs): void
    {
        $run = $runs->start(RadarPipelineRun::TYPE_SUMMARIZE_UPDATES, [
            'provider' => config('radar.ai.provider'),
            'model' => config('radar.ai.model'),
        ]);
        $counts = ['seen' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0];

        $categoryIds = Category::whereIn('slug', config('radar.focus_categories', ['ai']))->pluck('id');

        try {
            RawUpdate::query()
                ->with(['source.category'])
                ->whereIn('source_id', Source::whereIn('category_id', $categoryIds)->pluck('id'))
                ->where('status', RawUpdate::STATUS_COLLECTED)
                ->where('created_at', '>=', now()->subHours((int) config('radar.agent.lookback_hours', 72)))
                ->orderByDesc('published_at')
                ->limit((int) config('radar.agent.max_items', 25))
                ->get()
                ->each(function (RawUpdate $update) use ($summarizer, &$counts): void {
                    $counts['seen']++;

                    try {
                        $summarizer->summarize($update);
                        $counts['updated']++;
                    } catch (MissingAiConfigurationException $exception) {
                        throw $exception;
                    } catch (Throwable $exception) {
                        $update->update([
                            'status' => RawUpdate::STATUS_FAILED,
                            'metadata' => array_merge($update->metadata ?? [], [
                                'summarize_error' => $exception->getMessage(),
                            ]),
                        ]);
                        $counts['failed']++;
                    }
                });

            $runs->complete($run, $counts);
        } catch (MissingAiConfigurationException $exception) {
            $runs->fail($run, $exception, $counts);
        }
    }
}
