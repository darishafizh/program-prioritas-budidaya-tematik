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
        
        <!-- Toolbar: Add Button -->
        <div class="d-flex justify-content-end mb-3">
             <a href="{{ route('kdmp.create') }}" class="btn btn-sm btn-success d-flex align-items-center gap-2 rounded-pill px-4 shadow-sm">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="fw-bold">Tambah Kuesioner</span>
            </a>
        </div>

        <div class="table-responsive">
        <table id="kdmpTable" class="table table-hover w-100 table-sm align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="text-center" width="40">No</th>
                    <th>Provinsi</th>
                    <th>Kabupaten</th>
                    <th>Desa/Kelurahan</th>
                    <th>Nama KDKMP</th>
                    <th>Komoditas</th>
                    <th>Ketua/Anggota</th>
                    <th>Penyuluh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kdmpLocations as $item)
                <tr>
                    <td class="text-center">{{ $item->no ?? $loop->iteration }}</td>
                    <td>{{ $item->provinsi ?? '-' }}</td>
                    <td>{{ $item->kabupaten ?? '-' }}</td>
                    <td>{{ $item->desa ?? '-' }}</td>
                    <td class="fw-bold text-dark">{{ $item->nama_kdkmp ?? '-' }}</td>
                    <td>
                        <span class="badge {{ strtolower($item->komoditas ?? '') == 'lele' ? 'bg-info text-dark' : 'bg-success' }} rounded-pill px-2 py-1 user-select-none" style="font-size: 0.75rem;">
                            {{ $item->komoditas ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->ketua_anggota ?? '-' }}</div>
                        @if($item->no_hp)
                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $item->no_hp }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->nama_penyuluh ?? '-' }}</div>
                        @if($item->no_hp_penyuluh)
                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                             <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $item->no_hp_penyuluh }}
                        </div>
                        @endif
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
    /* Ensure Manrope font is used */
    body, .page-title, .table, .btn {
        font-family: 'Manrope', sans-serif;
    }
    
    /* Table modifications */
    .table-sm td, .table-sm th {
        padding: 0.75rem 0.75rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }
    
    /* Custom Badge Colors to match theme */
    .bg-info { background-color: var(--kkp-cyan) !important; }
    .bg-success { background-color: var(--kkp-success) !important; }
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
            lengthMenu: "Tampilkan _MENU_ Data", 
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
        },
        pageLength: 25,
        dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
        order: [[0, 'asc']],
        columnDefs: [
            { className: "text-center", targets: [0] },
            { className: "text-center", targets: [5] }
        ]
    });
});
</script>
@endpush
