<?php

namespace App\Services\RadarPipeline;

use App\Models\RawUpdate;

interface AiSummarizer
{
    public function summarize(RawUpdate $update): AiSummary;
}
