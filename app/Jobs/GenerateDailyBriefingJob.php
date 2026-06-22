<?php

namespace App\Jobs;

use App\Models\RadarPipelineRun;
use App\Services\RadarPipeline\BriefingBuilder;
use App\Services\RadarPipeline\PipelineRunRecorder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateDailyBriefingJob implements ShouldQueue
{
    use Queueable;

    public function handle(BriefingBuilder $builder, PipelineRunRecorder $runs): void
    {
        $run = $runs->start(RadarPipelineRun::TYPE_GENERATE_BRIEFING);
        $briefing = $builder->build();

        $runs->complete($run, [
            'seen' => $briefing->priority_signal_count,
            'created' => 1,
            'updated' => 0,
            'failed' => 0,
        ]);
    }
}
