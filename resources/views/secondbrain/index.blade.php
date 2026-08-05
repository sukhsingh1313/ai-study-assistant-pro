@extends('layouts.dashboard')

@section('title', 'AI Second Brain - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">AI Second Brain 🧠</h2>
            <p class="text-muted mb-0">Personalized study recommendations, weak topic detection, and learning pattern analytics.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-danger mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Weak Topics Identified</h5>
                <ul class="list-group list-group-flush">
                    @foreach($secondBrain->weak_topics as $topic)
                        <li class="list-group-item bg-light border-0 rounded mb-2 fw-semibold text-dark d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-dot text-danger fs-4 me-1"></i>{{ $topic }}</span>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Needs Revision</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>Strong Mastery Topics</h5>
                <ul class="list-group list-group-flush">
                    @foreach($secondBrain->strong_topics as $topic)
                        <li class="list-group-item bg-light border-0 rounded mb-2 fw-semibold text-dark d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-star-fill text-warning fs-8 me-2"></i>{{ $topic }}</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Mastered</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-robot text-primary me-2"></i>AI Next Study Recommendations</h5>
        <div class="row g-3">
            @foreach($secondBrain->recommendations as $rec)
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded border">
                        <span class="badge bg-primary text-white text-uppercase fs-8 mb-2">{{ $rec['type'] }}</span>
                        <h6 class="fw-bold text-dark mb-2">{{ $rec['title'] }}</h6>
                        <a href="{{ route($rec['action']) }}" class="btn btn-sm btn-outline-primary w-100">Start Activity</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
