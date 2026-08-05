<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserGamification;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Demo Student',
                'password' => Hash::make('password123'),
            ]
        );

        Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => 'Computer Science & AI enthusiast aiming for straight As this semester.',
                'study_goal' => 'Ace Midterm & Final Exams',
                'target_exam' => 'CS Finals 2026',
                'daily_study_minutes' => 90,
                'preferred_theme' => 'light',
            ]
        );

        UserGamification::firstOrCreate(
            ['user_id' => $user->id],
            [
                'xp_points' => 350,
                'streak_days' => 5,
                'level' => 2,
            ]
        );
    }
}
