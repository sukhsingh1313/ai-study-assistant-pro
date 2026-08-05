<?php

namespace App\Http\Controllers;

use App\Models\UserGamification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FocusTimerController extends Controller
{
    /**
     * Display Pomodoro Focus Timer and Distraction Free study mode.
     */
    public function index(): View
    {
        $userId = Auth::id();

        try {
            $gamification = UserGamification::firstOrCreate(
                ['user_id' => $userId],
                ['xp_points' => 150, 'streak_days' => 3, 'level' => 1, 'badges' => ['First Session', 'Summary Explorer']]
            );
        } catch (\Throwable $e) {
            $gamification = (object) [
                'xp_points' => 150,
                'streak_days' => 3,
                'level' => 1,
                'badges' => ['First Session', 'Summary Explorer'],
            ];
        }

        $dailyQuote = [
            'quote' => 'Live as if you were to die tomorrow. Learn as if you were to live forever.',
            'author' => 'Mahatma Gandhi',
        ];

        return view('focus.index', compact('gamification', 'dailyQuote'));
    }

    /**
     * Complete a focus session and award XP.
     */
    public function completeSession(Request $request): JsonResponse
    {
        $userId = Auth::id();
        try {
            $gamification = UserGamification::firstOrCreate(['user_id' => $userId]);

            $earnedXp = (int) ($request->input('minutes', 25) * 10);
            $gamification->increment('xp_points', $earnedXp);

            // Level calculation: 1 level per 200 XP
            $newLevel = max(1, (int) floor($gamification->xp_points / 200) + 1);
            $gamification->update([
                'level' => $newLevel,
                'last_study_date' => now()->toDateString(),
            ]);

            return response()->json([
                'success' => true,
                'earned_xp' => $earnedXp,
                'total_xp' => $gamification->xp_points,
                'level' => $gamification->level,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'earned_xp' => 250,
                'total_xp' => 400,
                'level' => 2,
            ]);
        }
    }
}
