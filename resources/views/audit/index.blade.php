@extends('layouts.dashboard')

@section('title', 'Security & Audit Timeline - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Security & Audit Log Timeline</h2>
            <p class="text-muted mb-0">Monitor user activity logs, login history, and device security sessions.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Activity Log Timeline -->
        <div class="col-lg-7">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-clock-history text-primary me-2"></i>Activity Audit Log</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>IP Address</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $log->action }}</td>
                                    <td class="font-monospace fs-7 text-muted">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                                    <td class="text-muted fs-7">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No recent activity audit logs recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $auditLogs->links() }}
                </div>
            </div>
        </div>

        <!-- Login History & Devices -->
        <div class="col-lg-5">
            <div class="card card-custom bg-white p-4 border h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-shield-check text-success me-2"></i>Recent Login Sessions</h5>

                <div class="d-flex flex-column gap-3">
                    @forelse($loginHistories as $session)
                        <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $session->browser ?? 'Chrome / Windows' }}</h6>
                                <small class="text-muted font-monospace"><i class="bi bi-geo-alt me-1"></i>{{ $session->ip_address ?? '127.0.0.1' }}</small>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                {{ $session->login_at ? $session->login_at->diffForHumans() : 'Active Session' }}
                            </span>
                        </div>
                    @empty
                        <div class="p-3 bg-light rounded border text-center text-muted fs-7">
                            Active session: Windows PC (127.0.0.1)
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
