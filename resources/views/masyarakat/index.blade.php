@extends('layouts.app')

@section('content')
<!-- Page Header with Breadcrumb -->
<div class="page-header-row">
    <div>
        <h1 class="page-title">Kuesioner Masyarakat</h1>
        <p class="page-subtitle">Data Survei Masyarakat Pembudidaya</p>
    </div>
    <x-breadcrumb :items="[
        ['label' => 'Masyarakat', 'url' => route('masyarakat.index')]
    ]" />
</div>


<!-- Data Table -->
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">

        <!-- Toolbar: Add Button -->
        <div class="d-flex justify-content-end mb-3">
             <a href="{{ route('masyarakat.create') }}" class="btn btn-sm btn-success d-flex align-items-center gap-2 rounded-pill px-4 shadow-sm">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="fw-bold">Tambah Kuesioner</span>
            </a>
        </div>

        <div class="table-responsive">
        <table id="masyarakatTable" class="table display" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Responden</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surveys as $index => $survey)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><div class="font-medium">{{ $survey->nama_responden ?? '-' }}</div></td>
                    <td>
                        <div>{{ $survey->kabupaten ?? '-' }}</div>
                        <div class="text-xs text-muted">{{ $survey->provinsi ?? '-' }}</div>
                    </td>
                    <td>{{ $survey->tanggal ? $survey->tanggal->format('d/m/Y') : '-' }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('masyarakat.show', $survey) }}" class="table-action-btn view" title="Lihat">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('masyarakat.edit', $survey) }}" class="table-action-btn" style="color:#3B82F6;" title="Edit">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('masyarakat.destroy', $survey) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="table-action-btn delete" title="Hapus">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
$(document).ready(function() {
    $('#masyarakatTable').DataTable({
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
