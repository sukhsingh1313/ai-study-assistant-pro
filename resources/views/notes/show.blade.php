@extends('layouts.dashboard')

@section('title', $note->title . ' - AI Study Assistant')

@push('styles')
<style>
.dark-reader {
    background-color: #0f172a !important;
    color: #f8fafc !important;
}
.dark-reader .card {
    background-color: #1e293b !important;
    color: #f8fafc !important;
    border-color: #334155 !important;
}
.dark-reader .text-dark {
    color: #f8fafc !important;
}
.dark-reader .text-muted {
    color: #94a3b8 !important;
}
.focus-reader-active #appSidebar, .focus-reader-active .dashboard-navbar {
    display: none !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0" id="noteReadingContainer">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-bold text-dark mb-0" id="noteTitle">{{ $note->title }}</h2>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                    {{ $note->category ?? 'General' }}
                </span>
            </div>
            <p class="text-muted mb-0">Created {{ $note->created_at ? $note->created_at->format('M d, Y') : now()->format('M d, Y') }}</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="button" id="toggleDarkModeBtn" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-moon-stars me-1"></i> Dark Reading Mode
            </button>
            <button type="button" id="toggleFocusModeBtn" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-fullscreen me-1"></i> Focus Mode
            </button>
            <button type="button" id="copyShareLinkBtn" class="btn btn-light border btn-sm text-secondary">
                <i class="bi bi-link-45deg me-1"></i> Copy Link
            </button>
            <button type="button" onclick="window.print()" class="btn btn-light border btn-sm text-secondary">
                <i class="bi bi-printer me-1"></i> Print
            </button>

            @if($note->hasFile())
                <a href="{{ route('notes.download', $note) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download me-1"></i> Attachment
                </a>
            @endif

            <a href="{{ route('notes.edit', $note) }}" class="btn btn-sm btn-light border">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    <!-- Reading Metadata Stat Badges -->
    <div class="d-flex gap-3 mb-4">
        <span class="badge bg-light text-dark border px-3 py-2 fs-7">
            <i class="bi bi-fonts me-1 text-primary"></i> {{ number_format(str_word_count(strip_tags($note->content))) }} Words
        </span>
        <span class="badge bg-light text-dark border px-3 py-2 fs-7">
            <i class="bi bi-clock me-1 text-primary"></i> ~{{ max(1, (int) ceil(str_word_count(strip_tags($note->content)) / 200)) }} Min Read
        </span>
    </div>

    <!-- Main Content Display -->
    <div class="card card-custom bg-white p-4 p-md-5 border mb-4 shadow-sm" id="readingCard">
        <div class="lh-lg text-dark fs-6 font-monospace" style="white-space: pre-line;">
            {{ $note->content }}
        </div>
    </div>

    <!-- AI Quick Action Buttons for Note -->
    <div class="card card-custom bg-light p-4 border mb-4">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-magic text-primary me-2"></i>AI Workspace Tools for this Note</h6>
        <div class="d-flex flex-wrap gap-2">
            <form method="POST" action="{{ route('summaries.store') }}" class="d-inline">
                @csrf
                <input type="hidden" name="note_id" value="{{ $note->id }}">
                <input type="hidden" name="content" value="{{ $note->content }}">
                <button type="submit" class="btn btn-primary-custom btn-sm">
                    <i class="bi bi-file-earmark-text me-1"></i> Summarize with AI
                </button>
            </form>

            <form method="POST" action="{{ route('quizzes.store') }}" class="d-inline">
                @csrf
                <input type="hidden" name="note_id" value="{{ $note->id }}">
                <input type="hidden" name="content" value="{{ $note->content }}">
                <input type="hidden" name="total_questions" value="5">
                <input type="hidden" name="question_type" value="Mixed">
                <input type="hidden" name="difficulty" value="Medium">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-question-square me-1"></i> Generate Practice Quiz
                </button>
            </form>

            <form method="POST" action="{{ route('flashcards.store') }}" class="d-inline">
                @csrf
                <input type="hidden" name="note_id" value="{{ $note->id }}">
                <input type="hidden" name="content" value="{{ $note->content }}">
                <input type="hidden" name="count" value="10">
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-card-heading me-1"></i> Generate Flashcards
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleDarkModeBtn = document.getElementById('toggleDarkModeBtn');
    const toggleFocusModeBtn = document.getElementById('toggleFocusModeBtn');
    const copyShareLinkBtn = document.getElementById('copyShareLinkBtn');

    toggleDarkModeBtn.addEventListener('click', function() {
        document.body.classList.toggle('dark-reader');
    });

    toggleFocusModeBtn.addEventListener('click', function() {
        document.body.classList.toggle('focus-reader-active');
    });

    copyShareLinkBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(window.location.href);
        this.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
        setTimeout(() => this.innerHTML = '<i class="bi bi-link-45deg me-1"></i> Copy Link', 2000);
    });
});
</script>
@endpush
