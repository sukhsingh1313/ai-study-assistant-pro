@extends('layouts.app')

@section('title', 'Register Account - AI Study Assistant')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark mb-1">Create Student Account</h3>
        <p class="text-muted fs-7">Join AI Study Assistant to summarize notes and generate quizzes</p>
    </div>

    <form method="POST" action="{{ route('register.perform') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Full Name</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control border-start-0 @error('name') is-invalid @enderror" placeholder="Alex Johnson" required autofocus>
            </div>
            @error('name')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control border-start-0 @error('email') is-invalid @enderror" placeholder="student@university.edu" required>
            </div>
            @error('email')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
            </div>
            <!-- Live Password Strength Meter -->
            <div class="progress mt-2" style="height: 5px;">
                <div id="passwordStrengthBar" class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted fs-8 mt-1 d-block" id="passwordStrengthText">Password strength: Weak</small>

            @error('password')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-semibold shadow-sm mb-3">
            <i class="bi bi-person-plus me-1"></i> Register & Get Started
        </button>

        <div class="text-center">
            <p class="fs-7 text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Sign In</a></p>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const bar = document.getElementById('passwordStrengthBar');
    const text = document.getElementById('passwordStrengthText');

    passwordInput.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if (val.length >= 8) score += 25;
        if (/[A-Z]/.test(val)) score += 25;
        if (/[0-9]/.test(val)) score += 25;
        if (/[^A-Za-z0-9]/.test(val)) score += 25;

        bar.style.width = score + '%';
        if (score <= 25) {
            bar.className = 'progress-bar bg-danger';
            text.textContent = 'Password strength: Weak (Add numbers & symbols)';
        } else if (score <= 75) {
            bar.className = 'progress-bar bg-warning';
            text.textContent = 'Password strength: Medium';
        } else {
            bar.className = 'progress-bar bg-success';
            text.textContent = 'Password strength: Strong 💪';
        }
    });
});
</script>
@endpush
