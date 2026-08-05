@extends('layouts.dashboard')

@section('title', 'My Study Notes - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">My Study Notes</h2>
            <p class="text-muted mb-0">Organize, search, and manage your course materials, PDFs, and lecture notes.</p>
        </div>
        <div>
            <a href="{{ route('notes.create') }}" class="btn btn-primary-custom d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Add New Note</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card card-custom p-3 bg-white mb-4 border">
        <form method="GET" action="{{ route('notes.index') }}" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search notes by keyword..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="subject_id" class="form-select">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                            {{ $subj->name }} ({{ $subj->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                @if(request()->hasAny(['search', 'subject_id', 'category', 'tag']))
                    <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Notes Grid -->
    @if($notes->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($notes as $note)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 bg-white p-4 d-flex flex-column justify-between border">
                        <div>
                            <!-- Header Badges -->
                            <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                @if($note->subject)
                                    <span class="badge text-white" style="background-color: {{ $note->subject->color }};">
                                        {{ $note->subject->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">General</span>
                                @endif

                                @if($note->isPdf())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-file-pdf me-1"></i>PDF</span>
                                @elseif($note->isImage())
                                    <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-file-image me-1"></i>IMAGE</span>
                                @else
                                    <span class="badge bg-light text-dark border"><i class="bi bi-file-text me-1"></i>TEXT</span>
                                @endif
                            </div>

                            <h5 class="fw-bold text-dark mb-2">
                                <a href="{{ route('notes.show', $note) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($note->title, 45) }}
                                </a>
                            </h5>

                            <p class="text-muted fs-7 mb-3">
                                {{ Str::limit(strip_tags($note->content), 90) }}
                            </p>

                            <!-- Category & Tags -->
                            @if($note->category)
                                <div class="mb-2">
                                    <small class="text-muted fw-semibold">Category:</small>
                                    <span class="badge bg-light text-dark border">{{ $note->category }}</span>
                                </div>
                            @endif

                            @if($note->tags && count($note->tags) > 0)
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @foreach($note->tags as $tag)
                                        <a href="{{ route('notes.index', ['tag' => $tag]) }}" class="badge bg-primary-subtle text-primary text-decoration-none">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted fs-8">
                                <i class="bi bi-clock me-1"></i>{{ $note->created_at->diffForHumans() }}
                            </small>

                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                    <li><a class="dropdown-item" href="{{ route('notes.show', $note) }}"><i class="bi bi-eye me-2"></i>View Note</a></li>
                                    <li><a class="dropdown-item" href="{{ route('notes.edit', $note) }}"><i class="bi bi-pencil me-2"></i>Edit Note</a></li>
                                    @if($note->hasFile())
                                        <li><a class="dropdown-item text-primary" href="{{ route('notes.download', $note) }}"><i class="bi bi-download me-2"></i>Download File</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $notes->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="card card-custom p-5 text-center bg-white border">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mx-auto mb-3" style="width: 64px; height: 64px; justify-content: center; align-items: center;">
                <i class="bi bi-journal-x fs-2"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">No Notes Found</h4>
            <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">
                You haven't uploaded or created any notes matching your criteria yet. Get started by adding your first study material.
            </p>
            <div>
                <a href="{{ route('notes.create') }}" class="btn btn-primary-custom px-4 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Add Your First Note
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
