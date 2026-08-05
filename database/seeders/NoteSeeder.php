<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;
use App\Models\Subject;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $subject = Subject::first();

        if (!$subject) {
            return;
        }

        Note::create([
            'user_id' => $subject->user_id,
            'subject_id' => $subject->id,
            'title' => 'Mitosis vs Meiosis Detailed Comparison',
            'content' => 'Mitosis produces two genetically identical diploid somatic cells for growth and repair. Meiosis undergoes two rounds of nuclear division to produce four genetically distinct haploid gametes for sexual reproduction.',
            'raw_file_path' => 'notes/bio_mitosis_meiosis.pdf',
            'file_type' => 'pdf',
            'word_count' => 450,
        ]);
    }
}
