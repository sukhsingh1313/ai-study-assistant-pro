@extends('layouts.dashboard')

@section('title', 'Generate AI Summary - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Generate AI Study Summary</h2>
            <p class="text-muted mb-0">Transform long notes or study materials into structured key insights.</p>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border">
        <form method="POST" action="{{ route('summaries.store') }}">
            @csrf

            <!-- Select Existing Note -->
            <div class="mb-4">
                <label for="note_id" class="form-label fw-bold text-dark">Select Saved Note (Optional)</label>
                <select name="note_id" id="note_id" class="form-select">
                    <option value="">-- Or paste custom text below --</option>
                    @foreach($notes as $note)
                        <option value="{{ $note->id }}" data-content="{{ $note->content }}">{{ $note->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Summary Type & Length Controls -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="summary_type" class="form-label fw-bold text-dark">Summary Type</label>
                    <select name="summary_type" id="summary_type" class="form-select" required>
                        <option value="Detailed Summary" selected>Detailed Summary</option>
                        <option value="Short Summary">Short Summary</option>
                        <option value="Bullet Summary">Bullet Summary</option>
                        <option value="Exam Notes">Exam Notes</option>
                        <option value="Revision Notes">Revision Notes</option>
                        <option value="One Minute Revision">One Minute Revision</option>
                        <option value="Key Points">Key Points</option>
                        <option value="Important Definitions">Important Definitions</option>
                        <option value="Formula Sheet">Formula Sheet</option>
                        <option value="Interview Notes">Interview Notes</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="target_length" class="form-label fw-bold text-dark">Summary Length</label>
                    <select name="target_length" id="target_length" class="form-select" required>
                        <option value="100 words">100 Words (Brief)</option>
                        <option value="300 words" selected>300 Words (Standard)</option>
                        <option value="500 words">500 Words (In-Depth)</option>
                        <option value="Custom Length">Custom Length</option>
                    </select>
                </div>
            </div>

            <!-- Study Material Input -->
            <div class="mb-4">
                <label for="content" class="form-label fw-bold text-dark">Study Content to Summarize</label>
                <textarea name="content" id="content" rows="9" class="form-control font-monospace @error('content') is-invalid @enderror" placeholder="Paste your complete lecture notes or textbook chapter text here..." required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('summaries.index') }}" class="btn btn-light px-4">Cancel</a>
                <button type="submit" class="btn btn-primary-custom px-5 py-2">
                    <i class="bi bi-magic me-1"></i> Synthesize Summary
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const noteSelect = document.getElementById('note_id');
    const contentTextarea = document.getElementById('content');

    noteSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const noteContent = selectedOption.getAttribute('data-content');
        if (noteContent) {
            contentTextarea.value = noteContent;
        }
    });
});
</script>
@endpush
