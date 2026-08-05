@extends('layouts.dashboard')

@section('title', 'Research Assistant - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Research Assistant & Citation Generator 📚</h2>
            <p class="text-muted mb-0">Generate references and bibliographies in APA, MLA, Chicago, or IEEE formats.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom bg-white p-4 p-md-5 border mb-4 position-relative">
        <!-- Skeleton Loading State -->
        <div id="skeletonLoading" class="w-100 placeholder-glow">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <span class="placeholder col-4 mb-2 rounded"></span>
                    <span class="placeholder w-100 py-3 rounded"></span>
                </div>
                <div class="col-md-4">
                    <span class="placeholder col-6 mb-2 rounded"></span>
                    <span class="placeholder w-100 py-3 rounded"></span>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <span class="placeholder col-3 mb-2 rounded"></span>
                    <span class="placeholder w-100 py-3 rounded"></span>
                </div>
                <div class="col-md-4">
                    <span class="placeholder col-5 mb-2 rounded"></span>
                    <span class="placeholder w-100 py-3 rounded"></span>
                </div>
            </div>
            <span class="placeholder col-3 py-3 rounded"></span>
        </div>

        <!-- Main Content -->
        <div id="mainContent" class="d-none">
            <form method="POST" action="{{ route('research.citation') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label fw-bold text-dark">Paper / Book Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Artificial Intelligence: A Modern Approach" required>
                    </div>

                    <div class="col-md-4">
                        <label for="style" class="form-label fw-bold text-dark">Citation Style</label>
                        <select name="style" id="style" class="form-select" required>
                            <option value="APA" selected>APA 7th</option>
                            <option value="MLA">MLA 9th</option>
                            <option value="Chicago">Chicago</option>
                            <option value="IEEE">IEEE</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label for="authors" class="form-label fw-semibold">Authors</label>
                        <input type="text" name="authors" id="authors" class="form-control" placeholder="e.g. Russell, S., & Norvig, P." required>
                    </div>

                    <div class="col-md-4">
                        <label for="year" class="form-label fw-semibold">Year Published</label>
                        <input type="text" name="year" id="year" class="form-control" placeholder="2020" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom px-5 py-2">
                    <i class="bi bi-bookmark-plus me-1"></i> Generate Citation
                </button>
            </form>
        </div>
    </div>

    <!-- References List -->
    <div class="card card-custom bg-white p-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-bookmark text-primary me-2"></i>Generated Bibliography ({{ $references->count() }})</h5>

        <div class="d-flex flex-column gap-3">
            @forelse($references as $ref)
                <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary text-white mb-1">{{ $ref->citation_style }}</span>
                        <p class="font-monospace fs-7 text-dark mb-0">{{ $ref->formatted_citation }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted fs-7 mb-0 text-center py-3">No references generated yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.getElementById('skeletonLoading').classList.add('d-none');
        document.getElementById('mainContent').classList.remove('d-none');
    }, 600); // Simulate network load
});
</script>
@endpush
