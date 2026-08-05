<?php

namespace App\Actions\Summary;

use App\Models\Summary;
use App\Models\Note;
use App\Services\GeminiService;
use Exception;

class GenerateSummaryAction
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Generate an AI summary and save it to the database.
     *
     * @param int $userId
     * @param array $data
     * @return Summary
     * @throws Exception
     */
    public function execute(int $userId, array $data): Summary
    {
        $contentToSummarize = '';
        $noteId = $data['note_id'] ?? null;
        $subjectId = null;

        if ($noteId) {
            $note = Note::where('user_id', $userId)->findOrFail($noteId);
            $contentToSummarize = "Title: " . $note->title . "\n\nContent:\n" . $note->content;
            $subjectId = $note->subject_id;
        } else {
            $contentToSummarize = $data['raw_content'] ?? '';
        }

        if (empty(trim($contentToSummarize))) {
            throw new Exception('No content provided for AI summarization.');
        }

        // Call Gemini Service
        $aiResult = $this->geminiService->generateSummary(
            $contentToSummarize,
            $data['custom_instructions'] ?? ''
        );

        // Store summary in database
        return Summary::create([
            'user_id' => $userId,
            'note_id' => $noteId,
            'subject_id' => $subjectId,
            'title' => $aiResult['title'],
            'executive_summary' => $aiResult['executive_summary'],
            'key_points' => $aiResult['key_points'],
            'reading_time_minutes' => $aiResult['reading_time_minutes'],
            'ai_model_used' => $aiResult['model_used'],
        ]);
    }
}
