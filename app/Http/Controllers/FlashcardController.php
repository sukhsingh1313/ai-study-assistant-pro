<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Summary;
use App\Models\Note;
use App\Models\Subject;
use App\Http\Requests\Flashcard\GenerateFlashcardsRequest;
use App\Actions\Flashcard\GenerateFlashcardsAction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Exception;

class FlashcardController extends Controller
{
    /**
     * Display flashcard decks and collection overview.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        try {
            $query = Flashcard::where('user_id', $user->id)
                ->with(['subject', 'note', 'summary']);

            if ($request->boolean('favorites')) {
                $query->where('is_favorite', true);
            }

            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->input('subject_id'));
            }

            $flashcards = $query->latest()->get();
            $subjects = Subject::where('user_id', $user->id)->get();
            $totalFavorites = Flashcard::where('user_id', $user->id)->where('is_favorite', true)->count();
        } catch (\Throwable $e) {
            $flashcards = collect();
            $subjects = collect();
            $totalFavorites = 0;
        }

        return view('flashcards.index', compact('flashcards', 'subjects', 'totalFavorites'));
    }

    /**
     * Show form to generate new flashcards.
     */
    public function create(Request $request): View
    {
        $user = Auth::user();
        try {
            $summaries = Summary::where('user_id', $user->id)->get();
            $notes = Note::where('user_id', $user->id)->get();
        } catch (\Throwable $e) {
            $summaries = collect();
            $notes = collect();
        }

        $selectedSummaryId = $request->input('summary_id');
        $selectedNoteId = $request->input('note_id');

        return view('flashcards.create', compact('summaries', 'notes', 'selectedSummaryId', 'selectedNoteId'));
    }

    /**
     * Generate flashcards using Gemini API.
     */
    public function store(GenerateFlashcardsRequest $request, GenerateFlashcardsAction $action): RedirectResponse
    {
        try {
            $cards = $action->execute(Auth::id(), $request->validated());

            return redirect()->route('flashcards.review')
                ->with('success', 'Generated ' . count($cards) . ' new smart flashcards!');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to generate flashcards: ' . $e->getMessage());
        }
    }

    /**
     * Interactive 3D Flip Card Flashcard Review Mode.
     */
    public function review(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        try {
            $query = Flashcard::where('user_id', $user->id);

            if ($request->boolean('favorites')) {
                $query->where('is_favorite', true);
            }

            if ($request->filled('subject_id')) {
                $query->where('subject_id', $request->input('subject_id'));
            }

            $cards = $query->get();
        } catch (\Throwable $e) {
            $cards = collect();
        }

        if ($cards->isEmpty()) {
            return redirect()->route('flashcards.index')
                ->with('info', 'No flashcards available for review. Create a deck first!');
        }

        return view('flashcards.review', compact('cards'));
    }

    /**
     * Toggle favorite status on a flashcard.
     */
    public function toggleFavorite(Flashcard $flashcard): JsonResponse|RedirectResponse
    {
        $this->authorizeOwner($flashcard);

        $flashcard->is_favorite = !$flashcard->is_favorite;
        $flashcard->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_favorite' => $flashcard->is_favorite]);
        }

        return redirect()->back()->with('success', 'Favorite status updated.');
    }

    /**
     * Record card review completion timestamp and count.
     */
    public function recordReview(Flashcard $flashcard): JsonResponse
    {
        $this->authorizeOwner($flashcard);

        $flashcard->increment('review_count');
        $flashcard->last_reviewed_at = now();
        $flashcard->save();

        return response()->json(['success' => true, 'review_count' => $flashcard->review_count]);
    }

    /**
     * Delete a flashcard.
     */
    public function destroy(Flashcard $flashcard): RedirectResponse
    {
        $this->authorizeOwner($flashcard);
        $flashcard->delete();

        return redirect()->route('flashcards.index')->with('success', 'Flashcard deleted.');
    }

    private function authorizeOwner(Flashcard $flashcard): void
    {
        if ($flashcard->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to flashcard.');
        }
    }
}
