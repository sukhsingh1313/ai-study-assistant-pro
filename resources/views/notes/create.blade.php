@extends('layouts.dashboard')

@section('title', 'Create Study Note - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Create New Study Note</h2>
            <p class="text-muted mb-0">Add your handwritten or lecture notes, upload attachments, and tag for quick AI indexing.</p>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border">
        <form method="POST" action="{{ route('notes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label for="title" class="form-label fw-bold text-dark">Note Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Data Structures - Binary Search Trees" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="subject_id" class="form-label fw-bold text-dark">Subject / Course</label>
                    <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
                        <option value="">Select Subject (Optional)</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label fw-semibold">Category / Topic</label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}" class="form-control" placeholder="e.g. Computer Science">
                </div>

                <div class="col-md-6">
                    <label for="file" class="form-label fw-semibold">Attachment File (PDF, DOCX, TXT)</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx,.txt,.png,.jpg">
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="content" class="form-label fw-bold text-dark mb-0">Study Content & Notes</label>
                    <small class="text-muted font-monospace fs-8" id="liveWordCounter">0 Words • ~0 Min Read</small>
                </div>
                <textarea name="content" id="content" rows="10" class="form-control font-monospace @error('content') is-invalid @enderror" placeholder="Write or paste your complete study material here..." required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('notes.index') }}" class="btn btn-light px-4">Cancel</a>
                <button type="submit" class="btn btn-primary-custom px-5 py-2">
                    <i class="bi bi-save me-1"></i> Save Note
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('content');
    const counter = document.getElementById('liveWordCounter');

    textarea.addEventListener('input', function() {
        const text = this.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const readTime = Math.max(1, Math.ceil(words / 200));
        counter.textContent = `${words} Words • ~${readTime} Min Read`;
    });
});
</script>
@endpush
