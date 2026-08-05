<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-blue-primary fs-4" href="{{ route('landing') }}">
            <i class="bi bi-cpu-fill text-primary"></i>
            <span>AI Study Assistant</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('landing') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('landing') }}#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('landing') }}#about">About</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary-custom">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-custom px-4">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary-custom px-4">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
