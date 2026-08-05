@extends('layouts.dashboard')

@section('title', 'Study Games Arcade - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <!-- Header & Coin Balance -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Study Games Arcade 🎮</h2>
            <p class="text-muted mb-0">Learn through play! Earn XP, collect Coins, and master your notes with interactive AI games.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-2 fs-6 rounded-pill fw-bold" id="coinsDisplay">
                🪙 {{ number_format($gamification->coins ?? 50) }} Coins
            </span>
            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 fs-6 rounded-pill fw-bold" id="xpDisplay">
                ⭐ {{ number_format($gamification->xp_points ?? 200) }} XP (Lvl {{ $gamification->level ?? 1 }})
            </span>
        </div>
    </div>

    <!-- Games Performance Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white border d-flex flex-row align-items-center gap-3">
                <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-controller fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_games'] }}</h3>
                    <small class="text-muted fw-semibold">Games Played</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white border d-flex flex-row align-items-center gap-3">
                <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-bullseye fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['avg_accuracy'] }}%</h3>
                    <small class="text-muted fw-semibold">Avg Accuracy</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white border d-flex flex-row align-items-center gap-3">
                <div class="bg-warning-subtle text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-trophy-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_score'] }}</h3>
                    <small class="text-muted fw-semibold">Total Points</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white border d-flex flex-row align-items-center gap-3">
                <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-fire fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $gamification->streak_days }} Days</h3>
                    <small class="text-muted fw-semibold">Daily Streak</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 10 Study Games Grid -->
    <h4 class="fw-bold text-dark mb-3"><i class="bi bi-grid-fill text-primary me-2"></i>Choose a Game to Play</h4>

    <div class="row g-4">
        <!-- Game 1: Word Scramble -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-type fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Word Scramble</h5>
                    <p class="text-muted fs-7">Unscramble key terminology extracted dynamically from your study notes before the timer runs out!</p>
                </div>
                <a href="{{ route('games.scramble') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Play Word Scramble</a>
            </div>
        </div>

        <!-- Game 2: Hangman -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-danger-subtle text-danger rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-x fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Hangman</h5>
                    <p class="text-muted fs-7">Guess letters to reveal key subject terms with AI-generated hints. Protect your lives!</p>
                </div>
                <a href="{{ route('games.hangman') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Play Hangman</a>
            </div>
        </div>

        <!-- Game 3: Memory Match -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-success-subtle text-success rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-grid-3x3-gap-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Memory Match</h5>
                    <p class="text-muted fs-7">Flip cards to match terms with their correct definitions and formulas.</p>
                </div>
                <a href="{{ route('games.memory') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Play Memory Match</a>
            </div>
        </div>

        <!-- Game 4: Rapid Fire -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-warning-subtle text-warning rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-lightning-charge-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Rapid Fire</h5>
                    <p class="text-muted fs-7">Answer non-stop AI quiz questions in 30, 60, or 120 second sprints to build combo multipliers!</p>
                </div>
                <a href="{{ route('games.rapidfire') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Play Rapid Fire</a>
            </div>
        </div>

        <!-- Game 5: Fill in the Blanks -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-info-subtle text-info rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-pencil-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Fill in the Blanks</h5>
                    <p class="text-muted fs-7">Complete missing keywords in sentences generated directly from your uploaded notes.</p>
                </div>
                <a href="{{ route('games.fillblanks') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Play Fill Blanks</a>
            </div>
        </div>

        <!-- Game 6: Match the Following -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-purple-subtle text-primary rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-[#6f42c1] bi-arrow-left-right fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Match the Following</h5>
                    <p class="text-muted fs-7">Drag & drop or click to pair subjects, concepts, and definitions in record time.</p>
                </div>
                <a href="{{ route('games.match') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Play Matching</a>
            </div>
        </div>

        <!-- Game 7: Spin Wheel -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-warning-subtle text-warning rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-disc-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Wheel of Fortune</h5>
                    <p class="text-muted fs-7">Spin the wheel for random topic challenges, flashcard reviews, and bonus coins!</p>
                </div>
                <a href="{{ route('games.wheel') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Spin the Wheel</a>
            </div>
        </div>

        <!-- Game 8: Daily Challenge -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-success-subtle text-success rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-check-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">AI Daily Quest</h5>
                    <p class="text-muted fs-7">Complete today's unique study quest to unlock bonus coins and exclusive badges!</p>
                </div>
                <a href="{{ route('games.daily') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Daily Quest</a>
            </div>
        </div>

        <!-- Game 9: Flashcard Challenge -->
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom bg-white p-4 h-100 border text-center d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-card-heading fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Flashcard Challenge</h5>
                    <p class="text-muted fs-7">Test your active recall against 3D flip flashcards and track your accuracy streak.</p>
                </div>
                <a href="{{ route('flashcards.review') }}" class="btn btn-primary-custom w-100 py-2 mt-3">Start Flashcards</a>
            </div>
        </div>
    </div>
</div>
@endsection
