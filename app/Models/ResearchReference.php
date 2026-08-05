<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'authors',
        'year',
        'citation_style',
        'formatted_citation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
