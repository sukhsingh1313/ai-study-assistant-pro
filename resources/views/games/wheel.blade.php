@extends('layouts.dashboard')

@section('title', 'AI Wheel of Fortune - Study Games Arcade')

@push('styles')
<style>
.wheel-container {
    position: relative;
    width: 320px;
    height: 320px;
    margin: 0 auto;
}
#wheelCanvas {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 8px solid #0d6efd;
    transition: transform 4s cubic-bezier(0.15, 0.99, 0.18, 1);
}
.wheel-pointer {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-top: 25px solid #dc3545;
    z-index: 10;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('games.index') }}" class="btn btn-sm btn-light border mb-2"><i class="bi bi-arrow-left me-1"></i> Back to Arcade</a>
            <h2 class="fw-bold text-dark mb-0">AI Wheel of Fortune 🎡</h2>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border text-center">
        <p class="text-muted fs-7 mb-4">Spin the Wheel every day to win bonus study Coins, XP multipliers, and rapid challenges!</p>

        <div class="wheel-container mb-4">
            <div class="wheel-pointer"></div>
            <canvas id="wheelCanvas" width="320" height="320"></canvas>
        </div>

        <button type="button" id="spinBtn" class="btn btn-primary-custom btn-lg px-5 rounded-pill shadow-sm">
            <i class="bi bi-disc me-1"></i> SPIN THE WHEEL!
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const spinBtn = document.getElementById('spinBtn');

    const segments = ['+50 Coins 🪙', '+100 XP ⭐', 'Free Spin 🎡', 'Quiz Quest ❓', '+25 Coins 🪙', '+50 XP ⭐'];
    const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1'];
    const numSegments = segments.length;
    const anglePerSeg = (2 * Math.PI) / numSegments;
    let currentRotation = 0;
    let isSpinning = false;

    function drawWheel() {
        ctx.clearRect(0, 0, 320, 320);
        for (let i = 0; i < numSegments; i++) {
            ctx.beginPath();
            ctx.fillStyle = colors[i];
            ctx.moveTo(160, 160);
            ctx.arc(160, 160, 150, i * anglePerSeg, (i + 1) * anglePerSeg);
            ctx.fill();

            // Text
            ctx.save();
            ctx.translate(160, 160);
            ctx.rotate(i * anglePerSeg + anglePerSeg / 2);
            ctx.textAlign = "right";
            ctx.fillStyle = "#ffffff";
            ctx.font = "bold 13px Inter, sans-serif";
            ctx.fillText(segments[i], 130, 5);
            ctx.restore();
        }
    }

    drawWheel();

    spinBtn.addEventListener('click', function() {
        if (isSpinning) return;
        isSpinning = true;
        spinBtn.disabled = true;

        const randomDegrees = Math.floor(1440 + Math.random() * 1800);
        currentRotation += randomDegrees;

        canvas.style.transform = `rotate(${currentRotation}deg)`;

        setTimeout(() => {
            isSpinning = false;
            spinBtn.disabled = false;

            const actualDegrees = currentRotation % 360;
            const winningIndex = Math.floor((360 - actualDegrees % 360) / (360 / numSegments)) % numSegments;
            const prize = segments[winningIndex];

            alert(`🎉 Congratulations! You won: ${prize}!`);

            fetch('{{ route("games.api.record-score") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ game_type: 'wheel', score: 50 })
            });
        }, 4000);
    });
});
</script>
@endpush
