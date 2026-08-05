<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashcard;
use App\Models\Note;

class FlashcardSeeder extends Seeder
{
    public function run(): void
    {
        $note = Note::first();

        if (!$note) {
            return;
        }

        Flashcard::create([
            'user_id' => $note->user_id,
            'subject_id' => $note->subject_id,
            'note_id' => $note->id,
            'question' => 'What is the ploidy level of daughter cells in meiosis?',
            'answer' => 'Haploid (n) - containing half the number of chromosomes.',
            'difficulty_level' => 'easy',
            'last_reviewed_at' => now(),
            'review_count' => 3,
        ]);
    }
}
