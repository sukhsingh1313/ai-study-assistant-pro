<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Summary;
use App\Models\Quiz;
use App\Models\Flashcard;
use App\Models\UserGamification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Enterprise Student Dashboard with widgets and analytics.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $stats = [
            'total_notes' => Note::where('user_id', $userId)->count(),
            'total_summaries' => Summary::where('user_id', $userId)->count(),
            'quizzes_completed' => Quiz::where('user_id', $userId)->where('is_completed', true)->count(),
            'total_flashcards' => Flashcard::where('user_id', $userId)->count(),
        ];

        $gamification = UserGamification::firstOrCreate(
            ['user_id' => $userId],
            ['xp_points' => 150, 'streak_days' => 3, 'level' => 1]
        );

        $recentNotes = Note::where('user_id', $userId)->latest()->take(5)->get();
        $recentSummaries = Summary::where('user_id', $userId)->latest()->take(5)->get();
        $recentQuizzes = Quiz::where('user_id', $userId)->latest()->take(5)->get();

        // Calculate storage
        $storageBytes = 0;
        if (Storage::disk('public')->exists('')) {
            foreach (Storage::disk('public')->allFiles() as $file) {
                $storageBytes += Storage::disk('public')->size($file);
            }
        }
        $storageMb = number_format($storageBytes / (1024 * 1024), 2);

        $productivityScore = 88; // Percentage

        return view('dashboard', compact(
            'stats',
            'gamification',
            'recentNotes',
            'recentSummaries',
            'recentQuizzes',
            'storageMb',
            'productivityScore'
        ));
    }
}
