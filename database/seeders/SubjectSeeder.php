<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\User;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $subjects = [
            [
                'user_id' => $user->id,
                'name' => 'Cell Biology & Genetics',
                'code' => 'BIO-201',
                'color' => '#0d6efd',
                'description' => 'Fundamental concepts of cellular mechanics, mitosis, meiosis, and DNA replication.',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Data Structures & Algorithms',
                'code' => 'CS-302',
                'color' => '#198754',
                'description' => 'Arrays, trees, graphs, sorting algorithms, and asymptotic complexity analysis.',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Calculus II',
                'code' => 'MATH-102',
                'color' => '#0dcaf0',
                'description' => 'Integral calculus, series expansions, differential equations, and polar coordinates.',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
