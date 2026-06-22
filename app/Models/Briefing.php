<?php

namespace App\Models;

use Database\Factories\BriefingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefing extends Model
{
    /** @use HasFactory<BriefingFactory> */
    use HasFactory;

    protected $fillable = ['briefing_date', 'title', 'summary', 'reading_time_minutes', 'priority_signal_count', 'low_impact_filtered_count', 'confidence_score'];

    protected function casts(): array
    {
        return [
            'briefing_date' => 'date',
        ];
    }
}
