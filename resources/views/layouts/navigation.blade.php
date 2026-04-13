<aside class="sidebar" x-data="{ userMenu: false }">
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar Content -->
    <div class="sidebar-inner">
        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('logo-kkp.png') }}" alt="Logo KKP">
            <div class="sidebar-brand-text">
                <h1>Budidaya Tematik</h1>
                <span>Biro Perencanaan KKP</span>
            </div>
        </a>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Dashboard</div>

            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-nav-label">Kuesioner</div>

            <a href="{{ route('kdmp.index') }}" class="sidebar-link {{ request()->routeIs('kdmp.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <span>KDMP</span>
            </a>

            <a href="{{ route('masyarakat.index') }}"
                class="sidebar-link {{ request()->routeIs('masyarakat.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users fa-fw" style="font-size:1.25rem;"></i>
                <span>Masyarakat</span>
            </a>

            <a href="{{ route('sppg.index') }}" class="sidebar-link {{ request()->routeIs('sppg.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <span>SPPG</span>
            </a>

            <div class="sidebar-nav-label">Monitoring</div>

            <a href="{{ route('monitoring.index') }}"
                class="sidebar-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                <i class="fa-solid fa-map-location-dot fa-fw" style="font-size:1.25rem;"></i>
                <span>Lokasi Budidaya</span>
            </a>

            <div class="sidebar-nav-label">Scoring</div>

            <a href="{{ route('scoring.index') }}"
                class="sidebar-link {{ request()->routeIs('scoring.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span>Scoring</span>
            </a>

            <div class="sidebar-nav-label">Pengaturan</div>

            <a href="{{ route('users.index') }}"
                class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span>Users</span>
            </a>
        </nav>

        <!-- User Section (Bottom) -->
        <div class="sidebar-footer">
            <div class="sidebar-user" @click="userMenu = !userMenu" :class="{ 'active': userMenu }">
                <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="sidebar-chevron"
                    :class="{ 'rotated': userMenu }">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>

            <div class="sidebar-user-menu" x-show="userMenu" x-cloak x-transition>
                <a href="{{ route('profile.edit') }}" class="sidebar-user-menu-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="sidebar-user-menu-item"
                        onclick="event.preventDefault(); let form = this.closest('form'); Swal.fire({ title: 'Konfirmasi Logout', text: 'Apakah Anda yakin ingin keluar dari sistem?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#005b9f', cancelButtonColor: '#dc3545', confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal', reverseButtons: true }).then((result) => { if (result.isConfirmed) { form.submit(); } })">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </a>
                </form>
            </div>
        </div>
    </div>
</aside>