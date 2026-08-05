<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Summary;
use App\Models\Quiz;
use App\Models\Flashcard;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TrashController extends Controller
{
    /**
     * Display deleted items in the Trash bin.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $deletedNotes = Note::onlyTrashed()->where('user_id', $userId)->get();
        $deletedSummaries = Summary::onlyTrashed()->where('user_id', $userId)->get();
        $deletedQuizzes = Quiz::onlyTrashed()->where('user_id', $userId)->get();
        $deletedFlashcards = Flashcard::onlyTrashed()->where('user_id', $userId)->get();

        $totalTrashCount = $deletedNotes->count() + $deletedSummaries->count() + $deletedQuizzes->count() + $deletedFlashcards->count();

        return view('trash.index', compact(
            'deletedNotes',
            'deletedSummaries',
            'deletedQuizzes',
            'deletedFlashcards',
            'totalTrashCount'
        ));
    }

    /**
     * Restore a soft-deleted note.
     */
    public function restoreNote(int $id): RedirectResponse
    {
        $note = Note::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $note->restore();

        return redirect()->back()->with('success', 'Note restored successfully!');
    }

    /**
     * Restore a soft-deleted summary.
     */
    public function restoreSummary(int $id): RedirectResponse
    {
        $summary = Summary::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $summary->restore();

        return redirect()->back()->with('success', 'Summary restored successfully!');
    }

    /**
     * Permanently force delete a note.
     */
    public function forceDeleteNote(int $id): RedirectResponse
    {
        $note = Note::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $note->forceDelete();

        return redirect()->back()->with('success', 'Note permanently deleted.');
    }
}
