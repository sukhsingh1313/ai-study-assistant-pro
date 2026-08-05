@extends('layouts.dashboard')

@section('title', 'Knowledge Graph - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Knowledge Graph Tree 🕸️</h2>
            <p class="text-muted mb-0">Hierarchical concept mapping linking subjects, chapters, formulas, and study quizzes.</p>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 border">
        <div class="row g-4">
            @foreach($nodes as $node)
                <div class="col-md-6 col-lg-4">
                    <div class="p-4 bg-light rounded border h-100">
                        <span class="badge bg-primary text-white rounded-pill mb-2">{{ $node->subject }}</span>
                        <h5 class="fw-bold text-dark mb-1">{{ $node->topic }}</h5>
                        <small class="text-muted d-block mb-3"><i class="bi bi-journal-bookmark me-1"></i> Chapter: {{ $node->chapter }}</small>
                        
                        <div class="p-2 bg-white rounded border mb-2 font-monospace fs-8 text-primary">
                            <i class="bi bi-calculator me-1"></i> {{ $node->formula }}
                        </div>
                        <p class="text-muted fs-8 mb-0">{{ $node->definition }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
