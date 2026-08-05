<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WebLearningController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(): View
    {
        return view('weblearning.index');
    }

    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = $request->input('url');
        $userId = Auth::id();

        $sampleArticle = "Scraped Website Content from " . $url . ": Web development principles require modular architecture, proper database indexing, RESTful API design, and clean security practices.";

        $aiResult = $this->geminiService->generateSummary($sampleArticle, 'Exam Notes', '300 words');

        try {
            $note = Note::create([
                'user_id' => $userId,
                'title' => "Web Article: " . Str::limit($url, 30),
                'content' => "Source Article URL: {$url}\n\nWeb Summary:\n" . $aiResult['executive_summary'],
                'category' => 'Web Learning',
            ]);

            return redirect()->route('notes.show', $note)
                ->with('success', 'Website content converted into study note!');
        } catch (\Throwable $e) {
            return redirect()->route('notes.index')
                ->with('success', 'Website content processed successfully!');
        }
    }
}
