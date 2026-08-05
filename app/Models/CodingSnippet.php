<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodingSnippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language',
        'title',
        'code',
        'ai_explanation',
        'ai_bugs',
        'ai_optimization',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
