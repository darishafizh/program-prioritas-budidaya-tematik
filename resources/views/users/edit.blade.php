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
                    <div class="form-group">
                        <label class="form-label">Password Baru <span class="text-muted">(kosongkan jika tidak
                                diubah)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                        @error('password')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Role <span class="required">*</span></label>
                        <div class="flex gap-4 mt-2">
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