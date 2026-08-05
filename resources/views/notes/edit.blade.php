@extends('layouts.dashboard')

@section('title', 'Edit Note - ' . $note->title)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Edit Study Note</h2>
            <p class="text-muted mb-0">Update your note details, tags, content, or replacement attachment.</p>
        </div>
        <a href="{{ route('notes.show', $note) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Cancel
        </a>
    </div>

    <div class="card card-custom p-4 p-md-5 bg-white border">
        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger border-0 alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('notes.update', $note) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <label for="title" class="form-label fw-semibold">Note Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title', $note->title) }}" required>
                </div>

                <div class="col-md-4">
                    <label for="subject_id" class="form-label fw-semibold">Academic Subject</label>
                    <select name="subject_id" id="subject_id" class="form-select form-select-lg">
                        <option value="">-- Select Subject (Optional) --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}" {{ old('subject_id', $note->subject_id) == $subj->id ? 'selected' : '' }}>
                                {{ $subj->name }} ({{ $subj->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $note->category) }}">
                </div>

                <div class="col-md-6">
                    <label for="tags" class="form-label fw-semibold">Tags <small class="text-muted">(Comma-separated)</small></label>
                    <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags', is_array($note->tags) ? implode(', ', $note->tags) : '') }}">
                </div>
            </div>

            <div class="mb-4">
                <label for="attachment" class="form-label fw-semibold">Replace File Attachment <small class="text-muted">(Optional - PDF, JPG, PNG, WEBP)</small></label>
                @if($note->hasFile())
                    <div class="alert alert-light border mb-2 d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-paperclip me-2 text-primary"></i> Current File: <strong>{{ basename($note->raw_file_path) }}</strong></span>
                        <a href="{{ route('notes.download', $note) }}" class="btn btn-sm btn-outline-primary">Download Current</a>
                    </div>
                @endif
                <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp">
            </div>

            <div class="mb-4">
                <label for="content" class="form-label fw-semibold">Note Content / Description <span class="text-danger">*</span></label>
                <textarea name="content" id="content" rows="8" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $note->content) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('notes.show', $note) }}" class="btn btn-light px-4">Cancel</a>
                <button type="submit" class="btn btn-primary-custom px-5">
                    <i class="bi bi-save me-1"></i> Update Note
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
