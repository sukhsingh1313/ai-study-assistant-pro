<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AiTutorController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Display AI Tutor Chat interface and conversation history.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $history = AiConversation::where('user_id', $userId)
            ->latest()
            ->take(15)
            ->get();

        $totalTokens = AiConversation::where('user_id', $userId)->sum('tokens_estimated');

        return view('tutor.index', compact('history', 'totalTokens'));
    }

    /**
     * Handle incoming AI Tutor question or prompt transformation.
     */
    public function ask(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:5000'],
            'prompt_type' => ['nullable', 'string', 'in:chat,beginner,teacher,rewrite,mindmap,viva,exercises'],
        ]);

        $prompt = $request->input('prompt');
        $promptType = $request->input('prompt_type', 'chat');
        $userId = Auth::id();

        $aiResult = $this->geminiService->generateAiResponse($prompt, $promptType);

        $conversation = AiConversation::create([
            'user_id' => $userId,
            'title' => Str::limit($prompt, 40),
            'prompt_type' => $promptType,
            'prompt' => $prompt,
            'response' => $aiResult['response'],
            'model_used' => $aiResult['model_used'],
            'tokens_estimated' => $aiResult['tokens_estimated'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'conversation' => $conversation,
            ]);
        }

        return redirect()->route('tutor.index')->with('success', 'AI Response generated!');
    }

    /**
     * Submit rating feedback for an AI response.
     */
    public function rate(AiConversation $conversation, Request $request): JsonResponse
    {
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['rating' => ['required', 'integer', 'min:1', 'max:5']]);

        $conversation->update(['rating' => $request->input('rating')]);

        return response()->json(['success' => true, 'rating' => $conversation->rating]);
    }
}
