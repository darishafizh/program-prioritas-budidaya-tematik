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
            <label for="username" class="form-label" style="color: white;">Username</label>
            <div class="input-group">
                <span class="input-icon" style="color: rgba(255, 255, 255, 0.7) !important; z-index: 5;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <input id="username" name="username" type="text" value="{{ old('username') }}" class="form-control"
                    placeholder="Masukkan username anda" required autofocus autocomplete="username" style="color: white !important;">
            </div>
            <x-input-error :messages="$errors->get('username')" class="text-danger text-sm mt-1" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label" style="color: white;">Password</label>
            <div class="input-group">
                <span class="input-icon" style="color: rgba(255, 255, 255, 0.7) !important; z-index: 5;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </span>
                <input id="password" name="password" type="password" class="form-control"
                    placeholder="Masukkan password" required autocomplete="current-password" style="padding-right: 40px; color: white !important;">
                
                <span onclick="var p = document.getElementById('password'); var isP = p.type === 'password'; p.type = isP ? 'text' : 'password'; document.getElementById('eye-show').style.display = isP ? 'none' : 'block'; document.getElementById('eye-hide').style.display = isP ? 'block' : 'none';" style="cursor: pointer; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.7); z-index: 5;">
                    <!-- Mata Terbuka (Tampil saat hide = default) -->
                    <svg id="eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="display: block;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <!-- Mata Tertutup (Tampil saat text text) -->
                    <svg id="eye-hide" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    </svg>
                </span>
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

        <!-- CAPTCHA -->
        <div class="form-group">
            <label for="captcha" class="form-label" style="color: white;">Verifikasi CAPTCHA</label>
            <div class="captcha-box">
                <div class="captcha-question" id="captchaQuestion">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>{{ $captcha_question ?? '...' }}</span>
                </div>
                <a href="{{ route('login') }}" class="captcha-refresh" title="Ganti soal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
            <div class="input-group" style="margin-top:0.5rem;">
                <span class="input-icon" style="color: rgba(255, 255, 255, 0.7) !important; z-index: 5;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                </span>
                <input id="captcha" name="captcha" type="number" class="form-control"
                    placeholder="Masukkan jawaban" required autocomplete="off" style="color: white !important;">
            </div>
            <x-input-error :messages="$errors->get('captcha')" class="text-danger text-sm mt-1" />
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