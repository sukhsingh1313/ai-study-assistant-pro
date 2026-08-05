@extends('layouts.dashboard')

@section('title', 'Enterprise Dashboard - AI Study Assistant')

@push('styles')
<style>
.widget-card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    transition: transform 0.2s ease, shadow 0.2s ease;
}
.widget-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
}
.weather-gradient {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    text-align: center;
    font-size: 0.8rem;
}
.calendar-day-header {
    font-weight: 700;
    color: #64748b;
}
.calendar-day {
    padding: 6px;
    border-radius: 8px;
    background-color: #f8fafc;
}
.calendar-day.today {
    background-color: #0d6efd;
    color: #ffffff;
    font-weight: bold;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Top Welcome Banner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Welcome back, {{ Auth::user()->name ?? 'Student' }} 👋</h2>
            <p class="text-muted mb-0">Here is your AI study workspace, calendar widgets, productivity score, and activity feed.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('notes.create') }}" class="btn btn-primary-custom d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Add Study Note</span>
            </a>
            <a href="{{ route('focus.index') }}" class="btn btn-warning text-dark fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-fire"></i>
                <span>Focus Room</span>
            </a>
        </div>
    </div>

    <!-- Top Widgets Row -->
    <div class="row g-3 mb-4">
        <!-- Weather & Study Time Widget -->
        <div class="col-lg-4">
            <div class="card widget-card weather-gradient p-4 h-100 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="text-uppercase fs-8 fw-bold opacity-75">Local Study Weather</span>
                        <h4 class="fw-bold mb-0 mt-1"><i class="bi bi-sun me-2"></i> 26°C Clear Sky</h4>
                    </div>
                    <span class="badge bg-white text-primary fw-bold rounded-pill px-3 py-1 fs-8">Optimal Study</span>
                </div>
                <p class="mb-0 fs-7 opacity-90"><i class="bi bi-clock me-1"></i> Today is {{ date('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Productivity Score & Progress Ring -->
        <div class="col-lg-4">
            <div class="card widget-card bg-white p-4 h-100 border">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-bold fs-8 text-uppercase">Productivity Score</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">+12% this week</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <h2 class="fw-bold text-dark mb-0 fs-1">{{ $productivityScore }}%</h2>
                    <div>
                        <small class="text-muted d-block fw-semibold">Streak: 🔥 {{ $gamification->streak_days }} Days</small>
                        <small class="text-primary fw-bold">Rank: Level {{ $gamification->level }} Scholar</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Assistant Recommendation Box -->
        <div class="col-lg-4">
            <div class="card widget-card bg-white p-4 h-100 border">
                <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                    <i class="bi bi-robot fs-5"></i>
                    <h6 class="fw-bold mb-0">AI Tutor Recommendation</h6>
                </div>
                <p class="text-muted fs-7 mb-2">Based on your recent notes, review your <strong>Data Structures</strong> flashcards before the upcoming quiz!</p>
                <a href="{{ route('tutor.index') }}" class="text-primary fw-semibold fs-8 text-decoration-none">
                    Ask AI Tutor <i class="bi bi-arrow-right me-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Action Shortcuts -->
    <div class="card widget-card bg-white p-3 mb-4 border">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <span class="fw-bold text-dark fs-7 px-2"><i class="bi bi-lightning-charge text-warning me-1"></i>Quick Actions:</span>
            <a href="{{ route('notes.create') }}" class="btn btn-sm btn-light border"><i class="bi bi-plus-lg me-1"></i>New Note</a>
            <a href="{{ route('summaries.create') }}" class="btn btn-sm btn-light border"><i class="bi bi-file-earmark-text me-1"></i>Summarize Text</a>
            <a href="{{ route('quizzes.create') }}" class="btn btn-sm btn-light border"><i class="bi bi-question-square me-1"></i>New Quiz</a>
            <a href="{{ route('flashcards.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-card-heading me-1"></i>Review Flashcards</a>
            <a href="{{ route('focus.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-stopwatch me-1"></i>Pomodoro Timer</a>
            <a href="{{ route('trash.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-trash me-1"></i>Trash Bin</a>
        </div>
    </div>

    <!-- Charts & Calendar Grid -->
    <div class="row g-4 mb-4">
        <!-- Analytics Chart -->
        <div class="col-lg-8">
            <div class="card widget-card bg-white p-4 h-100 border">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-bar-chart-line text-primary me-2"></i>Study Activity Chart</h5>
                <div style="height: 250px;">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Mini Calendar Widget -->
        <div class="col-lg-4">
            <div class="card widget-card bg-white p-4 h-100 border">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-calendar-event text-primary me-2"></i>Study Calendar</h5>
                <div class="calendar-grid mb-3">
                    <div class="calendar-day-header">S</div>
                    <div class="calendar-day-header">M</div>
                    <div class="calendar-day-header">T</div>
                    <div class="calendar-day-header">W</div>
                    <div class="calendar-day-header">T</div>
                    <div class="calendar-day-header">F</div>
                    <div class="calendar-day-header">S</div>

                    <div class="calendar-day">1</div>
                    <div class="calendar-day">2</div>
                    <div class="calendar-day">3</div>
                    <div class="calendar-day today">4</div>
                    <div class="calendar-day">5</div>
                    <div class="calendar-day">6</div>
                    <div class="calendar-day">7</div>
                    <div class="calendar-day">8</div>
                    <div class="calendar-day">9</div>
                    <div class="calendar-day">10</div>
                    <div class="calendar-day">11</div>
                    <div class="calendar-day">12</div>
                    <div class="calendar-day">13</div>
                    <div class="calendar-day">14</div>
                </div>
                <small class="text-muted fs-8"><i class="bi bi-dot text-primary fs-6"></i> Blue highlight indicates today</small>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tables -->
    <div class="row g-4">
        <!-- Recent Notes -->
        <div class="col-lg-6">
            <div class="card widget-card bg-white p-4 border h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Recent Study Notes</h5>
                    <a href="{{ route('notes.index') }}" class="text-primary text-decoration-none fs-7 fw-semibold">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($recentNotes as $note)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ Str::limit($note->title, 35) }}</td>
                                    <td class="text-muted fs-7">{{ $note->created_at->diffForHumans() }}</td>
                                    <td class="text-end"><a href="{{ route('notes.show', $note) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted text-center py-3">No study notes created yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent AI Summaries -->
        <div class="col-lg-6">
            <div class="card widget-card bg-white p-4 border h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark">Recent AI Summaries</h5>
                    <a href="{{ route('summaries.index') }}" class="text-primary text-decoration-none fs-7 fw-semibold">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($recentSummaries as $sum)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ Str::limit($sum->title, 35) }}</td>
                                    <td class="text-muted fs-7">{{ $sum->created_at->diffForHumans() }}</td>
                                    <td class="text-end"><a href="{{ route('summaries.show', $sum) }}" class="btn btn-sm btn-outline-primary">Read</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted text-center py-3">No summaries created yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statsData = @json($stats);

    const ctx = document.getElementById('dashboardChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Study Notes', 'AI Summaries', 'Quizzes Completed', 'Smart Flashcards'],
            datasets: [{
                label: 'Count',
                data: [statsData.total_notes, statsData.total_summaries, statsData.quizzes_completed, statsData.total_flashcards],
                backgroundColor: ['#0d6efd', '#198754', '#0dcaf0', '#ffc107'],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush
