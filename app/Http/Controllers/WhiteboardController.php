<?php

namespace App\Http\Controllers;

use App\Models\Whiteboard;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WhiteboardController extends Controller
{
    public function index(): View
    {
        $whiteboards = Whiteboard::where('user_id', Auth::id())->latest()->get();
        return view('whiteboard.index', compact('whiteboards'));
    }

    public function aiProcess(Request $request, \App\Services\GeminiService $geminiService)
    {
        $request->validate([
            'image' => ['required', 'string'],
            'action' => ['required', 'string', 'in:explain,notes,markdown,flashcards,quiz']
        ]);

        $base64Image = $request->input('image');
        $action = $request->input('action');

        $result = $geminiService->analyzeImage($base64Image, $action);

        if (empty($result['content']) && empty($result['items'])) {
            return response()->json(['message' => 'Failed to analyze diagram.'], 500);
        }

        return response()->json($result);
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'canvas_data' => ['required', 'string']
        ]);

        $whiteboard = Whiteboard::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'canvas_data' => $request->input('canvas_data'),
        ]);

        return response()->json([
            'message' => 'Whiteboard saved successfully!',
            'whiteboard_id' => $whiteboard->id
        ]);
    }
}
