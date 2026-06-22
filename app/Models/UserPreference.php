<?php

namespace App\Models;

use Database\Factories\UserPreferenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    /** @use HasFactory<UserPreferenceFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'briefing_length_minutes', 'priority_threshold', 'preferred_categories', 'daily_reminder_enabled', 'priority_alerts_enabled', 'weekly_summary_enabled', 'compact_mode_enabled', 'dark_mode_enabled'];

    protected function casts(): array
    {
        return [
            'preferred_categories' => 'array',
            'daily_reminder_enabled' => 'boolean',
            'priority_alerts_enabled' => 'boolean',
            'weekly_summary_enabled' => 'boolean',
            'compact_mode_enabled' => 'boolean',
            'dark_mode_enabled' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
