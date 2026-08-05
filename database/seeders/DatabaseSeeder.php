<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SubjectSeeder::class,
            NoteSeeder::class,
            SummarySeeder::class,
            QuizSeeder::class,
            FlashcardSeeder::class,
            StudyPlanSeeder::class,
            ReminderSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
