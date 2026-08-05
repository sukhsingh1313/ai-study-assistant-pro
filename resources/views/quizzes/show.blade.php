@extends('layouts.dashboard')

@section('title', 'Quiz Results - ' . $quiz->title)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge text-uppercase {{ $quiz->difficulty == 'easy' ? 'bg-success-subtle text-success border border-success-subtle' : ($quiz->difficulty == 'medium' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }}">
                    {{ $quiz->difficulty }}
                </span>
                <span class="badge bg-light text-muted border"><i class="bi bi-clock me-1"></i>{{ $quiz->timer_minutes }} min limit</span>
            </div>
            <h2 class="fw-bold text-dark mb-0">{{ $quiz->title }}</h2>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('quizzes.create') }}" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg me-1"></i> Create Another Quiz
            </a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to History
            </a>
        </div>
    </div>

    <!-- Score Banner -->
    <div class="card card-custom p-4 p-md-5 {{ ($quiz->score >= 70) ? 'bg-success-subtle border-success-subtle' : 'bg-danger-subtle border-danger-subtle' }} mb-4 border text-center">
        <span class="text-uppercase fw-bold tracking-wide {{ ($quiz->score >= 70) ? 'text-success' : 'text-danger' }}">Assessment Assessment Result</span>
        <h1 class="display-3 fw-bold my-2 {{ ($quiz->score >= 70) ? 'text-success' : 'text-danger' }}">
            {{ number_format($quiz->score ?? 0, 1) }}%
        </h1>
        <p class="mb-0 fw-medium fs-6 text-dark">
            @if($quiz->score >= 70)
                🎉 Excellent work! You passed this quiz assessment.
            @else
                💪 Keep practicing! Review the explanations below to improve your score.
            @endif
        </p>
    </div>

    <!-- Question-by-Question Breakdown -->
    <h4 class="fw-bold text-dark mb-3">Detailed Answer Breakdown</h4>

    <div class="d-flex flex-column gap-4 mb-4">
        @foreach($quiz->questions as $index => $q)
            <div class="card card-custom p-4 bg-white border {{ $q->is_correct ? 'border-start border-success border-4' : 'border-start border-danger border-4' }}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-muted fs-7">Question {{ $index + 1 }}</span>
                    @if($q->is_correct)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            <i class="bi bi-check-circle-fill me-1"></i> Correct (+1)
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                            <i class="bi bi-x-circle-fill me-1"></i> Incorrect (0)
                        </span>
                    @endif
                </div>

                <h5 class="fw-bold text-dark mb-3">{{ $q->question }}</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded {{ $q->is_correct ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}">
                            <small class="fw-bold text-uppercase d-block mb-1 fs-8">Your Answer:</small>
                            <span class="fw-bold fs-6">{{ $q->user_answer ?? 'No answer provided (Timed out)' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light border border-secondary-subtle">
                            <small class="fw-bold text-uppercase text-muted d-block mb-1 fs-8">Correct Answer:</small>
                            <span class="fw-bold text-dark fs-6">{{ $q->correct_answer }}</span>
                        </div>
                    </div>
                </div>

                @if($q->explanation)
                    <div class="bg-light p-3 rounded border text-muted fs-7">
                        <strong class="text-dark d-block mb-1"><i class="bi bi-info-circle text-primary me-1"></i> AI Explanation:</strong>
                        {{ $q->explanation }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
