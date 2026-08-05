<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGamification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'xp_points',
        'coins',
        'streak_days',
        'last_study_date',
        'level',
        'badges',
    ];

    protected $casts = [
        'xp_points' => 'integer',
        'coins' => 'integer',
        'streak_days' => 'integer',
        'level' => 'integer',
        'badges' => 'array',
        'last_study_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
