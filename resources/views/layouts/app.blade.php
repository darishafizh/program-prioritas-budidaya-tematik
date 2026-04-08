<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Budidaya Tematik | KKP</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-kkp.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo-kkp.png') }}">

    <!-- Fonts - Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- KKP Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/kkp-theme.css') }}">
    
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body>
    <script>
        // Restore sidebar state BEFORE render to prevent flash
        if (window.innerWidth > 768 && localStorage.getItem('sidebar-collapsed') === 'true') {
            document.write('<div class="app-layout sidebar-collapsed">');
        } else {
            document.write('<div class="app-layout">');
        }
    </script>
        @include('layouts.navigation')

        <div class="app-main">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="topbar-toggle" onclick="toggleSidebar()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:22px;height:22px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="topbar-right">
                    <div class="topbar-date">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span id="current-date"></span>
                    </div>
                </div>
            </header>

            <main class="main-content">
                <div class="container">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Display current date in topbar
        const dateEl = document.getElementById('current-date');
        if (dateEl) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.textContent = new Date().toLocaleDateString('id-ID', options);
        }

        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const appLayout = document.querySelector('.app-layout');

            if (window.innerWidth > 768) {
                // Desktop: collapse/expand & save state
                appLayout.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', appLayout.classList.contains('sidebar-collapsed'));
            } else {
                // Mobile: slide in/out
                sidebar.classList.toggle('sidebar-open');
            }
        }

        // Close sidebar on mobile when clicking a link
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    document.querySelector('.sidebar').classList.remove('sidebar-open');
                }
            });
        });

        // Scroll sidebar to active menu item
        const activeLink = document.querySelector('.sidebar-link.active');
        if (activeLink) {
            activeLink.scrollIntoView({ block: 'center', behavior: 'instant' });
        }

        // Close sidebar overlay on mobile
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('sidebar-overlay')) {
                document.querySelector('.sidebar').classList.remove('sidebar-open');
            }
        });

        // Automatically close mobile sidebar when window is resized to avoid layout bugs
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                document.querySelector('.sidebar').classList.remove('sidebar-open');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
