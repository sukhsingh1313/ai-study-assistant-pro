<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudyPlan;
use App\Models\Subject;

class StudyPlanSeeder extends Seeder
{
    public function run(): void
    {
        $subject = Subject::first();

        if (!$subject) {
            return;
        }

        StudyPlan::create([
            'user_id' => $subject->user_id,
            'subject_id' => $subject->id,
            'title' => 'Biology Midterm Mastery Plan',
            'description' => 'Complete review of Chapters 1-5 with AI summaries, flashcards, and mock quizzes.',
            'target_date' => now()->addDays(14)->toDateString(),
            'status' => 'in_progress',
            'goals' => [
                'Summarize Chapters 1 to 5',
                'Master 50 Biology Flashcards',
                'Achieve >90% on 3 practice quizzes',
            ],
        ]);
    }
}
