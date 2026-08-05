<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Services\GeminiService;
use App\Services\YouTubeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class YouTubeLearningController extends Controller
{
    protected GeminiService $geminiService;
    protected YouTubeService $youtubeService;

    public function __construct(GeminiService $geminiService, YouTubeService $youtubeService)
    {
        $this->geminiService = $geminiService;
        $this->youtubeService = $youtubeService;
    }

    public function index(): View
    {
        return view('youtube.index');
    }

    /**
     * AJAX endpoint to analyze video and fetch transcript.
     */
    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = $request->input('url');

        try {
            $metadata = $this->youtubeService->getVideoMetadata($url);
            $transcript = $this->youtubeService->getTranscript($metadata['video_id']);

            return response()->json([
                'success' => true,
                'metadata' => $metadata,
                'transcript' => $transcript
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * AJAX endpoint to generate specific AI output based on transcript.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'transcript' => ['required', 'array'],
            'type' => ['required', 'string']
        ]);

        $transcriptArray = $request->input('transcript');
        $type = $request->input('type');

        // Compile transcript text
        $fullText = "";
        foreach ($transcriptArray as $segment) {
            $fullText .= "[{$segment['formatted_time']}] " . $segment['text'] . "\n";
        }

        $result = $this->geminiService->processYouTube($fullText, $type);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * AJAX endpoint to store generated content as a Note.
     */
    public function export(Request $request): JsonResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'metadata' => ['nullable', 'array']
        ]);

        $meta = $request->input('metadata', []);
        
        // Append metadata to note content cleanly
        $noteContent = $request->input('content');
        if (!empty($meta)) {
            $noteContent = "### Video Metadata\n"
                         . "- **Reading Time**: {$meta['reading_time']} mins\n"
                         . "- **Difficulty**: {$meta['difficulty']}\n"
                         . "- **Est. Study Time**: {$meta['study_time']}\n"
                         . "- **AI Confidence**: {$meta['confidence']}%\n\n"
                         . "---\n\n" . $noteContent;
        }

        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => 'YouTube AI: ' . $request->input('title'),
            'content' => $noteContent,
            'category' => 'YouTube Learning',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully saved to Notes!',
            'note_url' => route('notes.show', $note)
        ]);
    }
}
