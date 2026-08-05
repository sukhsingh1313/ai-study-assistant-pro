<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subject_id',
        'title',
        'content',
        'category',
        'is_pinned',
        'tags',
        'file_path',
        'file_type',
        'word_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_pinned' => 'boolean',
        'word_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function flashcards(): HasMany
    {
        return $this->hasMany(Flashcard::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    public function isPdf(): bool
    {
        return strtolower($this->file_type) === 'pdf';
    }

    public function isImage(): bool
    {
        return in_array(strtolower($this->file_type), ['png', 'jpg', 'jpeg', 'webp']);
    }
}
