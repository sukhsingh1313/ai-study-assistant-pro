<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecondBrain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weak_topics',
        'strong_topics',
        'recommendations',
        'learning_pattern',
    ];

    protected $casts = [
        'weak_topics' => 'array',
        'strong_topics' => 'array',
        'recommendations' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
