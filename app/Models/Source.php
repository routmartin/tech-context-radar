<?php

namespace App\Models;

use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'feed_url',
        'homepage_url',
        'is_enabled',
        'trust_level',
        'updates_today',
        'last_scanned_at',
        'last_scan_error',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'last_scanned_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function signals(): HasMany
    {
        return $this->hasMany(Signal::class);
    }

    public function rawUpdates(): HasMany
    {
        return $this->hasMany(RawUpdate::class);
    }
}
