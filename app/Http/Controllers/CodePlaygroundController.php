<?php

namespace App\Http\Controllers;

use App\Models\CodingSnippet;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CodePlaygroundController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(): View
    {
        $snippets = CodingSnippet::where('user_id', Auth::id())->latest()->take(10)->get();
        return view('playground.index', compact('snippets'));
    }

    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'language' => ['required', 'string'],
        ]);

        $code = $request->input('code');
        $language = $request->input('language');

        $prompt = "Language: {$language}\nAnalyze this code for bugs, explain how it works step-by-step, and provide an optimized version with test cases:\n\n```{$language}\n{$code}\n```";

        $aiResult = $this->geminiService->generateAiResponse($prompt, 'chat');

        $snippet = CodingSnippet::create([
            'user_id' => Auth::id(),
            'language' => $language,
            'title' => ucfirst($language) . " Snippet (" . date('H:i') . ")",
            'code' => $code,
            'ai_explanation' => $aiResult['response'],
        ]);

        return response()->json([
            'success' => true,
            'snippet' => $snippet,
            'analysis' => $aiResult['response'],
        ]);
    }
}
