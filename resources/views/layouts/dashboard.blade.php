<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AI Study Assistant Enterprise')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --font-main: 'Inter', system-ui, -apple-system, sans-serif;
            --font-heading: 'Outfit', system-ui, -apple-system, sans-serif;
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --primary-blue: #0d6efd;
            --sidebar-width: 270px;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        [data-bs-theme="dark"] {
            --bg-body: #090d16;
            --card-bg: #111827;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #1f2937;
            --glass-bg: rgba(17, 24, 39, 0.85);
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
        }

        /* Independent Scrolling Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--card-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s ease-in-out;
            scrollbar-width: thin;
        }

        /* Custom Thin Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(100, 116, 139, 0.2);
            border-radius: 4px;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Nav Links Active Highlighting */
        .sidebar .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 10px;
            margin: 2px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-blue);
            background-color: rgba(13, 110, 253, 0.08);
        }

        .sidebar .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .card-custom {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #ffffff;
            border: none;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.35);
            color: #ffffff;
        }

        .floating-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
            z-index: 9999;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .floating-fab:hover {
            transform: scale(1.1);
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Sidebar Partial -->
    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        @include('partials.dashboard-navbar')

        <!-- Page Content Body -->
        <main class="flex-grow-1 p-3 p-md-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer mt-auto py-3 px-4 bg-white border-top text-center text-muted fs-7">
            &copy; {{ date('Y') }} AI Study Assistant Enterprise Edition • All Rights Reserved.
        </footer>
    </div>

    <!-- Floating Action Quick Button -->
    <a href="{{ route('notes.create') }}" class="floating-fab text-white text-decoration-none" title="Create New Study Note">
        <i class="bi bi-plus-lg"></i>
    </a>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Switcher, Shortcut Ctrl+K, Sidebar Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('appSidebar');
            const globalSearchInput = document.getElementById('globalSearchInput');
            const sidebarSearch = document.getElementById('sidebarSearch');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Keyboard Shortcut Ctrl+K to Focus Search
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    if (globalSearchInput) globalSearchInput.focus();
                }
            });

            // Live Sidebar Filter
            if (sidebarSearch) {
                sidebarSearch.addEventListener('input', function() {
                    const filter = this.value.toLowerCase().trim();
                    const navItems = document.querySelectorAll('#appSidebar .nav-item');
                    navItems.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
            
            // Recently Opened Pages Tracker
            const currentPath = window.location.pathname;
            const navLink = document.querySelector(`.sidebar .nav-link[href="${window.location.href}"]`) || document.querySelector('.sidebar .nav-link.active');
            let recentPages = JSON.parse(localStorage.getItem('recentPages') || '[]');
            
            if (navLink && !currentPath.includes('login') && !currentPath.includes('register')) {
                const iconHtml = navLink.querySelector('i').outerHTML;
                const text = navLink.querySelector('span').textContent.trim();
                const url = navLink.getAttribute('href');
                
                recentPages = recentPages.filter(p => p.url !== url);
                recentPages.unshift({ text, url, iconHtml });
                if (recentPages.length > 5) recentPages.pop();
                localStorage.setItem('recentPages', JSON.stringify(recentPages));
            }

            const recentSection = document.getElementById('recentSection');
            const recentList = document.getElementById('recentList');
            if (recentPages.length > 0 && recentSection && recentList) {
                recentSection.classList.remove('d-none');
                recentList.innerHTML = '';
                recentPages.forEach(page => {
                    recentList.innerHTML += `
                        <li class="nav-item">
                            <a href="${page.url}" class="nav-link py-1 fs-8">
                                ${page.iconHtml} <span>${page.text}</span>
                            </a>
                        </li>
                    `;
                });
            }

            // Favorites Logic
            let favoritePages = JSON.parse(localStorage.getItem('favoritePages') || '[]');
            const favoritesSection = document.getElementById('favoritesSection');
            const favoritesList = document.getElementById('favoritesList');
            
            function renderFavorites() {
                if (favoritePages.length > 0 && favoritesSection && favoritesList) {
                    favoritesSection.classList.remove('d-none');
                    favoritesList.innerHTML = '';
                    favoritePages.forEach(page => {
                        favoritesList.innerHTML += `
                            <li class="nav-item position-relative fav-item-row">
                                <a href="${page.url}" class="nav-link py-1 fs-8 pe-4">
                                    ${page.iconHtml} <span>${page.text}</span>
                                </a>
                                <button class="btn btn-sm text-warning position-absolute end-0 top-50 translate-middle-y remove-fav p-0 me-2" data-url="${page.url}">
                                    <i class="bi bi-star-fill"></i>
                                </button>
                            </li>
                        `;
                    });
                } else if(favoritesSection) {
                    favoritesSection.classList.add('d-none');
                }
            }
            renderFavorites();

            // Setup favorite buttons on main nav hover
            document.querySelectorAll('#mainNavList .nav-item').forEach(item => {
                const link = item.querySelector('.nav-link');
                if(!link) return;
                
                item.classList.add('position-relative');
                
                // create toggle btn
                const url = link.getAttribute('href');
                const btn = document.createElement('button');
                const isFav = favoritePages.some(p => p.url === url);
                
                btn.className = `btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 p-0 fav-toggle-btn ${isFav ? 'text-warning' : 'text-muted opacity-25'}`;
                btn.innerHTML = `<i class="bi bi-star${isFav ? '-fill' : ''}"></i>`;
                btn.style.zIndex = '10';
                
                btn.onclick = (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const currentlyFav = favoritePages.some(p => p.url === url);
                    if(currentlyFav) {
                        favoritePages = favoritePages.filter(p => p.url !== url);
                    } else {
                        const iconHtml = link.querySelector('i').outerHTML;
                        const text = link.querySelector('span').textContent.trim();
                        favoritePages.push({ text, url, iconHtml });
                    }
                    localStorage.setItem('favoritePages', JSON.stringify(favoritePages));
                    renderFavorites();
                    
                    // Update this button's state
                    const newFav = !currentlyFav;
                    btn.className = `btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 p-0 fav-toggle-btn ${newFav ? 'text-warning' : 'text-muted opacity-25'}`;
                    btn.innerHTML = `<i class="bi bi-star${newFav ? '-fill' : ''}"></i>`;
                };
                item.appendChild(btn);
            });

            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-fav');
                if(removeBtn) {
                    e.preventDefault();
                    const url = removeBtn.getAttribute('data-url');
                    favoritePages = favoritePages.filter(p => p.url !== url);
                    localStorage.setItem('favoritePages', JSON.stringify(favoritePages));
                    renderFavorites();
                    
                    // Update main nav button if it exists
                    const mainNavBtn = document.querySelector(`#mainNavList .nav-link[href="${url}"] ~ .fav-toggle-btn`);
                    if(mainNavBtn) {
                        mainNavBtn.className = 'btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 p-0 fav-toggle-btn text-muted opacity-25';
                        mainNavBtn.innerHTML = '<i class="bi bi-star"></i>';
                    }
                }
            });
        });

        function toggleGlobalTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        }
    </script>

    @stack('scripts')
</body>
</html>
