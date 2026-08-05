@extends('layouts.dashboard')

@section('title', 'Admin Panel - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">System Control Panel & Analytics</h2>
            <p class="text-muted mb-0">Platform overview, database storage usage, and system cache controls.</p>
        </div>

        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('admin.cache-clear') }}">
                @csrf
                <button type="submit" class="btn btn-outline-warning d-inline-flex align-items-center gap-2">
                    <i class="bi bi-trash-fill me-1"></i> Flush System Cache
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- System Metrics Grid -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="card card-custom p-3 bg-white border h-100">
                <small class="text-muted fw-semibold fs-8 text-uppercase">Total Users</small>
                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $metrics['total_users'] }}</h3>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card card-custom p-3 bg-white border h-100">
                <small class="text-muted fw-semibold fs-8 text-uppercase">Study Notes</small>
                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $metrics['total_notes'] }}</h3>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card card-custom p-3 bg-white border h-100">
                <small class="text-muted fw-semibold fs-8 text-uppercase">AI Summaries</small>
                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $metrics['total_summaries'] }}</h3>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card card-custom p-3 bg-white border h-100">
                <small class="text-muted fw-semibold fs-8 text-uppercase">Quizzes Set</small>
                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $metrics['total_quizzes'] }}</h3>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card card-custom p-3 bg-white border h-100">
                <small class="text-muted fw-semibold fs-8 text-uppercase">Flashcards</small>
                <h3 class="fw-bold mb-0 text-dark mt-1">{{ $metrics['total_flashcards'] }}</h3>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card card-custom p-3 bg-white border h-100">
                <small class="text-muted fw-semibold fs-8 text-uppercase">Storage Used</small>
                <h3 class="fw-bold mb-0 text-primary mt-1">{{ $metrics['storage_mb'] }} MB</h3>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Registered Users Table -->
        <div class="col-lg-8">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-people text-primary me-2"></i>Platform Users</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-muted fs-7">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $user->is_admin ? 'bg-danger' : 'bg-primary' }}">
                                            {{ $user->is_admin ? 'Admin' : 'Student' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Health Monitor -->
        <div class="col-lg-4">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-cpu text-success me-2"></i>Server Status</h5>

                <div class="d-flex flex-column gap-3">
                    <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark fs-7">PHP Engine Version</span>
                        <span class="badge bg-dark text-white font-monospace">{{ PHP_VERSION }}</span>
                    </div>

                    <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark fs-7">Database Connection</span>
                        <span class="badge bg-success">MySQL Active</span>
                    </div>

                    <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark fs-7">Storage Symlink</span>
                        <span class="badge bg-success">Connected</span>
                    </div>

                    <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark fs-7">Gemini AI Model</span>
                        <span class="badge bg-primary">gemini-2.0-flash</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
