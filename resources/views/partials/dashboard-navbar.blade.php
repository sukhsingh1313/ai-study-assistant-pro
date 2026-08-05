<header class="navbar navbar-expand bg-white border-bottom sticky-top px-3 px-md-4 py-2" style="z-index: 1040; backdrop-filter: blur(10px); background-color: var(--glass-bg);">
    <div class="container-fluid px-0">
        <!-- Sidebar Toggle Mobile Button -->
        <button type="button" id="sidebarToggle" class="btn btn-light border me-2" title="Toggle Sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>

        <!-- Global Search Input with Ctrl+K shortcut -->
        <form method="GET" action="{{ route('search') }}" class="d-none d-sm-flex align-items-center flex-grow-1 max-w-md mx-md-3" style="max-width: 420px;">
            <div class="input-group input-group-sm w-100">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="search" name="q" id="globalSearchInput" value="{{ request('q') }}" class="form-control bg-light border-start-0 border-end-0 fs-7" placeholder="Search notes, quizzes, flashcards, games..." autocomplete="off">
                <span class="input-group-text bg-light border-start-0 text-muted">
                    <small class="badge bg-white text-muted border fw-semibold">Ctrl+K</small>
                </span>
            </div>
        </form>

        <!-- Right Action Navbar Options -->
        <div class="d-flex align-items-center ms-auto gap-2">
            <!-- Theme Toggle Button -->
            <button type="button" class="btn btn-light border btn-sm" onclick="toggleGlobalTheme()" title="Toggle Dark/Light Mode">
                <i class="bi bi-moon-stars-fill text-warning"></i>
            </button>

            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <button type="button" class="btn btn-light border btn-sm position-relative" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                    <i class="bi bi-bell-fill text-primary"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-3" style="width: 300px;">
                    <h6 class="fw-bold mb-2 text-dark">Notifications</h6>
                    <div class="p-2 bg-light rounded border mb-2">
                        <small class="fw-bold text-dark d-block">🎉 Dynamic Quiz Generator Ready</small>
                        <small class="text-muted">Select question count from 5 to 50 items!</small>
                    </div>
                    <div class="p-2 bg-light rounded border">
                        <small class="fw-bold text-dark d-block">🎮 Study Games Arcade Live</small>
                        <small class="text-muted">Play Word Scramble & Hangman to earn XP.</small>
                    </div>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-7" style="width: 36px; height: 36px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline fw-semibold fs-7">{{ Auth::user()->name ?? 'User' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        <div class="px-3 py-2 border-bottom">
                            <strong class="d-block text-dark">{{ Auth::user()->name }}</strong>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                    </li>
                    <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profile Settings</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('focus.index') }}"><i class="bi bi-fire text-warning me-2"></i>Focus Room & XP</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
