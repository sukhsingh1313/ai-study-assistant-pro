<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY', ''));
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        $this->baseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta/models');
    }

    /**
     * Dynamic AI Summary Generator with rich prompt engineering and randomness.
     */
    public function generateSummary(
        string $content,
        string $summaryType = 'Detailed Summary',
        string $targetLength = '300 words',
        string $instructions = ''
    ): array {
        $randomSeed = rand(1000, 9999);
        $timestamp = microtime(true);

        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return $this->getFallbackSummary($content, $summaryType, $targetLength);
        }

        $systemPrompt = "You are an Elite Academic AI Tutor & Curriculum Designer.\n"
            . "Task: Create a UNIQUE, highly insightful {$summaryType} from the provided study material.\n"
            . "Target Length: Approximate {$targetLength}.\n"
            . "Summary Format/Type: {$summaryType}.\n"
            . "Random Seed Identifier: {$randomSeed}-{$timestamp}.\n"
            . "Instruction: Focus on fresh angles, core definitions, critical insights, and structured takeaway bullet points. Avoid repeating previously generated phrasing.\n\n"
            . "Return ONLY a valid JSON object matching this schema EXACTLY:\n"
            . "{\n"
            . "  \"title\": \"Descriptive, engaging title for this {$summaryType}\",\n"
            . "  \"executive_summary\": \"A clear paragraph overview of the core concepts matching {$targetLength}.\",\n"
            . "  \"key_points\": [\"Insightful point 1\", \"Insightful point 2\", \"Insightful point 3\", \"Insightful point 4\", \"Insightful point 5\"],\n"
            . "  \"reading_time_minutes\": 3\n"
            . "}\n";

        if (!empty($instructions)) {
            $systemPrompt .= "\nAdditional User Specific Request: " . $instructions;
        }

        $userPrompt = "Study Content to Analyze:\n" . $content;
        $endpoint = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(25)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\n" . $userPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 0.7,
                    'topP' => 0.9,
                ]
            ]);

            if ($response->failed()) {
                $status = $response->status();
                Log::warning("Gemini API Request Failed (Status {$status}). Utilizing dynamic fallback synthesis.");
                return $this->getFallbackSummary($content, $summaryType, $targetLength, "HTTP {$status}");
            }

            $jsonText = $response->json('candidates.0.content.parts.0.text');
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($jsonText));
            $data = json_decode($cleanJson, true);

            return [
                'title' => $data['title'] ?? ($summaryType . ': ' . Str::limit(strip_tags($content), 30)),
                'executive_summary' => $data['executive_summary'] ?? 'Summary synthesized successfully.',
                'key_points' => $data['key_points'] ?? [],
                'reading_time_minutes' => (int) ($data['reading_time_minutes'] ?? 3),
                'model_used' => Str::limit($this->model, 45, ''),
            ];

        } catch (Exception $e) {
            Log::warning('GeminiService summary exception: ' . $e->getMessage());
            return $this->getFallbackSummary($content, $summaryType, $targetLength, 'Offline Mode');
        }
    }

    /**
     * Dynamic AI Quiz Generator with variable question counts, types, and difficulty levels.
     */
    public function generateQuiz(
        string $content,
        int $totalQuestions = 5,
        string $questionType = 'Mixed',
        string $difficulty = 'Medium'
    ): array {
        $randomSeed = rand(10000, 99999);

        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return $this->getFallbackQuiz($content, $totalQuestions, $questionType, $difficulty);
        }

        $systemPrompt = "You are an Expert University Examiner & Assessment Creator.\n"
            . "Task: Create a UNIQUE, high-quality practice quiz based on the provided study material.\n"
            . "Total Questions Requested: EXACTLY {$totalQuestions}.\n"
            . "Question Style/Type: {$questionType}.\n"
            . "Difficulty Rating: {$difficulty}.\n"
            . "Random Seed: {$randomSeed}.\n"
            . "Rule: Ensure every question tests a DIFFERENT sentence, concept, or technical definition from the text. Avoid duplicate questions.\n\n"
            . "Return ONLY a valid JSON object matching this schema EXACTLY:\n"
            . "{\n"
            . "  \"quiz_title\": \"{$difficulty} Level Practice Exam ({$questionType})\",\n"
            . "  \"questions\": [\n"
            . "    {\n"
            . "      \"question\": \"Clear, specific question text testing a core concept?\",\n"
            . "      \"options\": [\"Option A\", \"Option B\", \"Option C\", \"Option D\"],\n"
            . "      \"correct_answer\": \"Option A\",\n"
            . "      \"explanation\": \"Detailed rationale explaining why this answer is correct.\"\n"
            . "    }\n"
            . "  ]\n"
            . "}\n";

        $userPrompt = "Study Material Source:\n" . $content;
        $endpoint = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(30)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\n" . $userPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 0.8,
                    'topP' => 0.95,
                ]
            ]);

            if ($response->failed()) {
                Log::warning('Gemini Quiz API Request failed. Utilizing dynamic fallback quiz engine.');
                return $this->getFallbackQuiz($content, $totalQuestions, $questionType, $difficulty);
            }

            $jsonText = $response->json('candidates.0.content.parts.0.text');
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($jsonText));
            $data = json_decode($cleanJson, true);

            return [
                'quiz_title' => $data['quiz_title'] ?? ("{$difficulty} Quiz ({$totalQuestions} Qs)"),
                'questions' => $data['questions'] ?? [],
            ];

        } catch (Exception $e) {
            Log::warning('GeminiService generateQuiz Exception: ' . $e->getMessage());
            return $this->getFallbackQuiz($content, $totalQuestions, $questionType, $difficulty);
        }
    }

    /**
     * Dynamic fallback summary generator when API is offline or rate limited.
     */
    protected function getFallbackSummary(string $content, string $summaryType, string $targetLength, string $reason = ''): array
    {
        $cleanText = trim(strip_tags($content));
        $sentences = preg_split('/(?<=[.?!])\s+/', $cleanText);
        shuffle($sentences);

        $selectedSentences = array_slice($sentences, 0, min(5, count($sentences)));
        $execSummary = implode(' ', $selectedSentences);

        if (empty($execSummary)) {
            $execSummary = "Key concepts extracted from the study material covering theoretical foundations and practical applications.";
        }

        $seedVariant = rand(1, 4);
        $titles = [
            1 => "Comprehensive Analysis: " . Str::limit($cleanText, 30),
            2 => "Key Exam Highlights — " . $summaryType,
            3 => "Essential Study Notes: " . Str::limit($cleanText, 25),
            4 => "Rapid Revision Summary ({$targetLength})",
        ];

        $modelName = 'offline-fallback' . ($reason ? " ({$reason})" : '');

        return [
            'title' => $titles[$seedVariant],
            'executive_summary' => "[Format: {$summaryType}] " . $execSummary,
            'key_points' => [
                "Primary concept Focus: " . Str::limit($sentences[0] ?? 'Foundational principles', 80),
                "Critical definition: " . Str::limit($sentences[1] ?? 'Core terminology and logic', 80),
                "Analytical insight: " . Str::limit($sentences[2] ?? 'Systematic breakdown', 80),
                "Key takeaway for revision: " . Str::limit($sentences[3] ?? 'Exam preparation topic', 80),
            ],
            'reading_time_minutes' => max(1, (int) ceil(str_word_count($cleanText) / 200)),
            'model_used' => Str::limit($modelName, 45, ''),
        ];
    }

    /**
     * Dynamic fallback quiz generator supporting flexible question counts & types.
     */
    protected function getFallbackQuiz(string $content, int $totalQuestions, string $questionType, string $difficulty): array
    {
        $sentences = array_filter(preg_split('/(?<=[.?!])\s+/', strip_tags($content)));
        if (count($sentences) < 3) {
            $sentences = [
                'Active recall stimulates memory retrieval during revision sessions.',
                'Spaced repetition combats the exponential curve of memory decay.',
                'The Pomodoro Technique breaks complex work into 25-minute study intervals.',
                'Structured flashcards improve long-term conceptual retention.',
                'Feynman technique involves explaining complex topics in plain simple language.',
            ];
        }

        shuffle($sentences);
        $questions = [];

        for ($i = 0; $i < $totalQuestions; $i++) {
            $sentenceIndex = $i % count($sentences);
            $sentence = $sentences[$sentenceIndex];

            if ($questionType === 'True/False' || ($questionType === 'Mixed' && $i % 2 === 0)) {
                $isTrue = ($i % 2 === 0);
                $questionText = $isTrue 
                    ? "True or False: " . $sentence 
                    : "True or False: " . str_replace(['is', 'improves', 'breaks'], ['is not', 'prevents', 'delays'], $sentence);

                $questions[] = [
                    'question' => $questionText,
                    'options' => ['True', 'False'],
                    'correct_answer' => $isTrue ? 'True' : 'False',
                    'explanation' => "Based on the study material statement: '{$sentence}'",
                ];
            } else {
                $words = explode(' ', $sentence);
                $keyword = $words[array_rand($words)];

                $questions[] = [
                    'question' => "What key concept is highlighted in this statement? \"" . Str::limit($sentence, 60) . "\"",
                    'options' => [
                        "Option A: " . ucfirst($keyword),
                        "Option B: General Principle",
                        "Option C: Conceptual Framework",
                        "Option D: System Methodology"
                    ],
                    'correct_answer' => "Option A: " . ucfirst($keyword),
                    'explanation' => "Directly derived from the study notes section discussing " . $keyword . ".",
                ];
            }
        }

        return [
            'quiz_title' => "Dynamic {$difficulty} Quiz ({$questionType} • {$totalQuestions} Questions)",
            'questions' => $questions,
        ];
    }

    /**
     * Analyze a base64 image (diagram/whiteboard) using Gemini Multimodal capabilities.
     */
    public function analyzeImage(string $base64Image, string $action): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return [
                'content' => "Offline Mode: AI Image analysis requires a valid API key.",
                'items' => []
            ];
        }

        // Clean the base64 string
        $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);

        $systemPrompt = "";
        $isJsonExpected = false;

        switch ($action) {
            case 'explain':
                $systemPrompt = "You are an expert tutor. Analyze this diagram/whiteboard sketch. Explain the concepts, flow, or relationships shown in detail but clearly. Use markdown formatting for readability.";
                break;
            case 'notes':
                $systemPrompt = "You are an expert student. Convert this diagram/whiteboard sketch into structured, comprehensive study notes. Use headings, bullet points, and bold text for key terms.";
                break;
            case 'markdown':
                $systemPrompt = "Convert the textual and structural information in this image into a clean Markdown document. If it's a flowchart, describe the flow. If it's a table, create a markdown table.";
                break;
            case 'flashcards':
                $systemPrompt = "Analyze this diagram and create 5-10 study flashcards from its contents. Return ONLY a JSON object with this exact format: {\"items\": [{\"question\": \"...\", \"answer\": \"...\"}]}";
                $isJsonExpected = true;
                break;
            case 'quiz':
                $systemPrompt = "Analyze this diagram and create a 5-question multiple choice quiz from its contents. Return ONLY a JSON object with this exact format: {\"items\": [{\"question\": \"...\", \"options\": [\"A\", \"B\", \"C\", \"D\"], \"correct_answer\": \"...\"}]}";
                $isJsonExpected = true;
                break;
            default:
                $systemPrompt = "Describe this image in detail.";
        }

        $endpoint = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(45)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/png',
                                    'data' => $base64Data
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4, // lower temp for more accurate transcription
                ]
            ]);

            if ($response->failed()) {
                Log::warning('Gemini Image API Request failed. Status: ' . $response->status());
                return [
                    'content' => "Failed to process the image. API returned status " . $response->status(),
                    'items' => []
                ];
            }

            $jsonText = $response->json('candidates.0.content.parts.0.text', '');
            
            if ($isJsonExpected) {
                $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($jsonText));
                $data = json_decode($cleanJson, true);
                return [
                    'content' => '',
                    'items' => $data['items'] ?? $data ?? []
                ];
            }

            return [
                'content' => trim($jsonText),
                'items' => []
            ];

        } catch (Exception $e) {
            Log::error('GeminiService analyzeImage Exception: ' . $e->getMessage());
            return [
                'content' => "An error occurred while analyzing the image: " . $e->getMessage(),
                'items' => []
            ];
        }
    }

    /**
     * Process YouTube Transcripts into various study formats.
     */
    public function processYouTube(string $transcript, string $type): array
    {
        $randomSeed = rand(10000, 99999);
        $timestamp = microtime(true);

        if (empty($this->apiKey) || $this->apiKey === 'your_gemini_api_key_here') {
            return [
                'content' => "Offline Mode: Cannot process YouTube AI requests without a valid API key.",
                'reading_time' => 0,
                'difficulty' => 'Unknown',
                'study_time' => 'Unknown',
                'confidence' => 0
            ];
        }

        $systemPrompt = "You are an Elite Academic AI Tutor.\n"
            . "Task: Analyze the following YouTube video transcript and generate the requested output.\n"
            . "Requested Output Type: {$type}\n"
            . "Random Seed (Ensure unique output): {$randomSeed}-{$timestamp}\n"
            . "Format: Return your response strictly as a JSON object matching this schema:\n"
            . "{\n"
            . "  \"content\": \"Your formatted markdown content here. For MCQs or Flashcards, format them as clean markdown lists/tables so they render nicely.\",\n"
            . "  \"reading_time\": \"Estimated reading time in minutes (integer)\",\n"
            . "  \"difficulty\": \"Beginner, Intermediate, or Advanced\",\n"
            . "  \"study_time\": \"Estimated study time in minutes (e.g. '15 mins')\",\n"
            . "  \"confidence\": \"A score from 1-100 indicating how confident you are in the accuracy based on the transcript quality\"\n"
            . "}\n"
            . "Instruction: Never recreate the exact same phrasing. Always use the transcript provided. If the transcript has timestamps, refer to them where useful (especially for 'Timestamp Wise Notes').";

        $endpoint = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(60)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nTranscript:\n" . $transcript]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topP' => 0.9,
                ]
            ]);

            if ($response->failed()) {
                Log::warning('Gemini YouTube Processing API Request failed. Status: ' . $response->status());
                return [
                    'content' => "Failed to process the transcript. API returned status " . $response->status(),
                    'reading_time' => 0,
                    'difficulty' => 'Unknown',
                    'study_time' => 'Unknown',
                    'confidence' => 0
                ];
            }

            $jsonText = $response->json('candidates.0.content.parts.0.text', '');
            $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($jsonText));
            $data = json_decode($cleanJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback if AI didn't return JSON
                return [
                    'content' => $jsonText,
                    'reading_time' => max(1, (int) ceil(str_word_count($jsonText) / 200)),
                    'difficulty' => 'Moderate',
                    'study_time' => '10 mins',
                    'confidence' => 85
                ];
            }

            return [
                'content' => $data['content'] ?? 'No content generated.',
                'reading_time' => $data['reading_time'] ?? 5,
                'difficulty' => $data['difficulty'] ?? 'Moderate',
                'study_time' => $data['study_time'] ?? '15 mins',
                'confidence' => $data['confidence'] ?? 90
            ];

        } catch (Exception $e) {
            Log::error('GeminiService processYouTube Exception: ' . $e->getMessage());
            return [
                'content' => "An error occurred while analyzing the transcript: " . $e->getMessage(),
                'reading_time' => 0,
                'difficulty' => 'Unknown',
                'study_time' => 'Unknown',
                'confidence' => 0
            ];
        }
    }
}
