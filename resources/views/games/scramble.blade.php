@extends('layouts.dashboard')

@section('title', 'AI Word Scramble - Study Games Arcade')

@push('styles')
<style>
.scramble-box {
    font-size: 3rem;
    font-weight: 800;
    letter-spacing: 12px;
    color: #0d6efd;
    text-transform: uppercase;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Word Scramble 🔤</h2>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-warning-subtle text-dark border px-3 py-2 fs-6 rounded-pill" id="scoreDisplay">Score: 0</span>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border text-center">
        <div class="mb-3">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fs-8" id="roundCounter">Round 1</span>
        </div>

        <div class="scramble-box font-monospace my-4" id="scrambledWord">LOADING...</div>

        <p class="text-muted fs-7 mb-4 d-none" id="hintBox"><i class="bi bi-lightbulb text-warning me-1"></i> <strong>Hint:</strong> <span id="hintText"></span></p>

        <form id="guessForm" class="mb-4">
            <div class="input-group input-group-lg max-w-md mx-auto" style="max-width: 450px;">
                <input type="text" id="guessInput" class="form-control text-center font-monospace text-uppercase" placeholder="Type unscrambled word..." required autofocus autocomplete="off">
                <button type="submit" class="btn btn-primary-custom px-4">Submit</button>
            </div>
        </form>

        <div class="d-flex justify-content-center gap-2">
            <button type="button" id="hintBtn" class="btn btn-outline-warning btn-sm"><i class="bi bi-lightbulb me-1"></i> Show Hint</button>
            <button type="button" id="skipBtn" class="btn btn-light border btn-sm text-secondary"><i class="bi bi-skip-forward me-1"></i> Skip Word</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let wordList = [];
    let currentIndex = 0;
    let currentWordObj = null;
    let score = 0;

    const scrambledWord = document.getElementById('scrambledWord');
    const hintBox = document.getElementById('hintBox');
    const hintText = document.getElementById('hintText');
    const guessForm = document.getElementById('guessForm');
    const guessInput = document.getElementById('guessInput');
    const scoreDisplay = document.getElementById('scoreDisplay');
    const roundCounter = document.getElementById('roundCounter');
    const hintBtn = document.getElementById('hintBtn');
    const skipBtn = document.getElementById('skipBtn');

    function scrambleString(str) {
        let arr = str.split('');
        for (let i = arr.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr.join('');
    }

    fetch('{{ route("games.api.data") }}')
        .then(res => res.json())
        .then(data => {
            wordList = data.words || [];
            if (wordList.length > 0) {
                loadWord();
            }
        });

    function loadWord() {
        if (currentIndex >= wordList.length) {
            alert(`🎉 Game Over! Final Score: ${score}`);
            fetch('{{ route("games.api.record-score") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ game_type: 'scramble', score: score })
            }).then(() => window.location.href = '{{ route("games.index") }}');
            return;
        }

        currentWordObj = wordList[currentIndex];
        let original = currentWordObj.word;
        let scrambled = scrambleString(original);
        while (scrambled === original && original.length > 1) {
            scrambled = scrambleString(original);
        }

        scrambledWord.textContent = scrambled;
        hintText.textContent = currentWordObj.hint;
        hintBox.classList.add('d-none');
        guessInput.value = '';
        guessInput.focus();
        roundCounter.textContent = `Round ${currentIndex + 1} of ${wordList.length}`;
    }

    guessForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let userGuess = guessInput.value.trim().toUpperCase();
        if (userGuess === currentWordObj.word) {
            score += 10;
            scoreDisplay.textContent = `Score: ${score}`;
            alert('✅ Correct!');
            currentIndex++;
            loadWord();
        } else {
            alert('❌ Incorrect! Try again or use hint.');
        }
    });

    hintBtn.addEventListener('click', function() {
        hintBox.classList.remove('d-none');
    });

    skipBtn.addEventListener('click', function() {
        currentIndex++;
        loadWord();
    });
});
</script>
@endpush
