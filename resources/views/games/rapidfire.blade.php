@extends('layouts.dashboard')

@section('title', 'AI Rapid Fire - Study Games Arcade')

@push('styles')
<style>
.timer-badge {
    font-size: 1.5rem;
    font-weight: bold;
}
.combo-badge {
    font-size: 1.2rem;
    font-weight: bold;
    color: #ffc107;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Rapid Fire ⚡</h2>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-dark text-white px-3 py-2 timer-badge font-monospace" id="timerDisplay">30s</span>
            <span class="badge bg-warning text-dark px-3 py-2 combo-badge" id="comboDisplay">🔥 0x Combo</span>
        </div>
    </div>

    <!-- Mode Selector -->
    <div class="card card-custom p-3 bg-white mb-4 border text-center" id="modeCard">
        <h5 class="fw-bold text-dark mb-3">Select Timer Speed Sprint</h5>
        <div class="d-flex justify-content-center gap-3">
            <button type="button" class="btn btn-primary-custom px-4 rounded-pill start-btn" data-time="30">30 Seconds Sprint</button>
            <button type="button" class="btn btn-outline-primary px-4 rounded-pill start-btn" data-time="60">60 Seconds Endurance</button>
            <button type="button" class="btn btn-outline-primary px-4 rounded-pill start-btn" data-time="120">120 Seconds Marathon</button>
        </div>
    </div>

    <!-- Question Arena -->
    <div class="card card-custom bg-white p-5 border text-center d-none" id="arenaCard">
        <h4 class="fw-bold text-dark mb-4" id="rapidQuestion">Loading question...</h4>

        <div class="d-flex justify-content-center gap-3">
            <button type="button" class="btn btn-success btn-lg px-5 py-3 fw-bold rounded-pill ans-btn" data-ans="True">
                <i class="bi bi-check-circle me-1"></i> TRUE
            </button>
            <button type="button" class="btn btn-danger btn-lg px-5 py-3 fw-bold rounded-pill ans-btn" data-ans="False">
                <i class="bi bi-x-circle me-1"></i> FALSE
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let timerSeconds = 30;
    let timerInterval = null;
    let score = 0;
    let combo = 0;
    let totalAsked = 0;
    let correctCount = 0;
    let questions = [];
    let currentQ = null;

    const timerDisplay = document.getElementById('timerDisplay');
    const comboDisplay = document.getElementById('comboDisplay');
    const modeCard = document.getElementById('modeCard');
    const arenaCard = document.getElementById('arenaCard');
    const rapidQuestion = document.getElementById('rapidQuestion');
    const startBtns = document.querySelectorAll('.start-btn');
    const ansBtns = document.querySelectorAll('.ans-btn');

    fetch('{{ route("games.api.data") }}')
        .then(res => res.json())
        .then(data => {
            let words = data.words || [];
            questions = words.map(w => ({
                question: `True or False: "${w.word}" is defined as "${w.hint}".`,
                answer: 'True'
            }));

            // Add false statements
            words.forEach((w, i) => {
                let nextHint = words[(i + 1) % words.length].hint;
                questions.push({
                    question: `True or False: "${w.word}" is defined as "${nextHint}".`,
                    answer: 'False'
                });
            });

            questions.sort(() => Math.random() - 0.5);
        });

    startBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            timerSeconds = parseInt(this.getAttribute('data-time'));
            modeCard.classList.add('d-none');
            arenaCard.classList.remove('d-none');
            startSprint();
        });
    });

    function startSprint() {
        timerDisplay.textContent = `${timerSeconds}s`;
        nextQuestion();

        timerInterval = setInterval(() => {
            timerSeconds--;
            timerDisplay.textContent = `${timerSeconds}s`;

            if (timerSeconds <= 0) {
                clearInterval(timerInterval);
                let accuracy = totalAsked > 0 ? round((correctCount / totalAsked) * 100, 1) : 100;
                alert(`⚡ Rapid Fire Complete!\nScore: ${score}\nAccuracy: ${accuracy}%\nCorrect: ${correctCount}/${totalAsked}`);

                fetch('{{ route("games.api.record-score") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ game_type: 'rapidfire', score: score, accuracy: accuracy })
                }).then(() => window.location.href = '{{ route("games.index") }}');
            }
        }, 1000);
    }

    function nextQuestion() {
        if (questions.length === 0) return;
        currentQ = questions[Math.floor(Math.random() * questions.length)];
        rapidQuestion.textContent = currentQ.question;
    }

    ansBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            let choice = this.getAttribute('data-ans');
            totalAsked++;

            if (choice === currentQ.answer) {
                combo++;
                correctCount++;
                score += (10 * combo);
                comboDisplay.textContent = `🔥 ${combo}x Combo`;
            } else {
                combo = 0;
                comboDisplay.textContent = `🔥 0x Combo`;
            }

            nextQuestion();
        });
    });
});
</script>
@endpush
