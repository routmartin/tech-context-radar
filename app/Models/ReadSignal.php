<?php

namespace App\Models;

use Database\Factories\ReadSignalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadSignal extends Model
{
    /** @use HasFactory<ReadSignalFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'signal_id', 'created_at'];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signal(): BelongsTo
    {
        return $this->belongsTo(Signal::class);
    }
}
