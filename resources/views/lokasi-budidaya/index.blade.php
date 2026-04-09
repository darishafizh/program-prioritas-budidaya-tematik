@extends('layouts.app')

@section('content')
    <!-- Page Header with Breadcrumb -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Lokasi Budidaya</h1>
            <p class="page-subtitle">Monitoring data lokasi budidaya</p>
        </div>
        <x-breadcrumb :items="[
            ['label' => 'Lokasi Budidaya', 'url' => route('lokasi-budidaya.index')]
        ]" />
    </div>

    <!-- Page Actions -->
    <div class="page-header">
        <div class="page-header-content">
            <div></div>
            <a href="{{ route('lokasi-budidaya.create') }}" class="btn btn-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Data
            </a>
        </div>
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

    <!-- Data Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="lokasiTable" class="table display" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">No</th>
                            <th rowspan="2" style="vertical-align: middle;">KDKMP</th>
                            <th colspan="2" class="text-center">Hasil Panen</th>
                            <th rowspan="2" class="text-end" style="vertical-align: middle;">Biaya Opr</th>
                            <th rowspan="2" class="text-end" style="vertical-align: middle;">Harga Jual</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-end">Volume (kg)</th>
                            <th class="text-end">Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="font-medium">{{ $item->nama_koperasi }}</div>
                                    <div class="text-muted" style="font-size: 0.8em;">{{ $item->lokasi }}</div>
                                </td>
                                <td class="text-end">{{ number_format($item->volume_hasil_panen, 2, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item->nilai_hasil_panen, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item->biaya_operasional, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item->harga_jual_per_kg, 0, ',', '.') }}</td>
                                <td>
                                    <div class="table-actions justify-center">
                                        <a href="{{ route('lokasi-budidaya.edit', $item) }}" class="table-action-btn edit"
                                            title="Edit">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('lokasi-budidaya.destroy', $item) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="table-action-btn delete" title="Hapus">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        /* Override theme styles — light header with dark text */
        table.dataTable thead th,
        table.dataTable thead td,
        .table thead th,
        .table thead td {
            background: #f8f9fa !important;
            color: #333 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        table.dataTable thead .sorting::before,
        table.dataTable thead .sorting::after,
        table.dataTable thead .sorting_asc::before,
        table.dataTable thead .sorting_asc::after,
        table.dataTable thead .sorting_desc::before,
        table.dataTable thead .sorting_desc::after {
            color: #666 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#lokasiTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(filter dari _MAX_ data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: { first: "<<", last: ">>", next: ">", previous: "<" }
                },
                pageLength: 10,
                orderCellsTop: true,
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [6] }]
            });
        });
    </script>
@endpush