@extends('layouts.dashboard')

@section('title', 'Trash & Recovery Bin - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Trash & Recovery Bin</h2>
            <p class="text-muted mb-0">Restore soft-deleted study materials or permanently remove items.</p>
        </div>
        <div>
            <span class="badge bg-danger-subtle text-danger px-3 py-2 fs-7 rounded-pill border border-danger-subtle">
                <i class="bi bi-trash me-1"></i> {{ $totalTrashCount }} Items in Trash
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom bg-white p-4 mb-4 border">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-text text-primary me-2"></i>Deleted Study Notes ({{ $deletedNotes->count() }})</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Deleted At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deletedNotes as $note)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $note->title }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $note->category ?? 'General' }}</span></td>
                            <td class="text-muted fs-7">{{ $note->deleted_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('trash.notes.restore', $note->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success me-1">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('trash.notes.force', $note->id) }}" onsubmit="return confirm('Permanently delete this note?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No deleted notes in trash bin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
