<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AiTutorController;
use App\Http\Controllers\FocusTimerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudyGameController;
use App\Http\Controllers\SecondBrainController;
use App\Http\Controllers\KnowledgeGraphController;
use App\Http\Controllers\YouTubeLearningController;
use App\Http\Controllers\WebLearningController;
use App\Http\Controllers\CodePlaygroundController;
use App\Http\Controllers\WhiteboardController;
use App\Http\Controllers\ResearchAssistantController;

/*
|--------------------------------------------------------------------------
| Web Routes - AI Study Assistant Enterprise Edition
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.perform');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // 16 New Expansion Modules Routes
    Route::get('/second-brain', [SecondBrainController::class, 'index'])->name('secondbrain.index');
    Route::get('/knowledge-graph', [KnowledgeGraphController::class, 'index'])->name('knowledgegraph.index');
    Route::get('/youtube-learning', [YouTubeLearningController::class, 'index'])->name('youtube.index');
    Route::post('/youtube-learning/analyze', [YouTubeLearningController::class, 'analyze'])->name('youtube.analyze');
    Route::post('/youtube-learning/generate', [YouTubeLearningController::class, 'generate'])->name('youtube.generate');
    Route::post('/youtube-learning/export', [YouTubeLearningController::class, 'export'])->name('youtube.export');
    Route::get('/web-learning', [WebLearningController::class, 'index'])->name('weblearning.index');
    Route::post('/web-learning', [WebLearningController::class, 'process'])->name('weblearning.process');
    Route::get('/code-playground', [CodePlaygroundController::class, 'index'])->name('playground.index');
    Route::post('/code-playground/analyze', [CodePlaygroundController::class, 'analyze'])->name('playground.analyze');
    Route::get('/whiteboard', [WhiteboardController::class, 'index'])->name('whiteboard.index');
    Route::post('/whiteboard/ai-process', [WhiteboardController::class, 'aiProcess'])->name('whiteboard.ai-process');
    Route::post('/whiteboard/save', [WhiteboardController::class, 'save'])->name('whiteboard.save');
    Route::get('/research-assistant', [ResearchAssistantController::class, 'index'])->name('research.index');
    Route::post('/research-assistant/citation', [ResearchAssistantController::class, 'generateCitation'])->name('research.citation');

    // Study Games Arcade Routes
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [StudyGameController::class, 'index'])->name('index');
        Route::get('/scramble', [StudyGameController::class, 'scramble'])->name('scramble');
        Route::get('/hangman', [StudyGameController::class, 'hangman'])->name('hangman');
        Route::get('/memory', [StudyGameController::class, 'memory'])->name('memory');
        Route::get('/rapidfire', [StudyGameController::class, 'rapidfire'])->name('rapidfire');
        Route::get('/fillblanks', [StudyGameController::class, 'fillblanks'])->name('fillblanks');
        Route::get('/match', [StudyGameController::class, 'match'])->name('match');
        Route::get('/wheel', [StudyGameController::class, 'wheel'])->name('wheel');
        Route::get('/daily', [StudyGameController::class, 'daily'])->name('daily');

        Route::get('/api/data', [StudyGameController::class, 'getGameData'])->name('api.data');
        Route::post('/api/record-score', [StudyGameController::class, 'recordScore'])->name('api.record-score');
    });

    // Admin Panel Routes (Protected by admin middleware)
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/cache-clear', [AdminController::class, 'clearCache'])->name('admin.cache-clear');
    });

    // Pomodoro Focus Timer & Gamification Routes
    Route::get('/focus', [FocusTimerController::class, 'index'])->name('focus.index');
    Route::post('/focus/complete', [FocusTimerController::class, 'completeSession'])->name('focus.complete');

    // AI Tutor Chat & Tools Routes
    Route::get('/tutor', [AiTutorController::class, 'index'])->name('tutor.index');
    Route::post('/tutor/ask', [AiTutorController::class, 'ask'])->name('tutor.ask');
    Route::post('/tutor/{conversation}/rate', [AiTutorController::class, 'rate'])->name('tutor.rate');

    // Profile Settings Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Global Search Route
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('search');

    // Trash & Recovery Bin Routes
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/notes/{id}/restore', [TrashController::class, 'restoreNote'])->name('trash.notes.restore');
    Route::post('/trash/summaries/{id}/restore', [TrashController::class, 'restoreSummary'])->name('trash.summaries.restore');
    Route::delete('/trash/notes/{id}/force', [TrashController::class, 'forceDeleteNote'])->name('trash.notes.force');

    // Audit Logs & Security History
    Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');

    // Notes Module Routes
    Route::get('/notes/{note}/download', [NoteController::class, 'download'])->name('notes.download');
    Route::resource('notes', NoteController::class);

    // AI Summarizer Module Routes
    Route::post('/summaries/{summary}/retry', [SummaryController::class, 'retry'])->name('summaries.retry');
    Route::resource('summaries', SummaryController::class)->except(['edit', 'update']);

    // AI Quiz Generator Module Routes
    Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::resource('quizzes', QuizController::class)->except(['edit', 'update']);

    // Smart Flashcards Module Routes
    Route::get('/flashcards/review', [FlashcardController::class, 'review'])->name('flashcards.review');
    Route::post('/flashcards/{flashcard}/favorite', [FlashcardController::class, 'toggleFavorite'])->name('flashcards.favorite');
    Route::post('/flashcards/{flashcard}/record-review', [FlashcardController::class, 'recordReview'])->name('flashcards.record-review');
    Route::resource('flashcards', FlashcardController::class)->except(['edit', 'update', 'show']);
});

Route::get('/health', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Throwable $e) {
        $dbStatus = 'disconnected: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toIso8601String(),
        'environment' => config('app.env'),
        'database' => $dbStatus,
    ], 200);
});
Route::get('/db-test', function () {
    return [
        'DATABASE_URL' => env('DATABASE_URL'),
        'DB_HOST' => env('DB_HOST'),
        'DB_DATABASE' => env('DB_DATABASE'),
        'DB_USERNAME' => env('DB_USERNAME'),
        'DB_CONNECTION' => env('DB_CONNECTION'),
    ];
});

