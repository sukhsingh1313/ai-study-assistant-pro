@extends('layouts.dashboard')

@section('title', 'AI Summary History - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">AI Summaries History</h2>
            <p class="text-muted mb-0">Review AI-generated summaries, key points, and executive overviews produced by Gemini.</p>
        </div>
        <div>
            <a href="{{ route('summaries.create') }}" class="btn btn-primary-custom d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-stars"></i>
                <span>Generate New Summary</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card card-custom p-3 bg-white mb-4 border">
        <form method="GET" action="{{ route('summaries.index') }}" class="row g-3 align-items-center">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search summaries by title or text..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="subject_id" class="form-select">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                            {{ $subj->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                @if(request()->hasAny(['search', 'subject_id']))
                    <a href="{{ route('summaries.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Summaries Grid -->
    @if($summaries->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($summaries as $summary)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 bg-white p-4 d-flex flex-column justify-between border">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-8">
                                    <i class="bi bi-cpu me-1"></i>{{ $summary->ai_model_used }}
                                </span>
                                <span class="badge bg-light text-muted border">
                                    <i class="bi bi-clock me-1"></i>{{ $summary->reading_time_minutes }} min read
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2">
                                <a href="{{ route('summaries.show', $summary) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($summary->title, 50) }}
                                </a>
                            </h5>

                            <p class="text-muted fs-7 mb-3">
                                {{ Str::limit($summary->executive_summary, 110) }}
                            </p>

                            @if($summary->key_points && count($summary->key_points) > 0)
                                <div class="bg-light p-2 rounded mb-3 fs-8">
                                    <small class="fw-bold text-primary"><i class="bi bi-list-check me-1"></i>Key Takeaways ({{ count($summary->key_points) }}):</small>
                                    <ul class="mb-0 ps-3 text-muted">
                                        @foreach(array_slice($summary->key_points, 0, 2) as $pt)
                                            <li>{{ Str::limit($pt, 50) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted fs-8">
                                {{ $summary->created_at->diffForHumans() }}
                            </small>

                            <div class="d-flex gap-2">
                                <a href="{{ route('summaries.show', $summary) }}" class="btn btn-sm btn-light text-primary fw-semibold">
                                    Read Full
                                </a>
                                <form method="POST" action="{{ route('summaries.retry', $summary) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Regenerate with Gemini">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $summaries->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="card card-custom p-5 text-center bg-white border">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mx-auto mb-3" style="width: 64px; height: 64px; justify-content: center; align-items: center;">
                <i class="bi bi-file-earmark-x fs-2"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">No AI Summaries Generated Yet</h4>
            <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">
                Select a note or paste your study materials to generate an instant executive summary with Gemini AI.
            </p>
            <div>
                <a href="{{ route('summaries.create') }}" class="btn btn-primary-custom px-4 py-2">
                    <i class="bi bi-stars me-1"></i> Generate Your First AI Summary
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
