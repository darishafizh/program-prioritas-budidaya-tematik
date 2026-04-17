@extends('layouts.app')

@section('content')
    <!-- Page Header with Breadcrumb -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Manajemen User</h1>
            <p class="page-subtitle">Kelola pengguna sistem</p>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'Users', 'url' => route('users.index')]
        ]" />
    </div>



    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success mb-4">
            <div class="alert-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            <div class="alert-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            {{ session('error') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">

            <!-- Toolbar: Add Button -->
            <div class="d-flex justify-content-end mb-3">
                 <a href="{{ route('users.create') }}" class="btn btn-sm btn-success d-flex align-items-center gap-2 rounded-pill px-4 shadow-sm">
                    <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="fw-bold">Tambah User</span>
                </a>
            </div>

            <div class="table-responsive">
            <table id="usersTable" class="table display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        style="width:36px;height:36px;background:linear-gradient(135deg,{{ $user->role == 'admin' ? '#0D2137,#164E63' : '#0891B2,#06B6D4' }});border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.875rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge badge-navy">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Administrator
                                    </span>
                                @else
                                    <span class="badge badge-teal">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Verifikator
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('users.edit', $user) }}" class="table-action-btn" style="color:#3B82F6;"
                                        title="Edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;"
                                            onsubmit="return confirm('Hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="table-action-btn delete" title="Hapus">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                language: {
                    search: "Cari:", lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data", infoFiltered: "(filter dari _MAX_ data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
                },
                pageLength: 10, order: [[0, 'asc']], columnDefs: [{ orderable: false, targets: [4] }]
            });
        });
    </script>
@endpush