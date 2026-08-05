@extends('layouts.dashboard')

@section('title', 'Global Search Results - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Global Search Results</h2>
        <p class="text-muted mb-0">Showing matching results for <span class="fw-bold text-primary">"{{ $queryText }}"</span></p>
    </div>

    <!-- Notes Results -->
    <div class="card card-custom bg-white p-4 mb-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-text text-primary me-2"></i>Notes ({{ $notes->count() }})</h5>

        <div class="row g-3">
            @forelse($notes as $note)
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $note->title }}</h6>
                            <small class="text-muted">{{ Str::limit($note->content, 60) }}</small>
                        </div>
                        <a href="{{ route('notes.show', $note) }}" class="btn btn-sm btn-outline-primary">Open</a>
                    </div>
                </div>
            @empty
                <p class="text-muted fs-7 mb-0 px-2">No notes match this keyword.</p>
            @endforelse
        </div>
    </div>

    <!-- Summaries Results -->
    <div class="card card-custom bg-white p-4 mb-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-text text-primary me-2"></i>AI Summaries ({{ $summaries->count() }})</h5>

        <div class="row g-3">
            @forelse($summaries as $sum)
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $sum->title }}</h6>
                            <small class="text-muted">{{ Str::limit($sum->executive_summary, 60) }}</small>
                        </div>
                        <a href="{{ route('summaries.show', $sum) }}" class="btn btn-sm btn-outline-primary">Read</a>
                    </div>
                </div>
            @empty
                <p class="text-muted fs-7 mb-0 px-2">No summaries match this keyword.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
