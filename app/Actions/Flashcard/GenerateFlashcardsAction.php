<?php

namespace App\Actions\Flashcard;

use App\Models\Flashcard;
use App\Models\Note;
use App\Models\Summary;
use App\Services\GeminiService;
use Illuminate\Support\Facades\DB;
use Exception;

class GenerateFlashcardsAction
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Generate flashcards using Gemini API and save to database inside a transaction.
     *
     * @param int $userId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function execute(int $userId, array $data): array
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
            throw new Exception('No valid study content provided for flashcard generation.');
        }

        $count = (int) ($data['count'] ?? 10);

        $aiResult = $this->geminiService->generateFlashcards($content, $count);

        return DB::transaction(function () use ($userId, $subjectId, $noteId, $summaryId, $aiResult) {
            $createdFlashcards = [];
            foreach ($aiResult['flashcards'] as $card) {
                $createdFlashcards[] = Flashcard::create([
                    'user_id' => $userId,
                    'subject_id' => $subjectId,
                    'note_id' => $noteId,
                    'summary_id' => $summaryId,
                    'question' => $card['question'],
                    'answer' => $card['answer'],
                    'difficulty_level' => $card['difficulty_level'] ?? 'medium',
                    'is_favorite' => false,
                    'last_reviewed_at' => null,
                    'review_count' => 0,
                ]);
            }
            return $createdFlashcards;
        });
    }
}
