@extends('layouts.dashboard')

@section('title', 'AI Fill in the Blanks - Study Games Arcade')

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Fill in the Blanks ✍️</h2>
        </div>
        <div>
            <span class="badge bg-warning-subtle text-dark border px-3 py-2 fs-6 rounded-pill" id="scoreDisplay">Score: 0</span>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border text-center">
        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fs-8 mb-3" id="blankCounter">Question 1 of 5</span>

        <h4 class="fw-bold text-dark lh-base my-4" id="sentenceBox">Loading sentence with missing blank...</h4>

        <form id="blankForm" class="mb-3">
            <div class="input-group input-group-lg max-w-md mx-auto" style="max-width: 450px;">
                <input type="text" id="blankInput" class="form-control text-center font-monospace" placeholder="Type missing word..." required autofocus autocomplete="off">
                <button type="submit" class="btn btn-primary-custom px-4">Submit</button>
            </div>
        </form>

        <p class="text-muted fs-8 mb-0" id="feedbackText"></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let items = [];
    let currentIndex = 0;
    let score = 0;

    const sentenceBox = document.getElementById('sentenceBox');
    const blankForm = document.getElementById('blankForm');
    const blankInput = document.getElementById('blankInput');
    const blankCounter = document.getElementById('blankCounter');
    const scoreDisplay = document.getElementById('scoreDisplay');
    const feedbackText = document.getElementById('feedbackText');

    fetch('{{ route("games.api.data") }}')
        .then(res => res.json())
        .then(data => {
            items = data.words || [];
            if (items.length > 0) {
                loadQuestion();
            }
        });

    function loadQuestion() {
        if (currentIndex >= items.length) {
            alert(`🎉 Challenge Finished! Final Score: ${score}`);
            fetch('{{ route("games.api.record-score") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ game_type: 'fillblanks', score: score })
            }).then(() => window.location.href = '{{ route("games.index") }}');
            return;
        }

        let curr = items[currentIndex];
        let displaySentence = curr.hint.replace(new RegExp(curr.word, 'gi'), '_______');
        if (!displaySentence.includes('_______')) {
            displaySentence = `The study concept __________ is defined as: "${curr.hint}".`;
        }

        sentenceBox.textContent = displaySentence;
        blankCounter.textContent = `Question ${currentIndex + 1} of ${items.length}`;
        blankInput.value = '';
        feedbackText.textContent = '';
        blankInput.focus();
    }

    blankForm.addEventListener('submit', function(e) {
        e.preventDefault();
        let userAns = blankInput.value.trim().toUpperCase();
        let correctAns = items[currentIndex].word.toUpperCase();

        if (userAns === correctAns) {
            score += 15;
            scoreDisplay.textContent = `Score: ${score}`;
            feedbackText.className = 'text-success fw-bold fs-7 mt-2';
            feedbackText.textContent = '✅ Correct Answer!';
            setTimeout(() => {
                currentIndex++;
                loadQuestion();
            }, 1000);
        } else {
            feedbackText.className = 'text-danger fw-bold fs-7 mt-2';
            feedbackText.textContent = `❌ Incorrect! Correct word was: ${correctAns}`;
            setTimeout(() => {
                currentIndex++;
                loadQuestion();
            }, 2000);
        }
    });
});
</script>
@endpush
