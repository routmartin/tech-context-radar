<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadarPipelineRun extends Model
{
    use HasFactory;

    public const STATUS_QUEUED = 'queued';

    public const STATUS_RUNNING = 'running';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const TYPE_SCAN_FEEDS = 'scan_feeds';

    public const TYPE_SUMMARIZE_UPDATES = 'summarize_updates';

    public const TYPE_RANK_SIGNALS = 'rank_signals';

    public const TYPE_GENERATE_BRIEFING = 'generate_briefing';

    protected $fillable = [
        'type',
        'status',
        'items_seen',
        'items_created',
        'items_updated',
        'items_failed',
        'metadata',
        'error_message',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }
}
