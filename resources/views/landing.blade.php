@extends('layouts.app')

@section('title', 'AI Study Assistant - Smart AI Learning Platform')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-gradient-blue text-white">
    <div class="container py-5">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <span class="badge bg-light text-primary rounded-pill px-3 py-2 fw-semibold mb-3">
                    <i class="bi bi-stars me-1"></i> Next-Gen Learning Companion
                </span>
                <h1 class="display-4 fw-bold lh-sm mb-3">
                    Master Any Subject 10x Faster with AI
                </h1>
                <p class="lead mb-4 opacity-90">
                    Transform your study materials, lecture notes, and textbooks into interactive summaries, flashcards, and instant practice quizzes powered by AI.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light text-primary btn-lg fw-bold px-4 shadow-sm">
                        Get Started Free <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg px-4">
                        Explore Features
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="card card-custom p-4 bg-white text-dark shadow-lg border-0">
                    <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle p-2">
                            <i class="bi bi-robot fs-4"></i>
                        </div>
                        <div class="text-start">
                            <h6 class="mb-0 fw-bold">AI Assistant Active</h6>
                            <small class="text-muted">Analyzing: Quantum Mechanics Notes.pdf</small>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded mb-3 text-start fs-7">
                        <p class="mb-2 fw-semibold text-primary"><i class="bi bi-magic me-1"></i> Generated Key Summary:</p>
                        <ul class="mb-0 ps-3">
                            <li>Wave-particle duality applies to matter and light.</li>
                            <li>Schrödinger equation describes quantum state changes over time.</li>
                            <li>Heisenberg Uncertainty Principle bounds precision limits.</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">12 Flashcards Generated</span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">5 Quiz Questions Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-w-xl mx-auto mb-5">
            <span class="text-blue-primary fw-bold text-uppercase tracking-wide">Core Features</span>
            <h2 class="fw-bold mt-2">Everything You Need to Ace Your Exams</h2>
            <p class="text-muted">Designed specifically for students, researchers, and lifelong learners.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 border border-light">
                    <div class="stat-icon bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-2">Instant Note Summaries</h4>
                    <p class="text-muted mb-0">Upload lengthy PDFs or paste lecture notes to extract core concepts in seconds.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 border border-light">
                    <div class="stat-icon bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-card-heading"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-2">Smart Flashcards</h4>
                    <p class="text-muted mb-0">Automatically create spaced-repetition flashcard decks from your study material.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 border border-light">
                    <div class="stat-icon bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-patch-question"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-2">Adaptive Quiz Generator</h4>
                    <p class="text-muted mb-0">Test your knowledge with AI-generated multiple choice and short answer quizzes.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="card card-custom bg-white p-5 border border-primary-subtle shadow-sm text-center">
            <h2 class="fw-bold text-dark mb-3">Ready to Elevate Your Study Routine?</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width: 600px;">
                Join thousands of students saving hours of preparation with AI Study Assistant.
            </p>
            <div>
                <a href="{{ route('register') }}" class="btn btn-primary-custom btn-lg px-5">
                    Create Free Account
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
