@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb - Aligned -->
<div class="page-header-row mb-4">
    <div>
        <h1 class="page-title" style="color: var(--kkp-navy);">Data KDMP</h1>
        <p class="page-subtitle">Data Lokasi Koperasi Desa Merah Putih</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'KDMP']
    ]" />
</div>

<!-- Data Table Card -->
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">
        
        {{-- Card Header: Title + Add Button --}}
        <div class="dt-card-header">
            <h4 class="dt-card-title">Daftar KDMP</h4>
            <a href="{{ route('kdmp.create') }}" class="btn-tambah-data">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Data KDMP
            </a>
        </div>

        <div class="table-responsive">
        <table id="kdmpTable" class="table table-hover w-100 table-sm align-middle">
            <thead>
                <tr>
                    <th class="text-center" width="40">No</th>
                    <th>Provinsi</th>
                    <th>Kabupaten</th>
                    <th>Desa/Kelurahan</th>
                    <th>Nama KDKMP</th>
                    <th>Komoditas</th>
                    <th>Ketua/Anggota</th>
                    <th>Penyuluh</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kdmpLocations as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->provinsi ?? '-' }}</td>
                    <td>{{ $item->kabupaten ?? '-' }}</td>
                    <td>{{ $item->desa ?? '-' }}</td>
                    <td class="fw-bold">{{ $item->nama_kdkmp ?? '-' }}</td>
                    <td>
                        <span class="badge {{ strtolower($item->komoditas ?? '') == 'lele' ? 'bg-info text-dark' : 'bg-success' }} rounded-pill px-2 py-1 user-select-none" style="font-size: 0.75rem;">
                            {{ $item->komoditas ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $item->ketua_anggota ?? '-' }}</div>
                        @if($item->no_hp)
                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0{{ ltrim((string)$item->no_hp, '0') }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ $item->nama_penyuluh ?? '-' }}</div>
                        @if($item->no_hp_penyuluh)
                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                             <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0{{ ltrim((string)$item->no_hp_penyuluh, '0') }}
                        </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="{{ route('kdmp.edit', $item) }}" class="table-action-btn-modern">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form id="delete-form-kdmp-{{ $item->hashid ?? $item->id }}" action="{{ route('kdmp.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="table-action-btn-modern danger" onclick="confirmDelete('delete-form-kdmp-{{ $item->hashid ?? $item->id }}', 'Data KDMP')">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
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
<style>
    /* Use global font */
    
    /* Table modifications */
    table#kdmpTable thead th {
        background: #ffffff !important;
        color: var(--gray-700) !important;
        border-bottom: 2px solid var(--gray-200) !important;
    }
    [data-theme="dark"] table#kdmpTable thead th {
        background: #1f2937 !important;
        color: #e5e7eb !important;
        border-bottom: 2px solid #374151 !important;
    }
    .table-sm td, .table-sm th {
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
    }
    
    /* Custom Badge Colors to match theme */
    .bg-info { background-color: var(--kkp-cyan) !important; }
    .bg-success { background-color: var(--kkp-success) !important; }

    /* Modern Table Action Buttons */
    .table-action-btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid var(--border-color);
        background: var(--bg-surface);
        color: var(--gray-600);
        cursor: pointer;
        transition: all 180ms ease;
    }
    .table-action-btn-modern:hover {
        border-color: #3B82F6;
        color: #3B82F6;
        background: rgba(59,130,246,0.04);
    }
    .table-action-btn-modern.danger {
        border: none;
        background: rgba(239,68,68,0.06);
        color: #DC2626;
    }
    .table-action-btn-modern.danger:hover {
        background: rgba(239,68,68,0.12);
        color: #DC2626;
    }
    [data-theme="dark"] .table-action-btn-modern {
        background: #1F2937;
        border-color: #374151;
        color: #9CA3AF;
    }
    [data-theme="dark"] .table-action-btn-modern:hover {
        border-color: #3B82F6;
        color: #3B82F6;
    }
    [data-theme="dark"] .table-action-btn-modern.danger {
        background: rgba(239,68,68,0.08);
        border: none;
        color: #DC2626;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#kdmpTable').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        pageLength: 10,
        dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
        order: [[0, 'asc']],
        columnDefs: [
            { className: "text-center", targets: [0, 8] },
            { className: "text-center", targets: [5] },
            { orderable: false, targets: [8] }
        ]
    });
});
</script>
@endpush
