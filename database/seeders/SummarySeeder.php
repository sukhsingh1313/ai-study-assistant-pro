<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Summary;
use App\Models\Note;

class SummarySeeder extends Seeder
{
    public function run(): void
    {
        $note = Note::first();

        if (!$note) {
            return;
        }

        Summary::create([
            'user_id' => $note->user_id,
            'note_id' => $note->id,
            'subject_id' => $note->subject_id,
            'title' => 'Executive Summary: Mitosis vs Meiosis',
            'executive_summary' => 'Mitosis is responsible for vegetative growth resulting in identical diploid daughter cells. Meiosis generates genetic diversity through crossing over and independent assortment.',
            'key_points' => [
                'Mitosis: 1 division cycle, 2 daughter cells, identical genes.',
                'Meiosis: 2 division cycles, 4 daughter cells, genetic variations.',
                'Crossing over occurs during Prophase I of Meiosis.',
            ],
            'reading_time_minutes' => 3,
            'ai_model_used' => 'gpt-4o',
        ]);
    }
}
