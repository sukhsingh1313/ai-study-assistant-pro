@extends('layouts.dashboard')

@section('title', 'Flashcard Review Mode - AI Study Assistant')

@push('styles')
<style>
.flashcard-perspective {
    perspective: 1200px;
}
.flashcard-inner {
    width: 100%;
    min-height: 360px;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.flashcard-inner.flipped {
    transform: rotateY(180deg);
}
.flashcard-front, .flashcard-back {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 2.5rem;
}
.flashcard-front {
    background-color: #ffffff;
    border: 2px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(13, 110, 253, 0.08);
}
.flashcard-back {
    background: linear-gradient(135deg, #0d6efd 0%, #0a48b3 100%);
    color: #ffffff;
    transform: rotateY(180deg);
    box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <!-- Review Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="badge bg-primary text-white px-3 py-2 fs-7 rounded-pill" id="cardProgress">
                Card 1 of {{ count($cards) }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" id="favoriteToggleBtn" class="btn btn-outline-warning btn-sm px-3 rounded-pill">
                <i class="bi bi-star me-1" id="favoriteIcon"></i> Favorite
            </button>
            <a href="{{ route('flashcards.index') }}" class="btn btn-light border btn-sm px-3 rounded-pill">
                <i class="bi bi-x-lg me-1"></i> Exit Review
            </a>
        </div>
    </div>

    <!-- 3D Flashcard Container -->
    <div class="flashcard-perspective mb-4">
        <div class="flashcard-inner" id="flashcardInner">
            <!-- Front of Card (Question) -->
            <div class="flashcard-front">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-bold text-primary fs-8 tracking-wider"><i class="bi bi-question-circle me-1"></i> Question / Concept</span>
                    <small class="text-muted fs-8"><i class="bi bi-arrow-repeat me-1"></i>Click card or press Space to Flip</small>
                </div>
                <div class="my-auto text-center py-4">
                    <h3 class="fw-bold text-dark lh-base" id="cardQuestionText">
                        Loading question...
                    </h3>
                </div>
                <div class="text-center text-muted fs-8">
                    Tap to reveal answer
                </div>
            </div>

            <!-- Back of Card (Answer) -->
            <div class="flashcard-back">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-uppercase fw-bold text-light fs-8 tracking-wider opacity-75"><i class="bi bi-check-circle me-1"></i> Answer / Definition</span>
                    <small class="text-light opacity-75 fs-8">Click card to Flip back</small>
                </div>
                <div class="my-auto text-center py-4">
                    <h4 class="fw-semibold lh-base text-white" id="cardAnswerText">
                        Loading answer...
                    </h4>
                </div>
                <div class="text-center text-white opacity-75 fs-8">
                    Spaced repetition memory check
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Navigation Control Bar -->
    <div class="card card-custom p-3 bg-white border">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <button type="button" id="prevBtn" class="btn btn-outline-primary px-4 py-2">
                <i class="bi bi-chevron-left me-1"></i> Previous
            </button>

            <button type="button" id="flipBtn" class="btn btn-primary-custom px-4 py-2">
                <i class="bi bi-arrow-repeat me-1"></i> Flip Card
            </button>

            <button type="button" id="randomBtn" class="btn btn-outline-info px-3 py-2" title="Random Shuffle">
                <i class="bi bi-shuffle"></i> Random
            </button>

            <button type="button" id="nextBtn" class="btn btn-outline-primary px-4 py-2">
                Next <i class="bi bi-chevron-right ms-1"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = @json($cards);
    let currentIndex = 0;

    const flashcardInner = document.getElementById('flashcardInner');
    const questionText = document.getElementById('cardQuestionText');
    const answerText = document.getElementById('cardAnswerText');
    const cardProgress = document.getElementById('cardProgress');
    const favoriteIcon = document.getElementById('favoriteIcon');
    const favoriteToggleBtn = document.getElementById('favoriteToggleBtn');

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const flipBtn = document.getElementById('flipBtn');
    const randomBtn = document.getElementById('randomBtn');

    function renderCard(index) {
        if (cards.length === 0) return;

        // Unflip card when navigating
        flashcardInner.classList.remove('flipped');

        setTimeout(() => {
            const card = cards[index];
            questionText.textContent = card.question;
            answerText.textContent = card.answer;
            cardProgress.textContent = `Card ${index + 1} of ${cards.length}`;

            if (card.is_favorite) {
                favoriteIcon.className = 'bi bi-star-fill text-warning me-1';
            } else {
                favoriteIcon.className = 'bi bi-star me-1';
            }

            // Record review in background
            recordReview(card.id);
        }, 150);
    }

    function toggleFlip() {
        flashcardInner.classList.toggle('flipped');
    }

    flashcardInner.addEventListener('click', toggleFlip);
    flipBtn.addEventListener('click', toggleFlip);

    prevBtn.addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + cards.length) % cards.length;
        renderCard(currentIndex);
    });

    nextBtn.addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % cards.length;
        renderCard(currentIndex);
    });

    randomBtn.addEventListener('click', function() {
        currentIndex = Math.floor(Math.random() * cards.length);
        renderCard(currentIndex);
    });

    // Keyboard Shortcuts Navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            prevBtn.click();
        } else if (e.key === 'ArrowRight') {
            nextBtn.click();
        } else if (e.key === ' ' || e.key === 'Spacebar' || e.key === 'ArrowUp' || e.key === 'ArrowDown') {
            e.preventDefault();
            toggleFlip();
        }
    });

    // Favorite AJAX Toggle
    favoriteToggleBtn.addEventListener('click', function() {
        const card = cards[currentIndex];
        fetch(`/flashcards/${card.id}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                card.is_favorite = data.is_favorite;
                renderCard(currentIndex);
            }
        });
    });

    function recordReview(cardId) {
        fetch(`/flashcards/${cardId}/record-review`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
    }

    // Initial render
    renderCard(currentIndex);
});
</script>
@endpush
