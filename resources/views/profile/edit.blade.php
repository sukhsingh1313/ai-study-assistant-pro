@extends('layouts.dashboard')

@section('title', 'Profile Settings - AI Study Assistant')

@section('content')
<div class="container-fluid px-0" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Account & Profile Settings</h2>
            <p class="text-muted mb-0">Update your personal information, avatar, and academic institution.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom p-4 p-md-5 bg-white border">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Preview & Header -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-4 mb-4 pb-4 border-bottom">
                <div class="position-relative">
                    @if($user->profile && $user->profile->avatar)
                        <img src="{{ Storage::url($user->profile->avatar) }}" alt="{{ $user->name }}" class="rounded-circle border" style="width: 90px; height: 90px; object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 90px; height: 90px;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div>
                    <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                    <p class="text-muted fs-7 mb-2">{{ $user->email }}</p>
                    <label for="avatar" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-camera me-1"></i> Change Avatar Image
                    </label>
                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                    @error('avatar')
                        <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Profile Details Form -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->profile->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 (555) 000-0000">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="institution" class="form-label fw-semibold">University / School Institution</label>
                    <input type="text" name="institution" id="institution" value="{{ old('institution', $user->profile->institution ?? '') }}" class="form-control @error('institution') is-invalid @enderror" placeholder="e.g. Stanford University">
                    @error('institution')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="bio" class="form-label fw-semibold">Short Bio / Study Focus</label>
                    <textarea name="bio" id="bio" rows="3" class="form-control @error('bio') is-invalid @enderror" placeholder="Describe your degree or study goals...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-light px-4">Cancel</a>
                <button type="submit" class="btn btn-primary-custom px-5 py-2">
                    <i class="bi bi-check-lg me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
