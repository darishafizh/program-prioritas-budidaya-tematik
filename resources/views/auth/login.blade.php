<x-guest-layout>
    <div class="login-header">
        <img src="{{ asset('logo-kkp.png') }}" alt="Logo KKP" class="login-logo">
        <h1 class="login-title">Survey Budidaya Tematik</h1>
        <p class="login-subtitle">Kementerian Kelautan dan Perikanan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="alert alert-info mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <!-- Username -->
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <input id="username" name="username" type="text" value="{{ old('username') }}" class="form-control"
                    placeholder="Masukkan username anda" required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('username')" class="text-danger text-sm mt-1" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </span>
                <input id="password" name="password" type="password" class="form-control"
                    placeholder="Masukkan password" required autocomplete="current-password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="text-danger text-sm mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-4">
            <label class="form-check" style="background:none; padding:0;">
                <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                <span class="form-check-label">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary font-medium" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary" style="width:100%;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Masuk
        </button>
    </form>


</x-guest-layout>