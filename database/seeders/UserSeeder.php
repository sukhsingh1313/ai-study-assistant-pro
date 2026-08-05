<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Demo Student',
            'email' => 'student@example.com',
            'password' => Hash::make('password123'),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'bio' => 'Computer Science & AI enthusiast aiming for straight As this semester.',
            'study_goal' => 'Ace Midterm & Final Exams',
            'target_exam' => 'CS Finals 2026',
            'daily_study_minutes' => 90,
            'preferred_theme' => 'light',
        ]);
    }
}
