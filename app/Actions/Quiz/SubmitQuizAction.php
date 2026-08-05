<?php

namespace App\Actions\Quiz;

use App\Models\Quiz;

class SubmitQuizAction
{
    /**
     * Grade submitted answers, compute percentage score, and mark quiz completed.
     *
     * @param Quiz $quiz
     * @param array $userAnswers [question_id => user_answer_text]
     * @return Quiz
     */
    public function execute(Quiz $quiz, array $userAnswers): Quiz
    {
        $quiz->load('questions');

        $correctCount = 0;
        $total = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            $submittedAnswer = $userAnswers[$question->id] ?? null;
            $isCorrect = false;

            if ($submittedAnswer !== null) {
                // String comparison (case-insensitive & trimmed)
                $isCorrect = (strtolower(trim($submittedAnswer)) === strtolower(trim($question->correct_answer)));
            }

            if ($isCorrect) {
                $correctCount++;
            }

            $question->update([
                'user_answer' => $submittedAnswer,
                'is_correct' => $isCorrect,
            ]);
        }

        $percentageScore = $total > 0 ? round(($correctCount / $total) * 100, 2) : 0.00;

        $quiz->update([
            'score' => $percentageScore,
            'is_completed' => true,
        ]);

        return $quiz;
    }
}
