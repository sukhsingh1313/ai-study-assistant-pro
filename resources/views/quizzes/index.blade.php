@extends('layouts.dashboard')

@section('title', 'AI Quizzes History - AI Study Assistant')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">AI Practice Quizzes</h2>
            <p class="text-muted mb-0">Test your retention with AI-generated MCQs and True/False practice exams.</p>
        </div>
        <div>
            <a href="{{ route('quizzes.create') }}" class="btn btn-primary-custom d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-patch-question-fill"></i>
                <span>Generate New Quiz</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card card-custom p-3 bg-white mb-4 border">
        <form method="GET" action="{{ route('quizzes.index') }}" class="row g-3 align-items-center">
            <div class="col-md-9">
                <select name="difficulty" class="form-select" onchange="this.form.submit()">
                    <option value="">All Difficulty Levels</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                @if(request()->has('difficulty'))
                    <a href="{{ route('quizzes.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Quizzes Grid -->
    @if($quizzes->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($quizzes as $quiz)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 bg-white p-4 d-flex flex-column justify-between border">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge text-uppercase {{ $quiz->difficulty == 'easy' ? 'bg-success-subtle text-success border border-success-subtle' : ($quiz->difficulty == 'medium' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }}">
                                    {{ $quiz->difficulty }}
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1"></i>{{ $quiz->timer_minutes }} mins
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2">
                                {{ Str::limit($quiz->title, 45) }}
                            </h5>

                            <div class="d-flex align-items-center gap-3 text-muted fs-7 mb-3">
                                <span><i class="bi bi-question-circle me-1"></i>{{ $quiz->total_questions }} Questions</span>
                                @if($quiz->is_completed)
                                    <span class="badge bg-success"><i class="bi bi-check-all me-1"></i>Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                @endif
                            </div>

                            @if($quiz->is_completed && $quiz->score !== null)
                                <div class="bg-light p-3 rounded mb-3 text-center border">
                                    <small class="text-muted fw-semibold">Score Achieved:</small>
                                    <h3 class="fw-bold mb-0 {{ $quiz->score >= 70 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($quiz->score, 1) }}%
                                    </h3>
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted fs-8">
                                {{ $quiz->created_at->diffForHumans() }}
                            </small>

                            <div class="d-flex gap-2">
                                @if($quiz->is_completed)
                                    <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-sm btn-outline-primary">
                                        Results Report
                                    </a>
                                @else
                                    <a href="{{ route('quizzes.take', $quiz) }}" class="btn btn-sm btn-primary-custom">
                                        Start Quiz <i class="bi bi-play-fill"></i>
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('quizzes.destroy', $quiz) }}" onsubmit="return confirm('Are you sure you want to delete this quiz?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $quizzes->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="card card-custom p-5 text-center bg-white border">
            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mx-auto mb-3" style="width: 64px; height: 64px; justify-content: center; align-items: center;">
                <i class="bi bi-question-square-fill fs-2"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">No Quizzes Generated Yet</h4>
            <p class="text-muted mb-4" style="max-width: 450px; margin: 0 auto;">
                Build custom practice exams with MCQs and True/False questions generated directly from your summaries or study notes.
            </p>
            <div>
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary-custom px-4 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Create Your First Practice Quiz
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
