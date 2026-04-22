@extends('layouts.app')

@section('content')
    <!-- Page Header with Breadcrumb -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">{{ $user->name }}</p>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'Users', 'url' => route('users.index')],
            ['label' => 'Edit', 'url' => '#']
        ]" />
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="section-card">
            <div class="section-header">
                <div class="section-icon teal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="section-title">Informasi User</h3>
            </div>
            <div class="section-body">
                <div class="grid grid-cols-2">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Username <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                        <small class="text-muted">Username akan digunakan untuk login</small>
                        @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                <div class="form-group" style="grid-column: span 2;">
                    <div class="password-info-box">
                        <i class="fa-solid fa-circle-info" style="color: var(--kkp-teal); margin-right: 0.25rem;"></i>
                        <strong>Kriteria Keamanan Password:</strong> Jika ingin mengubah password, password baru wajib memiliki minimal 8 karakter, mengandung kombinasi huruf besar (A-Z), huruf kecil (a-z), angka (0-9), dan karakter spesial (seperti @, #, !). Karakter bersifat *case-sensitive* (huruf kapital berpengaruh).
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru <span class="text-muted">(kosongkan jika tidak diubah)</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password baru" style="padding-right: 40px;">
                        <span onclick="var p = document.getElementById('password'); var isP = p.type === 'password'; p.type = isP ? 'text' : 'password'; document.getElementById('pw-eye-show').style.display = isP ? 'none' : 'block'; document.getElementById('pw-eye-hide').style.display = isP ? 'block' : 'none';" style="cursor: pointer; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400);">
                            <i id="pw-eye-show" class="fa-solid fa-eye" style="display: block;"></i>
                            <i id="pw-eye-hide" class="fa-solid fa-eye-slash" style="display: none;"></i>
                        </span>
                    </div>
                    @error('password')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru" style="padding-right: 40px;">
                        <span onclick="var p = document.getElementById('password_confirmation'); var isP = p.type === 'password'; p.type = isP ? 'text' : 'password'; document.getElementById('pwc-eye-show').style.display = isP ? 'none' : 'block'; document.getElementById('pwc-eye-hide').style.display = isP ? 'block' : 'none';" style="cursor: pointer; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-400);">
                            <i id="pwc-eye-show" class="fa-solid fa-eye" style="display: block;"></i>
                            <i id="pwc-eye-hide" class="fa-solid fa-eye-slash" style="display: none;"></i>
                        </span>
                    </div>
                </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Role <span class="required">*</span></label>
                        <div class="flex flex-wrap gap-4 mt-2">
                            <label class="form-check"
                                style="flex:1; justify-content:center; padding:1rem; border:2px solid {{ $user->role == 'admin' ? 'var(--kkp-navy)' : 'var(--gray-200)' }}; cursor:pointer;">
                                <input type="radio" name="role" value="admin" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }} required class="form-check-input">
                                <div style="text-align:center;">
                                    <div class="font-semibold" style="color:var(--kkp-navy);">Administrator</div>
                                    <div class="text-xs text-muted">Akses penuh ke semua fitur</div>
                                </div>
                            </label>
                            <label class="form-check"
                                style="flex:1; justify-content:center; padding:1rem; border:2px solid {{ $user->role == 'verifikator' ? 'var(--kkp-teal)' : 'var(--gray-200)' }}; cursor:pointer;">
                                <input type="radio" name="role" value="verifikator" {{ old('role', $user->role) == 'verifikator' ? 'checked' : '' }} class="form-check-input">
                                <div style="text-align:center;">
                                    <div class="font-semibold" style="color:var(--kkp-teal);">Verifikator</div>
                                    <div class="text-xs text-muted">Akses verifikasi kuesioner</div>
                                </div>
                            </label>
                        </div>
                        @error('role')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update User
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('styles')
<style>
    .password-info-box {
        font-size: 0.8rem;
        background: rgba(8,145,178,0.05);
        color: var(--kkp-navy);
        border: 1px solid rgba(8,145,178,0.2);
        padding: 0.75rem;
        border-radius: 8px;
    }
    
    [data-theme="dark"] .password-info-box {
        background: rgba(8,145,178,0.15);
        color: #E5E7EB;
        border-color: rgba(8,145,178,0.3);
    }
</style>
@endpush