<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Note;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $note = Note::first();

        if (!$note) {
            return;
        }

        $quiz = Quiz::create([
            'user_id' => $note->user_id,
            'subject_id' => $note->subject_id,
            'note_id' => $note->id,
            'title' => 'Biology Cell Division Quiz',
            'total_questions' => 2,
            'difficulty' => 'medium',
            'score' => 100.0,
            'is_completed' => true,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'How many daughter cells are created during mitosis?',
            'options' => ['1', '2', '4', '8'],
            'correct_answer' => '2',
            'user_answer' => '2',
            'explanation' => 'Mitosis undergoes one nuclear division producing 2 identical daughter cells.',
            'is_correct' => true,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'In which phase of meiosis does crossing over occur?',
            'options' => ['Prophase I', 'Metaphase II', 'Anaphase I', 'Telophase II'],
            'correct_answer' => 'Prophase I',
            'user_answer' => 'Prophase I',
            'explanation' => 'Homologous chromosomes exchange genetic segments during Prophase I.',
            'is_correct' => true,
        ]);
    }
}
