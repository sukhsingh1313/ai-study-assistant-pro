<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flashcard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subject_id',
        'note_id',
        'summary_id',
        'question',
        'answer',
        'difficulty_level',
        'is_favorite',
        'last_reviewed_at',
        'review_count',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'last_reviewed_at' => 'datetime',
        'review_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function summary(): BelongsTo
    {
        return $this->belongsTo(Summary::class);
    }
}
