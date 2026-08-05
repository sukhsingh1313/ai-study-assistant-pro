@extends('layouts.dashboard')

@section('title', 'AI Daily Quest - Study Games Arcade')

@section('content')
<div class="container-fluid px-0" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Daily Quest & Challenges 📅</h2>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border mb-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-calendar-check-fill fs-2"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">Today's Daily Quest: {{ date('F j, Y') }}</h4>
                <p class="text-muted fs-7 mb-0">Complete all 3 study objectives to claim 100 Bonus Coins and unlock the "Daily Scholar" badge!</p>
            </div>
        </div>

        <div class="d-flex flex-column gap-3 mb-4">
            <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Objective 1: Unscramble 3 Study Words</h6>
                    <small class="text-muted">Play AI Word Scramble mode</small>
                </div>
                <a href="{{ route('games.scramble') }}" class="btn btn-sm btn-outline-primary">Complete</a>
            </div>

            <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Objective 2: Play 1 Memory Match Game</h6>
                    <small class="text-muted">Pair terms with correct definitions</small>
                </div>
                <a href="{{ route('games.memory') }}" class="btn btn-sm btn-outline-primary">Complete</a>
            </div>

            <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i>Objective 3: Earn 50 XP Points</h6>
                    <small class="text-muted">Play any study game or focus room session</small>
                </div>
                <a href="{{ route('games.index') }}" class="btn btn-sm btn-outline-primary">Complete</a>
            </div>
        </div>

        <button type="button" id="claimRewardBtn" class="btn btn-success btn-lg w-100 py-3 fw-bold rounded-pill shadow-sm">
            <i class="bi bi-gift-fill me-1"></i> Claim 100 Bonus Coins & Daily XP!
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const claimRewardBtn = document.getElementById('claimRewardBtn');

    claimRewardBtn.addEventListener('click', function() {
        fetch('{{ route("games.api.record-score") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ game_type: 'daily', score: 100 })
        })
        .then(res => res.json())
        .then(data => {
            alert('🎉 Congratulations! Claimed +100 Bonus Coins and +150 XP for completing Daily Quests!');
            window.location.href = '{{ route("games.index") }}';
        });
    });
});
</script>
@endpush
