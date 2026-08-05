@extends('layouts.dashboard')

@section('title', 'Take Quiz - ' . $quiz->title)

@section('content')
<div class="container-fluid px-0">
    <!-- Sticky Timer Header -->
    <div class="card card-custom p-3 bg-white border sticky-top shadow-sm mb-4" style="top: 70px; z-index: 1020;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase me-2">{{ $quiz->difficulty }}</span>
                <h5 class="fw-bold text-dark d-inline mb-0">{{ $quiz->title }}</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold border border-danger-subtle d-flex align-items-center gap-2" id="timerBadge">
                    <i class="bi bi-clock-history fs-5"></i>
                    <span id="timerDisplay" class="font-monospace fs-5">00:00</span>
                </div>
                <button type="button" onclick="document.getElementById('quizTakeForm').submit();" class="btn btn-primary-custom px-4">
                    Submit Quiz <i class="bi bi-send ms-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Quiz Form -->
    <form method="POST" action="{{ route('quizzes.submit', $quiz) }}" id="quizTakeForm">
        @csrf

        <div class="d-flex flex-column gap-4 mb-4">
            @foreach($quiz->questions as $index => $q)
                <div class="card card-custom p-4 bg-white border" id="question-card-{{ $q->id }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 fs-7">
                            Question {{ $index + 1 }} of {{ $quiz->questions->count() }}
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-4 lh-base">
                        {{ $q->question }}
                    </h5>

                    <div class="row g-3">
                        @foreach($q->options as $optIndex => $option)
                            <div class="col-md-6">
                                <div class="form-check custom-option-card p-3 border rounded bg-light hover-primary">
                                    <input class="form-check-input ms-1 me-2" type="radio" name="answers[{{ $q->id }}]" id="q_{{ $q->id }}_opt_{{ $optIndex }}" value="{{ $option }}">
                                    <label class="form-check-label w-100 fw-medium text-dark cursor-pointer" for="q_{{ $q->id }}_opt_{{ $optIndex }}">
                                        {{ $option }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center py-4">
            <button type="submit" class="btn btn-primary-custom btn-lg px-5 py-3 shadow">
                <i class="bi bi-check-circle-fill me-2"></i> Submit & Grade Assessment
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Total timer in seconds
    let totalSeconds = {{ ($quiz->timer_minutes ?? 10) * 60 }};
    const timerDisplay = document.getElementById('timerDisplay');
    const timerBadge = document.getElementById('timerBadge');
    const form = document.getElementById('quizTakeForm');

    function updateTimer() {
        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            timerDisplay.textContent = "00:00";
            alert("⏰ Time is up! Your quiz responses will now be automatically submitted for grading.");
            form.submit();
            return;
        }

        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;

        const formattedMins = String(minutes).padStart(2, '0');
        const formattedSecs = String(seconds).padStart(2, '0');

        timerDisplay.textContent = `${formattedMins}:${formattedSecs}`;

        // Flash badge red when under 1 minute remaining
        if (totalSeconds < 60) {
            timerBadge.classList.remove('bg-danger-subtle', 'text-danger');
            timerBadge.classList.add('bg-danger', 'text-white');
        }

        totalSeconds--;
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
});
</script>
@endpush
