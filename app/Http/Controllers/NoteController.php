<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Subject;
use App\Http\Requests\Note\StoreNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Actions\Note\StoreNoteAction;
use App\Actions\Note\UpdateNoteAction;
use App\Actions\Note\DeleteNoteAction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NoteController extends Controller
{
    /**
     * Display a paginated listing of the user's notes with search/filters.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Note::where('user_id', $user->id)
            ->with('subject');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->input('subject_id'));
        }

        if ($request->filled('tag')) {
            $tag = $request->input('tag');
            $query->whereJsonContains('tags', $tag);
        }

        $notes = $query->latest()->paginate(9)->withQueryString();
        $subjects = Subject::where('user_id', $user->id)->get();
        $categories = Note::where('user_id', $user->id)->whereNotNull('category')->distinct()->pluck('category');

        return view('notes.index', compact('notes', 'subjects', 'categories'));
    }

    /**
     * Show form for creating a new note.
     */
    public function create(): View
    {
        $subjects = Subject::where('user_id', Auth::id())->get();
        return view('notes.create', compact('subjects'));
    }

    /**
     * Store a newly created note.
     */
    public function store(StoreNoteRequest $request, StoreNoteAction $action): RedirectResponse
    {
        $note = $action->execute(Auth::id(), $request->validated(), $request->file('file'));

        return redirect()->route('notes.show', $note)
            ->with('success', 'Study note created successfully!');
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note): View
    {
        $this->authorizeOwner($note);
        $note->load(['subject', 'summaries', 'quizzes', 'flashcards']);

        return view('notes.show', compact('note'));
    }

    /**
     * Show form for editing the specified note.
     */
    public function edit(Note $note): View
    {
        $this->authorizeOwner($note);
        $subjects = Subject::where('user_id', Auth::id())->get();

        return view('notes.edit', compact('note', 'subjects'));
    }

    /**
     * Update the specified note in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note, UpdateNoteAction $action): RedirectResponse
    {
        $action->execute($note, $request->validated(), $request->file('file'));

        return redirect()->route('notes.show', $note)
            ->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified note from storage.
     */
    public function destroy(Note $note, DeleteNoteAction $action): RedirectResponse
    {
        $this->authorizeOwner($note);
        $action->execute($note);

        return redirect()->route('notes.index')
            ->with('success', 'Note deleted successfully.');
    }

    /**
     * Download attached file if present.
     */
    public function download(Note $note)
    {
        $this->authorizeOwner($note);

        if (!$note->hasFile() || !Storage::exists($note->file_path)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return Storage::download($note->file_path, $note->title . '.' . $note->file_type);
    }

    private function authorizeOwner(Note $note): void
    {
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to note.');
        }
    }
}
