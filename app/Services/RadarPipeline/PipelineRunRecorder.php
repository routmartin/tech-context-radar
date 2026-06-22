<?php

namespace App\Services\RadarPipeline;

use App\Models\RadarPipelineRun;
use Throwable;

class PipelineRunRecorder
{
    public function start(string $type, array $metadata = []): RadarPipelineRun
    {
        return RadarPipelineRun::create([
            'type' => $type,
            'status' => RadarPipelineRun::STATUS_RUNNING,
            'metadata' => $metadata,
            'started_at' => now(),
        ]);
    }

    public function complete(RadarPipelineRun $run, array $counts = [], array $metadata = []): void
    {
        $run->update([
            'status' => RadarPipelineRun::STATUS_COMPLETED,
            'items_seen' => $counts['seen'] ?? $run->items_seen,
            'items_created' => $counts['created'] ?? $run->items_created,
            'items_updated' => $counts['updated'] ?? $run->items_updated,
            'items_failed' => $counts['failed'] ?? $run->items_failed,
            'metadata' => array_merge($run->metadata ?? [], $metadata),
            'finished_at' => now(),
        ]);
    }

    public function fail(RadarPipelineRun $run, Throwable|string $error, array $counts = []): void
    {
        $run->update([
            'status' => RadarPipelineRun::STATUS_FAILED,
            'items_seen' => $counts['seen'] ?? $run->items_seen,
            'items_created' => $counts['created'] ?? $run->items_created,
            'items_updated' => $counts['updated'] ?? $run->items_updated,
            'items_failed' => $counts['failed'] ?? $run->items_failed,
            'error_message' => is_string($error) ? $error : $error->getMessage(),
            'finished_at' => now(),
        ]);
    }
}
