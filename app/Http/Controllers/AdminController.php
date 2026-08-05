<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use App\Models\Summary;
use App\Models\Quiz;
use App\Models\Flashcard;
use App\Models\AuditLog;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display System Admin Control Panel & Analytics Dashboard.
     */
    public function index(): View
    {
        $metrics = [
            'total_users' => User::count(),
            'total_notes' => Note::count(),
            'total_summaries' => Summary::count(),
            'total_quizzes' => Quiz::count(),
            'total_flashcards' => Flashcard::count(),
            'total_audit_logs' => AuditLog::count(),
        ];

        // Storage usage calculation
        $storageBytes = 0;
        if (Storage::disk('public')->exists('')) {
            foreach (Storage::disk('public')->allFiles() as $file) {
                $storageBytes += Storage::disk('public')->size($file);
            }
        }
        $metrics['storage_mb'] = number_format($storageBytes / (1024 * 1024), 2);

        $recentUsers = User::latest()->take(10)->get();
        $recentLogins = LoginHistory::latest('login_at')->take(10)->get();

        return view('admin.index', compact('metrics', 'recentUsers', 'recentLogins'));
    }

    /**
     * Clear system configuration and view cache.
     */
    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'System caches cleared successfully!');
    }
}
