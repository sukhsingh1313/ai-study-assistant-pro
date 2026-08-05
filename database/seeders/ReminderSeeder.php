<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reminder;
use App\Models\User;

class ReminderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        Reminder::create([
            'user_id' => $user->id,
            'title' => 'Review Bio-201 Flashcards',
            'remind_at' => now()->addHours(3),
            'is_completed' => false,
            'frequency' => 'daily',
        ]);
    }
}
