<?php

namespace App\Models;

use Database\Factories\SignalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Signal extends Model
{
    /** @use HasFactory<SignalFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'source_id', 'title', 'slug', 'summary', 'why_it_matters', 'developer_impact', 'recommended_action', 'priority_score', 'read_time_minutes', 'source_count', 'published_at'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function savedSignals(): HasMany
    {
        return $this->hasMany(SavedSignal::class);
    }

    public function readSignals(): HasMany
    {
        return $this->hasMany(ReadSignal::class);
    }

    public function rawUpdates(): BelongsToMany
    {
        return $this->belongsToMany(RawUpdate::class, 'signal_raw_update')->withTimestamps();
    }
}
