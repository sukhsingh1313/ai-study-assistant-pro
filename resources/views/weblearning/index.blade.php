@extends('layouts.dashboard')

@section('title', 'Website Article Learning - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Website Article Learning 🌐</h2>
            <p class="text-muted mb-0">Paste any website or article URL to convert its content into study notes and flashcards.</p>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 p-md-5 border">
        <form method="POST" action="{{ route('weblearning.process') }}">
            @csrf

            <div class="mb-4">
                <label for="url" class="form-label fw-bold text-dark">Website / Article URL</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0 text-primary"><i class="bi bi-globe"></i></span>
                    <input type="url" name="url" id="url" class="form-control border-start-0" placeholder="https://wikipedia.org/wiki/..." required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold shadow-sm">
                <i class="bi bi-magic me-1"></i> Convert Article into Study Material
            </button>
        </form>
    </div>
</div>
@endsection
