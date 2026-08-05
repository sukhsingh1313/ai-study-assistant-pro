<aside class="sidebar py-3" id="appSidebar">
    <!-- Brand Header -->
    <div class="px-3 mb-3 d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </div>
            <span class="fw-bold text-dark fs-5">Study AI Pro</span>
        </a>
    </div>

    <!-- Live Sidebar Filter Search -->
    <div class="px-3 mb-3">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" id="sidebarSearch" class="form-control bg-light border-start-0 fs-8" placeholder="Filter menu items...">
        </div>
    </div>

    <div id="favoritesSection" class="px-3 mb-2 d-none">
        <small class="text-uppercase text-muted fw-bold fs-8">Favorites <i class="bi bi-star-fill text-warning"></i></small>
        <ul class="nav flex-column mb-3 mt-1" id="favoritesList">
        </ul>
    </div>

    <div id="recentSection" class="px-3 mb-2 d-none">
        <small class="text-uppercase text-muted fw-bold fs-8">Recently Opened <i class="bi bi-clock-history text-secondary"></i></small>
        <ul class="nav flex-column mb-3 mt-1" id="recentList">
        </ul>
    </div>

    <div class="px-3 mb-2">
        <small class="text-uppercase text-muted fw-bold fs-8">Main Navigation</small>
    </div>
    <ul class="nav flex-column mb-auto" id="mainNavList">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('secondbrain.index') }}" class="nav-link {{ request()->routeIs('secondbrain.*') ? 'active' : '' }}">
                <i class="bi bi-cpu fs-5 text-primary"></i>
                <span>AI Second Brain</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('knowledgegraph.index') }}" class="nav-link {{ request()->routeIs('knowledgegraph.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 fs-5 text-info"></i>
                <span>Knowledge Graph</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('games.index') }}" class="nav-link {{ request()->routeIs('games.*') ? 'active' : '' }}">
                <i class="bi bi-controller fs-5 text-success"></i>
                <span>Study Games Arcade 🎮</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('playground.index') }}" class="nav-link {{ request()->routeIs('playground.*') ? 'active' : '' }}">
                <i class="bi bi-code-slash fs-5 text-warning"></i>
                <span>AI Coding Playground</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('youtube.index') }}" class="nav-link {{ request()->routeIs('youtube.*') ? 'active' : '' }}">
                <i class="bi bi-youtube fs-5 text-danger"></i>
                <span>YouTube Learning</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('weblearning.index') }}" class="nav-link {{ request()->routeIs('weblearning.*') ? 'active' : '' }}">
                <i class="bi bi-globe fs-5 text-primary"></i>
                <span>Website Learning</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('whiteboard.index') }}" class="nav-link {{ request()->routeIs('whiteboard.*') ? 'active' : '' }}">
                <i class="bi bi-palette fs-5 text-warning"></i>
                <span>Whiteboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('research.index') }}" class="nav-link {{ request()->routeIs('research.*') ? 'active' : '' }}">
                <i class="bi bi-book fs-5 text-secondary"></i>
                <span>Research Assistant</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('focus.index') }}" class="nav-link {{ request()->routeIs('focus.*') ? 'active' : '' }}">
                <i class="bi bi-fire fs-5 text-danger"></i>
                <span>Focus Room & XP</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('notes.index') }}" class="nav-link {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text fs-5"></i>
                <span>My Notes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('summaries.index') }}" class="nav-link {{ request()->routeIs('summaries.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text fs-5"></i>
                <span>AI Summarizer</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('flashcards.index') }}" class="nav-link {{ request()->routeIs('flashcards.*') ? 'active' : '' }}">
                <i class="bi bi-card-heading fs-5"></i>
                <span>Smart Flashcards</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('quizzes.index') }}" class="nav-link {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                <i class="bi bi-question-square fs-5"></i>
                <span>Quiz Generator</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tutor.index') }}" class="nav-link {{ request()->routeIs('tutor.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-dots fs-5"></i>
                <span>AI Tutor Chat</span>
            </a>
        </li>
    </ul>

    <hr class="mx-3 my-3">

    <div class="px-3 mb-2">
        <small class="text-uppercase text-muted fw-bold fs-8">Account & Enterprise</small>
    </div>
    <ul class="nav flex-column mb-3">
        @if(Auth::check() && Auth::user()->isAdmin())
            <li class="nav-item">
                <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill fs-5 text-danger"></i>
                    <span>Admin Panel Only</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear fs-5"></i>
                <span>Profile Settings</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('trash.index') }}" class="nav-link {{ request()->routeIs('trash.*') ? 'active' : '' }}">
                <i class="bi bi-trash fs-5"></i>
                <span>Trash System</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history fs-5"></i>
                <span>Security & Audit</span>
            </a>
        </li>
    </ul>
</aside>
