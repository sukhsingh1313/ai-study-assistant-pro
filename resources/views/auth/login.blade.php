@extends('layouts.app')

@section('title', 'Log In - AI Study Assistant')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5 bg-white border">
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-person-lock fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                    <p class="text-muted fs-7">Sign in to continue to AI Study Assistant</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 alert-dismissible fade show mb-4" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.perform') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-semibold mb-0">Password</label>
                            <a href="#" class="fs-7 text-primary text-decoration-none">Forgot?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted fs-7" for="remember">
                            Remember me on this device
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mb-3">
                        Log In
                    </button>

                    <div class="text-center">
                        <span class="text-muted fs-7">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="text-primary fw-semibold fs-7 text-decoration-none ms-1">Register here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
