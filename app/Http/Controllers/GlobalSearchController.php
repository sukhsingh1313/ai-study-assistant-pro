<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Summary;
use App\Models\Quiz;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GlobalSearchController extends Controller
{
    /**
     * Perform multi-entity global search across all study materials.
     */
    public function search(Request $request): View
    {
        $queryText = trim($request->input('q', ''));
        $userId = Auth::id();

        if (empty($queryText)) {
            return view('search.results', [
                'queryText' => '',
                'notes' => collect(),
                'summaries' => collect(),
                'quizzes' => collect(),
                'flashcards' => collect(),
            ]);
        }

        $notes = Note::where('user_id', $userId)
            ->where(function ($q) use ($queryText) {
                $q->where('title', 'like', "%{$queryText}%")
                  ->orWhere('content', 'like', "%{$queryText}%")
                  ->orWhere('category', 'like', "%{$queryText}%");
            })->take(10)->get();

        $summaries = Summary::where('user_id', $userId)
            ->where(function ($q) use ($queryText) {
                $q->where('title', 'like', "%{$queryText}%")
                  ->orWhere('executive_summary', 'like', "%{$queryText}%");
            })->take(10)->get();

        $quizzes = Quiz::where('user_id', $userId)
            ->where('title', 'like', "%{$queryText}%")
            ->take(10)->get();

        $flashcards = Flashcard::where('user_id', $userId)
            ->where(function ($q) use ($queryText) {
                $q->where('question', 'like', "%{$queryText}%")
                  ->orWhere('answer', 'like', "%{$queryText}%");
            })->take(10)->get();

        return view('search.results', compact('queryText', 'notes', 'summaries', 'quizzes', 'flashcards'));
    }
}
