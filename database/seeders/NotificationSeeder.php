<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Summary Ready',
            'message' => 'Your AI Summary for "Mitosis vs Meiosis" has been generated successfully.',
            'type' => 'success',
            'read_at' => null,
        ]);
    }
}
