<nav class="navbar" x-data="{ open: false, userMenu: false }">
    <div class="navbar-container">
        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <img src="{{ asset('logo-kkp.png') }}" alt="Logo KKP">
            <div class="navbar-brand-text">
                <h1>Monev Budidaya Tematik</h1>
                <span>Kementerian Kelautan dan Perikanan</span>
            </div>
        </a>

        <!-- Desktop Menu -->
        <div class="navbar-menu hidden md:flex">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('kdmp.index') }}" class="nav-link {{ request()->routeIs('kdmp.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                KDMP
            </a>
            
            <a href="{{ route('masyarakat.index') }}" class="nav-link {{ request()->routeIs('masyarakat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Masyarakat
            </a>
            
            <a href="{{ route('sppg.index') }}" class="nav-link {{ request()->routeIs('sppg.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                SPPG
            </a>
            
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Users
            </a>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown" :class="{ 'active': userMenu }">
            <div class="navbar-user" @click="userMenu = !userMenu">
                <div class="navbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="navbar-user-info">
                    <div class="navbar-user-name">{{ Auth::user()->name }}</div>
                    <div class="navbar-user-role">Administrator</div>
                </div>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:white;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
            
            <div class="dropdown-menu">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </a>
                </form>
            </div>
        </div>

        <!-- Mobile Toggle -->
        <button class="navbar-toggle" @click="open = !open">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden" x-show="open" x-cloak style="background: linear-gradient(135deg, #0D2137 0%, #164E63 100%); padding: 1rem;">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('kdmp.index') }}" class="nav-link {{ request()->routeIs('kdmp.*') ? 'active' : '' }}">KDMP</a>
        <a href="{{ route('masyarakat.index') }}" class="nav-link {{ request()->routeIs('masyarakat.*') ? 'active' : '' }}">Masyarakat</a>
        <a href="{{ route('sppg.index') }}" class="nav-link {{ request()->routeIs('sppg.*') ? 'active' : '' }}">SPPG</a>
    </div>
</nav>
