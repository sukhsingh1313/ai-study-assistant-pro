@extends('layouts.dashboard')

@section('title', 'AI Memory Match - Study Games Arcade')

@push('styles')
<style>
.memory-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}
@media (max-width: 768px) {
    .memory-grid { grid-template-columns: repeat(2, 1fr); }
}
.memory-card {
    height: 110px;
    perspective: 1000px;
    cursor: pointer;
}
.memory-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.5s;
    transform-style: preserve-3d;
}
.memory-card.flipped .memory-card-inner {
    transform: rotateY(180deg);
}
.memory-card-front, .memory-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    font-size: 0.85rem;
    font-weight: 600;
}
.memory-card-front {
    background-color: #0d6efd;
    color: white;
    font-size: 1.5rem;
}
.memory-card-back {
    background-color: #ffffff;
    color: #0f172a;
    border: 2px solid #0d6efd;
    transform: rotateY(180deg);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Memory Match 🧠</h2>
        </div>
        <div>
            <span class="badge bg-primary-subtle text-primary border px-3 py-2 fs-6 rounded-pill" id="matchesDisplay">Matches: 0 / 4</span>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 border">
        <div class="memory-grid" id="memoryGrid">
            <!-- Dynamic 8 Cards (4 pairs) -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cardsData = [];
    let flippedCards = [];
    let matchedCount = 0;
    let score = 0;
    const memoryGrid = document.getElementById('memoryGrid');
    const matchesDisplay = document.getElementById('matchesDisplay');

    fetch('{{ route("games.api.data") }}')
        .then(res => res.json())
        .then(data => {
            let pairs = (data.pairs || []).slice(0, 4);
            let deck = [];

            pairs.forEach((p, idx) => {
                deck.push({ id: idx, type: 'term', text: p.term });
                deck.push({ id: idx, type: 'def', text: p.definition });
            });

            // Shuffle deck
            deck.sort(() => Math.random() - 0.5);
            cardsData = deck;
            renderBoard();
        });

    function renderBoard() {
        memoryGrid.innerHTML = '';
        cardsData.forEach((card, index) => {
            let cardElem = document.createElement('div');
            cardElem.className = 'memory-card';
            cardElem.setAttribute('data-index', index);
            cardElem.innerHTML = `
                <div class="memory-card-inner">
                    <div class="memory-card-front"><i class="bi bi-question-circle"></i></div>
                    <div class="memory-card-back">${card.text}</div>
                </div>
            `;
            cardElem.addEventListener('click', () => onCardClick(cardElem, card));
            memoryGrid.appendChild(cardElem);
        });
    }

    function onCardClick(elem, card) {
        if (flippedCards.length >= 2 || elem.classList.contains('flipped')) return;

        elem.classList.add('flipped');
        flippedCards.push({ elem: elem, card: card });

        if (flippedCards.length === 2) {
            let card1 = flippedCards[0];
            let card2 = flippedCards[1];

            if (card1.card.id === card2.card.id) {
                matchedCount++;
                score += 25;
                matchesDisplay.textContent = `Matches: ${matchedCount} / 4`;
                flippedCards = [];

                if (matchedCount === 4) {
                    setTimeout(() => {
                        alert(`🎉 Congratulations! Memory Match completed! Earned ${score} points!`);
                        fetch('{{ route("games.api.record-score") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ game_type: 'memory', score: score })
                        }).then(() => window.location.href = '{{ route("games.index") }}');
                    }, 500);
                }
            } else {
                setTimeout(() => {
                    card1.elem.classList.remove('flipped');
                    card2.elem.classList.remove('flipped');
                    flippedCards = [];
                }, 1000);
            }
        }
    }
});
</script>
@endpush
