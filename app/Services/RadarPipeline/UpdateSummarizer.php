<?php

namespace App\Services\RadarPipeline;

use App\Models\RawUpdate;

class UpdateSummarizer
{
    public function __construct(private readonly AiSummarizer $summarizer) {}

    public function summarize(RawUpdate $update): RawUpdate
    {
        $summary = $this->summarizer->summarize($update);

        $update->update([
            'ai_summary' => $summary->summary,
            'ai_why_it_matters' => $summary->whyItMatters,
            'ai_developer_impact' => $summary->developerImpact,
            'ai_recommended_action' => $summary->recommendedAction,
            'ai_priority_score' => $summary->priorityScore,
            'ai_confidence_score' => $summary->confidenceScore,
            'status' => RawUpdate::STATUS_SUMMARIZED,
            'summarized_at' => now(),
        ]);

        return $update;
    }
}
