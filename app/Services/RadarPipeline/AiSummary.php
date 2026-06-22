<?php

namespace App\Services\RadarPipeline;

readonly class AiSummary
{
    public function __construct(
        public string $summary,
        public string $whyItMatters,
        public string $developerImpact,
        public string $recommendedAction,
        public int $priorityScore,
        public int $confidenceScore,
    ) {}
}
