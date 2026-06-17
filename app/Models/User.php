<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function savedSignals(): BelongsToMany
    {
        return $this->belongsToMany(Signal::class, 'saved_signals')->withPivot('created_at');
    }

    public function readSignals(): BelongsToMany
    {
        return $this->belongsToMany(Signal::class, 'read_signals')->withPivot('created_at');
    }

    public function savedSignalRecords(): HasMany
    {
        return $this->hasMany(SavedSignal::class);
    }

    public function readSignalRecords(): HasMany
    {
        return $this->hasMany(ReadSignal::class);
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }
}
