@extends('layouts.dashboard')

@section('title', 'Generate Flashcards - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Generate AI Flashcard Deck</h2>
            <p class="text-muted mb-0">Transform course notes into interactive spaced-repetition cards.</p>
        </div>
        <a href="{{ route('flashcards.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Decks
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Generation Error</h6>
            <p class="mb-0 fs-7">{{ session('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom p-4 p-md-5 bg-white border position-relative">
        <form method="POST" action="{{ route('flashcards.store') }}" id="flashcardForm">
            @csrf

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="summary_id" class="form-label fw-semibold">From AI Summary</label>
                    <select name="summary_id" id="summary_id" class="form-select form-select-lg">
                        <option value="">-- Select Summary (Recommended) --</option>
                        @foreach($summaries as $sum)
                            <option value="{{ $sum->id }}" {{ (old('summary_id', $selectedSummaryId) == $sum->id) ? 'selected' : '' }}>
                                {{ $sum->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="note_id" class="form-label fw-semibold">From Study Note</label>
                    <select name="note_id" id="note_id" class="form-select form-select-lg">
                        <option value="">-- Select Note --</option>
                        @foreach($notes as $note)
                            <option value="{{ $note->id }}" {{ (old('note_id', $selectedNoteId) == $note->id) ? 'selected' : '' }}>
                                {{ $note->title }} ({{ $note->word_count }} words)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="text-center my-3 position-relative">
                <hr class="text-muted">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted fs-7 font-monospace">OR PASTE RAW TEXT</span>
            </div>

            <div class="mb-4">
                <label for="raw_content" class="form-label fw-semibold">Raw Study Text</label>
                <textarea name="raw_content" id="raw_content" rows="4" class="form-control" placeholder="Paste lecture content or key definitions here...">{{ old('raw_content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="count" class="form-label fw-semibold">Number of Flashcards to Create</label>
                <select name="count" id="count" class="form-select form-select-lg">
                    <option value="5" {{ old('count') == 5 ? 'selected' : '' }}>5 Cards (Quick Deck)</option>
                    <option value="10" {{ old('count', 10) == 10 ? 'selected' : '' }}>10 Cards (Standard Deck)</option>
                    <option value="15" {{ old('count') == 15 ? 'selected' : '' }}>15 Cards (Detailed)</option>
                    <option value="20" {{ old('count') == 20 ? 'selected' : '' }}>20 Cards (Comprehensive)</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('flashcards.index') }}" class="btn btn-light px-4">Cancel</a>
                <button type="submit" id="submitBtn" class="btn btn-primary-custom px-5 py-2">
                    <i class="bi bi-stars me-1"></i> Generate Deck with Gemini
                </button>
            </div>
        </form>

        <div id="aiLoadingOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-95 d-none rounded flex-column align-items-center justify-content-center" style="z-index: 1050; min-height: 400px;">
            <div class="spinner-border text-primary mb-3" style="width: 3.5rem; height: 3.5rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h4 class="fw-bold text-dark mb-2">Building Flashcard Deck...</h4>
            <p class="text-muted fs-7 mb-0 text-center" style="max-width: 400px;">
                Extracting key terminology and creating front/back question pairs.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('flashcardForm');
    const loadingOverlay = document.getElementById('aiLoadingOverlay');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function() {
            loadingOverlay.classList.remove('d-none');
            loadingOverlay.classList.add('d-flex');
            submitBtn.disabled = true;
        });
    }
});
</script>
@endpush
