@extends('layouts.dashboard')

@section('title', 'AI Hangman - Study Games Arcade')

@push('styles')
<style>
.word-mask {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: 10px;
}
.keyboard-btn {
    width: 42px;
    height: 42px;
    font-weight: bold;
    margin: 3px;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Hangman 🧍</h2>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 fs-6 rounded-pill" id="livesDisplay">❤️ Lives: 6</span>
            <span class="badge bg-warning-subtle text-dark border px-3 py-2 fs-6 rounded-pill" id="scoreDisplay">Score: 0</span>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border text-center">
        <p class="text-muted fs-7 mb-3"><i class="bi bi-lightbulb text-warning me-1"></i> <strong>AI Hint:</strong> <span id="hangmanHint">Loading hint...</span></p>

        <div class="word-mask font-monospace my-4 text-primary" id="wordMask">_ _ _ _ _</div>

        <div id="keyboardContainer" class="d-flex flex-wrap justify-content-center max-w-lg mx-auto my-3">
            <!-- Letters A-Z dynamically rendered -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let wordList = [];
    let currentIndex = 0;
    let currentWord = '';
    let guessedLetters = new Set();
    let lives = 6;
    let score = 0;

    const livesDisplay = document.getElementById('livesDisplay');
    const scoreDisplay = document.getElementById('scoreDisplay');
    const hangmanHint = document.getElementById('hangmanHint');
    const wordMask = document.getElementById('wordMask');
    const keyboardContainer = document.getElementById('keyboardContainer');

    function renderKeyboard() {
        keyboardContainer.innerHTML = '';
        for (let i = 65; i <= 90; i++) {
            let char = String.fromCharCode(i);
            let btn = document.createElement('button');
            btn.className = 'btn btn-outline-primary keyboard-btn';
            btn.textContent = char;
            btn.disabled = guessedLetters.has(char);
            btn.addEventListener('click', () => makeGuess(char));
            keyboardContainer.appendChild(btn);
        }
    }

    fetch('{{ route("games.api.data") }}')
        .then(res => res.json())
        .then(data => {
            wordList = data.words || [];
            if (wordList.length > 0) {
                loadRound();
            }
        });

    function loadRound() {
        if (currentIndex >= wordList.length || lives <= 0) {
            alert(`🎉 Game Finished! Final Score: ${score}`);
            fetch('{{ route("games.api.record-score") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ game_type: 'hangman', score: score })
            }).then(() => window.location.href = '{{ route("games.index") }}');
            return;
        }

        currentWord = wordList[currentIndex].word;
        hangmanHint.textContent = wordList[currentIndex].hint;
        guessedLetters.clear();
        renderWordMask();
        renderKeyboard();
    }

    function renderWordMask() {
        let display = '';
        let win = true;
        for (let char of currentWord) {
            if (guessedLetters.has(char)) {
                display += char + ' ';
            } else {
                display += '_ ';
                win = false;
            }
        }
        wordMask.textContent = display.trim();

        if (win && currentWord.length > 0) {
            score += 15;
            scoreDisplay.textContent = `Score: ${score}`;
            alert('🎉 You solved the word!');
            currentIndex++;
            loadRound();
        }
    }

    function makeGuess(char) {
        if (guessedLetters.has(char)) return;
        guessedLetters.add(char);

        if (!currentWord.includes(char)) {
            lives--;
            livesDisplay.textContent = `❤️ Lives: ${lives}`;
            if (lives <= 0) {
                alert(`❌ Out of lives! The word was: ${currentWord}`);
                loadRound();
                return;
            }
        }

        renderWordMask();
        renderKeyboard();
    }
});
</script>
@endpush
