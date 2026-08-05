<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_type',
        'score',
        'xp_earned',
        'coins_earned',
        'accuracy_percentage',
        'duration_seconds',
    ];

    protected $casts = [
        'score' => 'integer',
        'xp_earned' => 'integer',
        'coins_earned' => 'integer',
        'accuracy_percentage' => 'float',
        'duration_seconds' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
