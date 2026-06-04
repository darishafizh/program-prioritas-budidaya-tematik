@extends('layouts.app')

@section('content')
    <!-- Page Header with Breadcrumb -->
    <div class="page-header-row mb-4">
        <div>
            <h1 class="page-title" style="color: var(--kkp-navy);">Kuesioner SPPG</h1>
            <p class="page-subtitle">Data Survei SPPG</p>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'SPPG']
        ]" />
    </div>



    <!-- Data Table -->
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body">

            {{-- Card Header: Title + Add Button --}}
            <div class="dt-card-header">
                <h4 class="dt-card-title">Daftar Kuesioner SPPG</h4>
                <a href="{{ route('sppg.create') }}" class="btn-tambah-data">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Data SPPG
                </a>
            </div>

            <div class="table-responsive">
                <table id="sppgTable" class="table table-hover w-100 table-sm align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" width="40">No</th>
                            <th>Nama SPPG</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surveys as $index => $survey)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $survey->nama_sppg ?? '-' }}</td>
                                <td>
                                    <div>{{ $survey->kabupaten ?? '-' }}</div>
                                    <div class="text-xs text-muted">{{ $survey->provinsi ?? '-' }}</div>
                                </td>
                                <td>{{ $survey->tanggal ? $survey->tanggal->format('d/m/Y') : '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('sppg.show', $survey) }}" class="table-action-btn-modern view">
                                            <i class="fa-solid fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('sppg.edit', $survey) }}" class="table-action-btn-modern">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <form id="delete-form-sppg-{{ $survey->hashid ?? $survey->id }}" action="{{ route('sppg.destroy', $survey) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="table-action-btn-modern danger" onclick="confirmDelete('delete-form-sppg-{{ $survey->hashid ?? $survey->id }}', 'Kuesioner')">
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
        /* Table modifications */
        table#sppgTable thead th {
            background: #ffffff !important;
            color: var(--gray-700) !important;
            border-bottom: 2px solid var(--gray-200) !important;
        }
        [data-theme="dark"] table#sppgTable thead th {
            background: #1f2937 !important;
            color: #e5e7eb !important;
            border-bottom: 2px solid #374151 !important;
        }
        .table-sm td, .table-sm th {
            padding: 0.75rem 0.75rem;
            vertical-align: middle;
        }

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
        .table-action-btn-modern.view:hover {
            border-color: #10B981;
            color: #10B981;
            background: rgba(16,185,129,0.04);
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
        [data-theme="dark"] .table-action-btn-modern.view:hover {
            border-color: #10B981;
            color: #10B981;
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sppgTable').DataTable({
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
                    { className: "text-center", targets: [0, 4] },
                    { orderable: false, targets: [4] }
                ]
        });
    </script>
@endpush