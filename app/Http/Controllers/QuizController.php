<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Subject;
use App\Models\Summary;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(): View
    {
        $quizzes = Quiz::where('user_id', Auth::id())
            ->with(['note', 'subject'])
            ->latest()
            ->paginate(9);

        return view('quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        $notes = Note::where('user_id', Auth::id())->latest()->get();
        $subjects = Subject::where('user_id', Auth::id())->get();

        return view('quizzes.create', compact('notes', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'note_id' => ['nullable', 'exists:notes,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'content' => ['required', 'string', 'min:30'],
            'total_questions' => ['required', 'integer', 'in:5,10,15,20,25,50'],
            'question_type' => ['required', 'string', 'in:MCQ,True/False,Fill in the blanks,Short Answer,Mixed'],
            'difficulty' => ['required', 'string', 'in:Easy,Medium,Hard,Mixed'],
        ]);

        $userId = Auth::id();
        $totalQuestions = (int) $validated['total_questions'];
        $questionType = $validated['question_type'];
        $difficulty = $validated['difficulty'];

        $aiQuizData = $this->geminiService->generateQuiz(
            $validated['content'],
            $totalQuestions,
            $questionType,
            $difficulty
        );

        $quiz = Quiz::create([
            'user_id' => $userId,
            'subject_id' => $validated['subject_id'] ?? null,
            'note_id' => $validated['note_id'] ?? null,
            'title' => $aiQuizData['quiz_title'] ?? "Practice Quiz ({$totalQuestions} Qs)",
            'total_questions' => count($aiQuizData['questions'] ?? []),
            'difficulty' => $difficulty,
            'timer_minutes' => max(5, ceil(count($aiQuizData['questions'] ?? []) * 1.5)),
            'score' => null,
            'is_completed' => false,
        ]);

        foreach ($aiQuizData['questions'] as $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $q['question'] ?? 'Sample Question?',
                'options' => $q['options'] ?? ['True', 'False'],
                'correct_answer' => $q['correct_answer'] ?? 'True',
                'explanation' => $q['explanation'] ?? null,
            ]);
        }

        return redirect()->route('quizzes.take', $quiz)
            ->with('success', 'Practice Quiz generated successfully!');
    }

    public function show(Quiz $quiz): View
    {
        $this->authorizeOwner($quiz);
        $quiz->load(['questions', 'note', 'subject']);

        return view('quizzes.show', compact('quiz'));
    }

    public function take(Quiz $quiz): View
    {
        $this->authorizeOwner($quiz);
        $quiz->load('questions');

        return view('quizzes.take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeOwner($quiz);
        $quiz->load('questions');

        $answers = $request->input('answers', []);
        $correctCount = 0;

        foreach ($quiz->questions as $question) {
            $userAns = trim($answers[$question->id] ?? '');
            if (strcasecmp($userAns, trim($question->correct_answer)) === 0) {
                $correctCount++;
            }
        }

        $total = max(1, $quiz->questions->count());
        $percentageScore = round(($correctCount / $total) * 100, 2);

        $quiz->update([
            'score' => $percentageScore,
            'is_completed' => true,
        ]);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', "Quiz completed! Your score: {$percentageScore}% ({$correctCount}/{$total})");
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorizeOwner($quiz);
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz moved to trash bin.');
    }

    private function authorizeOwner(Quiz $quiz): void
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to quiz.');
        }
    }
}
