<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RawUpdate extends Model
{
    use HasFactory;

    public const STATUS_COLLECTED = 'collected';

    public const STATUS_SUMMARIZED = 'summarized';

    public const STATUS_PROMOTED = 'promoted';

    public const STATUS_IGNORED = 'ignored';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'source_id',
        'title',
        'url',
        'content_hash',
        'excerpt',
        'raw_content',
        'status',
        'metadata',
        'ai_summary',
        'ai_why_it_matters',
        'ai_developer_impact',
        'ai_recommended_action',
        'ai_priority_score',
        'ai_confidence_score',
        'published_at',
        'summarized_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'published_at' => 'datetime',
            'summarized_at' => 'datetime',
        ];
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function signals(): BelongsToMany
    {
        return $this->belongsToMany(Signal::class, 'signal_raw_update')->withTimestamps();
    }
}
