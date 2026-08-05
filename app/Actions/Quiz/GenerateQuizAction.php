<?php

namespace App\Actions\Quiz;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Note;
use App\Models\Summary;
use App\Services\GeminiService;
use Illuminate\Support\Facades\DB;
use Exception;

class GenerateQuizAction
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Generate Quiz using Gemini API and save to database inside a transaction.
     *
     * @param int $userId
     * @param array $data
     * @return Quiz
     * @throws Exception
     */
    public function execute(int $userId, array $data): Quiz
    {
        $content = '';
        $subjectId = null;
        $noteId = $data['note_id'] ?? null;
        $summaryId = $data['summary_id'] ?? null;

        if ($summaryId) {
            $summary = Summary::where('user_id', $userId)->findOrFail($summaryId);
            $content = "Title: " . $summary->title . "\nExecutive Summary: " . $summary->executive_summary . "\nKey Points: " . implode(', ', $summary->key_points ?? []);
            $subjectId = $summary->subject_id;
            $noteId = $summary->note_id;
        } elseif ($noteId) {
            $note = Note::where('user_id', $userId)->findOrFail($noteId);
            $content = "Title: " . $note->title . "\nContent: " . $note->content;
            $subjectId = $note->subject_id;
        } else {
            $content = $data['raw_content'] ?? '';
        }

        if (empty(trim($content))) {
            throw new Exception('No valid study content found to generate quiz questions.');
        }

        $difficulty = $data['difficulty'] ?? 'medium';
        $totalQuestions = (int) ($data['total_questions'] ?? 5);
        $timerMinutes = (int) ($data['timer_minutes'] ?? 10);

        // Call Gemini Service
        $aiResult = $this->geminiService->generateQuiz($content, $difficulty, $totalQuestions);

        return DB::transaction(function () use ($userId, $subjectId, $noteId, $summaryId, $difficulty, $timerMinutes, $aiResult) {
            $quiz = Quiz::create([
                'user_id' => $userId,
                'subject_id' => $subjectId,
                'note_id' => $noteId,
                'summary_id' => $summaryId,
                'title' => $aiResult['quiz_title'],
                'total_questions' => count($aiResult['questions']),
                'difficulty' => $difficulty,
                'timer_minutes' => $timerMinutes,
                'score' => null,
                'is_completed' => false,
            ]);

            foreach ($aiResult['questions'] as $q) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $q['question'],
                    'options' => $q['options'] ?? ['True', 'False'],
                    'correct_answer' => $q['correct_answer'],
                    'user_answer' => null,
                    'explanation' => $q['explanation'] ?? '',
                    'is_correct' => null,
                ]);
            }

            return $quiz;
        });
    }
}
