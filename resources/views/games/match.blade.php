@extends('layouts.dashboard')

@section('title', 'AI Match the Following - Study Games Arcade')

@push('styles')
<style>
.match-item {
    padding: 15px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background-color: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
}
.match-item.selected {
    border-color: #0d6efd;
    background-color: #eff6ff;
}
.match-item.matched {
    border-color: #198754;
    background-color: #d1e7dd;
    color: #0f5132;
    cursor: default;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Match the Following 🔄</h2>
        </div>
        <div>
            <span class="badge bg-warning-subtle text-dark border px-3 py-2 fs-6 rounded-pill" id="scoreDisplay">Score: 0</span>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border">
        <p class="text-muted fs-7 mb-4 text-center">Click a <strong>Term</strong> on the left, then click its corresponding <strong>Definition</strong> on the right to match!</p>

        <div class="row g-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-tag me-1"></i> Terms</h6>
                <div class="d-flex flex-column gap-3" id="termsList">
                    <!-- Terms rendered dynamically -->
                </div>
            </div>

            <div class="col-md-6">
                <h6 class="fw-bold text-success mb-3"><i class="bi bi-card-text me-1"></i> Definitions</h6>
                <div class="d-flex flex-column gap-3" id="defsList">
                    <!-- Definitions rendered dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let pairs = [];
    let selectedTerm = null;
    let selectedDef = null;
    let matchedCount = 0;
    let score = 0;

    const termsList = document.getElementById('termsList');
    const defsList = document.getElementById('defsList');
    const scoreDisplay = document.getElementById('scoreDisplay');

    fetch('{{ route("games.api.data") }}')
        .then(res => res.json())
        .then(data => {
            pairs = (data.pairs || []).slice(0, 4);
            renderBoard();
        });

    function renderBoard() {
        termsList.innerHTML = '';
        defsList.innerHTML = '';

        let termsArr = pairs.map((p, idx) => ({ id: idx, text: p.term }));
        let defsArr = pairs.map((p, idx) => ({ id: idx, text: p.definition }));

        // Shuffle right column
        defsArr.sort(() => Math.random() - 0.5);

        termsArr.forEach(t => {
            let div = document.createElement('div');
            div.className = 'match-item fw-bold text-dark';
            div.setAttribute('data-id', t.id);
            div.textContent = t.text;
            div.addEventListener('click', () => selectTerm(div, t.id));
            termsList.appendChild(div);
        });

        defsArr.forEach(d => {
            let div = document.createElement('div');
            div.className = 'match-item text-muted fs-7';
            div.setAttribute('data-id', d.id);
            div.textContent = d.text;
            div.addEventListener('click', () => selectDef(div, d.id));
            defsList.appendChild(div);
        });
    }

    function selectTerm(elem, id) {
        if (elem.classList.contains('matched')) return;
        document.querySelectorAll('#termsList .match-item').forEach(e => e.classList.remove('selected'));
        elem.classList.add('selected');
        selectedTerm = { elem: elem, id: id };
        checkPair();
    }

    function selectDef(elem, id) {
        if (elem.classList.contains('matched')) return;
        document.querySelectorAll('#defsList .match-item').forEach(e => e.classList.remove('selected'));
        elem.classList.add('selected');
        selectedDef = { elem: elem, id: id };
        checkPair();
    }

    function checkPair() {
        if (selectedTerm && selectedDef) {
            if (selectedTerm.id === selectedDef.id) {
                selectedTerm.elem.classList.remove('selected');
                selectedDef.elem.classList.remove('selected');
                selectedTerm.elem.classList.add('matched');
                selectedDef.elem.classList.add('matched');

                matchedCount++;
                score += 25;
                scoreDisplay.textContent = `Score: ${score}`;
                selectedTerm = null;
                selectedDef = null;

                if (matchedCount === pairs.length) {
                    setTimeout(() => {
                        alert(`🎉 Great job! All ${pairs.length} pairs matched! Score: ${score}`);
                        fetch('{{ route("games.api.record-score") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ game_type: 'match', score: score })
                        }).then(() => window.location.href = '{{ route("games.index") }}');
                    }, 500);
                }
            } else {
                setTimeout(() => {
                    if (selectedTerm) selectedTerm.elem.classList.remove('selected');
                    if (selectedDef) selectedDef.elem.classList.remove('selected');
                    selectedTerm = null;
                    selectedDef = null;
                }, 600);
            }
        }
    }
});
</script>
@endpush
