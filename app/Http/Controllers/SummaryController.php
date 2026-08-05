<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Subject;
use App\Models\Summary;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SummaryController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(Request $request): View
    {
        $user = Auth::user();

        try {
            $query = Summary::where('user_id', $user->id)
                ->with(['note', 'subject']);

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('executive_summary', 'like', "%{$search}%");
                });
            }

            $summaries = $query->latest()->paginate(9)->withQueryString();
            $subjects = Subject::where('user_id', $user->id)->get();
        } catch (\Throwable $e) {
            $summaries = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 9);
            $subjects = collect();
        }

        return view('summaries.index', compact('summaries', 'subjects'));
    }

    public function create(): View
    {
        try {
            $notes = Note::where('user_id', Auth::id())->latest()->get();
            $subjects = Subject::where('user_id', Auth::id())->get();
        } catch (\Throwable $e) {
            $notes = collect();
            $subjects = collect();
        }

        return view('summaries.create', compact('notes', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'note_id' => ['nullable', 'exists:notes,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'content' => ['required', 'string', 'min:30'],
            'summary_type' => ['nullable', 'string'],
            'target_length' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = Auth::id();
        $summaryType = $validated['summary_type'] ?? 'Detailed Summary';
        $targetLength = $validated['target_length'] ?? '300 words';

        $aiResult = $this->geminiService->generateSummary(
            $validated['content'],
            $summaryType,
            $targetLength,
            $validated['instructions'] ?? ''
        );

        $summary = Summary::create([
            'user_id' => $userId,
            'subject_id' => $validated['subject_id'] ?? null,
            'note_id' => $validated['note_id'] ?? null,
            'title' => $aiResult['title'],
            'executive_summary' => $aiResult['executive_summary'],
            'key_points' => $aiResult['key_points'],
            'reading_time_minutes' => $aiResult['reading_time_minutes'],
            'ai_model_used' => $aiResult['model_used'],
        ]);

        return redirect()->route('summaries.show', $summary)
            ->with('success', 'AI Summary generated successfully!');
    }

    public function show(Summary $summary): View
    {
        $this->authorizeOwner($summary);
        $summary->load(['note', 'subject']);

        return view('summaries.show', compact('summary'));
    }

    public function destroy(Summary $summary): RedirectResponse
    {
        $this->authorizeOwner($summary);
        $summary->delete();

        return redirect()->route('summaries.index')
            ->with('success', 'Summary moved to trash.');
    }

    public function retry(Summary $summary): RedirectResponse
    {
        $this->authorizeOwner($summary);

        $content = $summary->note ? $summary->note->content : $summary->executive_summary;
        $aiResult = $this->geminiService->generateSummary($content, 'Detailed Summary', '300 words');

        $summary->update([
            'title' => $aiResult['title'],
            'executive_summary' => $aiResult['executive_summary'],
            'key_points' => $aiResult['key_points'],
            'reading_time_minutes' => $aiResult['reading_time_minutes'],
            'ai_model_used' => $aiResult['model_used'],
        ]);

        return redirect()->route('summaries.show', $summary)
            ->with('success', 'Summary regenerated successfully!');
    }

    private function authorizeOwner(Summary $summary): void
    {
        if ($summary->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to summary.');
        }
    }
}
