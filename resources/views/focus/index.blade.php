@extends('layouts.dashboard')

@section('title', 'Focus Timer & Gamification - AI Study Assistant')

@push('styles')
<style>
.timer-display {
    font-size: 5rem;
    font-weight: 800;
    letter-spacing: -2px;
    color: #0d6efd;
}
.fullscreen-mode {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: #0f172a;
    color: #ffffff;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" id="mainContainer">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pomodoro Focus Room & Achievements</h2>
            <p class="text-muted mb-0">Boost study productivity, earn XP, maintain your daily study streak, and enter distraction-free mode.</p>
        </div>
    </div>

    <!-- Gamification & XP Stats Bar -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="bg-warning-subtle text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-fire fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $gamification->streak_days }} Days</h3>
                    <small class="text-muted fw-semibold">Study Streak</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-star-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark" id="xpCounter">{{ $gamification->xp_points }} XP</h3>
                    <small class="text-muted fw-semibold">Total Experience</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-trophy-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark" id="levelDisplay">Level {{ $gamification->level }}</h3>
                    <small class="text-muted fw-semibold">Scholar Rank</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-custom p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-patch-check-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">{{ count($gamification->badges ?? []) }}</h3>
                    <small class="text-muted fw-semibold">Badges Unlocked</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Quote Banner -->
    <div class="card card-custom bg-gradient-blue text-white p-4 mb-4 border-0">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-quote fs-1 opacity-75"></i>
            <div>
                <h5 class="fst-italic fw-semibold mb-1">"{{ $dailyQuote['quote'] }}"</h5>
                <small class="opacity-90 fw-bold">— {{ $dailyQuote['author'] }}</small>
            </div>
        </div>
    </div>

    <!-- Main Pomodoro Focus Room -->
    <div class="card card-custom bg-white p-5 text-center border mb-4 shadow-sm" id="pomodoroCard">
        <div class="d-flex justify-content-center gap-2 mb-4">
            <button type="button" class="btn btn-primary-custom px-4 rounded-pill mode-btn" data-minutes="25">25 Min Focus</button>
            <button type="button" class="btn btn-outline-primary px-4 rounded-pill mode-btn" data-minutes="5">5 Min Short Break</button>
            <button type="button" class="btn btn-outline-primary px-4 rounded-pill mode-btn" data-minutes="15">15 Min Long Break</button>
        </div>

        <div class="my-3">
            <div class="timer-display font-monospace" id="timerDisplay">25:00</div>
            <p class="text-muted fs-7" id="timerStatus">Ready to begin your study session.</p>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="button" id="startBtn" class="btn btn-primary-custom px-5 py-3 fs-5 fw-bold rounded-pill">
                <i class="bi bi-play-fill me-1"></i> Start Focus Session
            </button>
            <button type="button" id="pauseBtn" class="btn btn-outline-secondary px-4 py-3 fs-5 fw-bold rounded-pill d-none">
                <i class="bi bi-pause-fill me-1"></i> Pause
            </button>
            <button type="button" id="resetBtn" class="btn btn-light border px-4 py-3 fs-5 fw-bold rounded-pill">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </button>
            <button type="button" id="distractionFreeBtn" class="btn btn-dark px-4 py-3 fs-5 fw-bold rounded-pill" title="Distraction Free Mode">
                <i class="bi bi-fullscreen me-1"></i> Distraction Free
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let totalSeconds = 25 * 60;
    let timerInterval = null;
    let isRunning = false;
    let currentSessionMinutes = 25;

    const timerDisplay = document.getElementById('timerDisplay');
    const timerStatus = document.getElementById('timerStatus');
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');
    const distractionFreeBtn = document.getElementById('distractionFreeBtn');
    const pomodoroCard = document.getElementById('pomodoroCard');
    const modeBtns = document.querySelectorAll('.mode-btn');

    function updateDisplay() {
        const mins = Math.floor(totalSeconds / 60);
        const secs = totalSeconds % 60;
        timerDisplay.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    modeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modeBtns.forEach(b => {
                b.classList.remove('btn-primary-custom', 'text-white');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary-custom', 'text-white');

            currentSessionMinutes = parseInt(this.getAttribute('data-minutes'));
            totalSeconds = currentSessionMinutes * 60;
            pauseTimer();
            updateDisplay();
            timerStatus.textContent = `Ready for ${currentSessionMinutes}-minute session.`;
        });
    });

    function startTimer() {
        if (isRunning) return;
        isRunning = true;
        startBtn.classList.add('d-none');
        pauseBtn.classList.remove('d-none');
        timerStatus.textContent = 'Session active. Stay focused!';

        timerInterval = setInterval(() => {
            if (totalSeconds > 0) {
                totalSeconds--;
                updateDisplay();
            } else {
                clearInterval(timerInterval);
                isRunning = false;
                startBtn.classList.remove('d-none');
                pauseBtn.classList.add('d-none');
                timerStatus.textContent = 'Session complete! Awarding XP...';
                
                // Complete session AJAX
                fetch('{{ route("focus.complete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ minutes: currentSessionMinutes })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('xpCounter').textContent = `${data.total_xp} XP`;
                        document.getElementById('levelDisplay').textContent = `Level ${data.level}`;
                        alert(`🎉 Great job! You completed a ${currentSessionMinutes}-minute focus session and earned +${data.earned_xp} XP!`);
                    }
                });
            }
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timerInterval);
        isRunning = false;
        startBtn.classList.remove('d-none');
        pauseBtn.classList.add('d-none');
        timerStatus.textContent = 'Session paused.';
    }

    startBtn.addEventListener('click', startTimer);
    pauseBtn.addEventListener('click', pauseTimer);
    resetBtn.addEventListener('click', function() {
        pauseTimer();
        totalSeconds = currentSessionMinutes * 60;
        updateDisplay();
        timerStatus.textContent = 'Timer reset.';
    });

    // Distraction Free Fullscreen Toggle
    distractionFreeBtn.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => console.log(err));
            pomodoroCard.classList.add('fullscreen-mode');
        } else {
            document.exitFullscreen();
            pomodoroCard.classList.remove('fullscreen-mode');
        }
    });

    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            pomodoroCard.classList.remove('fullscreen-mode');
        }
    });

    updateDisplay();
});
</script>
@endpush
