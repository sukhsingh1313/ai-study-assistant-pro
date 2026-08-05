@extends('layouts.dashboard')

@section('title', $summary->title . ' - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill mb-2">
                {{ $summary->ai_model_used }}
            </span>
            <h2 class="fw-bold text-dark mb-1">{{ $summary->title }}</h2>
            <p class="text-muted mb-0">Generated {{ $summary->created_at->format('M d, Y') }} • ~{{ $summary->reading_time_minutes }} Min Read</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <form method="POST" action="{{ route('summaries.retry', $summary) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary-custom btn-sm">
                    <i class="bi bi-arrow-clockwise me-1"></i> Generate Again
                </button>
            </form>

            <button type="button" id="copySummaryBtn" class="btn btn-light border btn-sm text-secondary">
                <i class="bi bi-clipboard me-1"></i> Copy Text
            </button>
            <button type="button" onclick="window.print()" class="btn btn-light border btn-sm text-secondary">
                <i class="bi bi-printer me-1"></i> Print PDF
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Executive Summary Card -->
    <div class="card card-custom bg-white p-4 p-md-5 mb-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-text text-primary me-2"></i>Executive Summary Overview</h5>
        <p class="lh-lg text-dark fs-6 mb-0" id="summaryText">{{ $summary->executive_summary }}</p>
    </div>

    <!-- Key Takeaway Points Card -->
    <div class="card card-custom bg-white p-4 p-md-5 mb-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-check text-success me-2"></i>Key Concept Takeaway Points</h5>
        <ul class="list-group list-group-flush border-0">
            @foreach($summary->key_points as $point)
                <li class="list-group-item bg-transparent border-0 px-0 py-2 d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-primary mt-1"></i>
                    <span class="fs-6 text-dark">{{ $point }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const copySummaryBtn = document.getElementById('copySummaryBtn');
    const summaryText = document.getElementById('summaryText');

    copySummaryBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(summaryText.textContent);
        this.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
        setTimeout(() => this.innerHTML = '<i class="bi bi-clipboard me-1"></i> Copy Text', 2000);
    });
});
</script>
@endpush
