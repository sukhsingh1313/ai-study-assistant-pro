<?php

namespace App\Http\Controllers;

use App\Models\SecondBrain;
use App\Models\Note;
use App\Models\Quiz;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SecondBrainController extends Controller
{
    /**
     * Display AI Second Brain Dashboard with weak/strong topics and recommendations.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $secondBrain = SecondBrain::firstOrCreate(
            ['user_id' => $userId],
            [
                'weak_topics' => ['Data Structures (Trees)', 'Calculus Integration'],
                'strong_topics' => ['PHP Fundamentals', 'Database SQL Indexing'],
                'recommendations' => [
                    ['type' => 'quiz', 'title' => 'Take Data Structures Practice Test', 'action' => 'quizzes.create'],
                    ['type' => 'flashcard', 'title' => 'Review Binary Search Trees deck', 'action' => 'flashcards.index'],
                    ['type' => 'note', 'title' => 'Read Calculus Integration Summary', 'action' => 'summaries.index'],
                ],
                'learning_pattern' => 'Visual & Active Recall Practice',
            ]
        );

        $notesCount = Note::where('user_id', $userId)->count();
        $quizzesCount = Quiz::where('user_id', $userId)->count();

        return view('secondbrain.index', compact('secondBrain', 'notesCount', 'quizzesCount'));
    }
}
