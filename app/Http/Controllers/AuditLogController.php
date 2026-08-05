<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display activity log timeline and login security history.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $auditLogs = AuditLog::where('user_id', $userId)
            ->latest()
            ->paginate(15);

        $loginHistories = LoginHistory::where('user_id', $userId)
            ->latest('login_at')
            ->take(10)
            ->get();

        return view('audit.index', compact('auditLogs', 'loginHistories'));
    }
}
