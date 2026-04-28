<?php

namespace App\Exports;

use App\Models\Kdmp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProduksiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tahun;
    protected $bulan;
    protected $search;

    public function __construct($tahun, $bulan, $search)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->search = $search;
    }

    public function collection()
    {
        // Ambil KDMP beserta record yang sesuai dengan filter tahun dan bulan
        // Karena kita mau mengekspor data KDMP dengan status terakhir di periode tersebut, 
        // maka mirip dengan filter di index.
        $query = Kdmp::with([
            'monitoringRecords' => function($q) {
                // Di sini kita tidak hard filter by $tahun/$bulan karena kita ingin record terbaru, 
                // tapi kalau user mau excel dari filter yg ada, bisa saja ambil yg difilter.
                // Jika tidak ada parameter tahun/bulan, kita hanya ambil order terbaru.
                $q->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');
            },
        ]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_kdkmp', 'like', "%{$this->search}%")
                    ->orWhere('kabupaten', 'like', "%{$this->search}%")
                    ->orWhere('provinsi', 'like', "%{$this->search}%");
            });
        }

        return $query->orderBy('no')->get();
    }

    public function map($kdmp): array
    {
        // Ambil laporan terakhir yang tersedia
        $lastRecord = $kdmp->monitoringRecords->first();
        
        $alamat = implode(', ', array_filter([$kdmp->desa, $kdmp->kabupaten, $kdmp->provinsi])) ?: '-';
        $ketua = $kdmp->ketua_anggota ?? '-';
        if ($kdmp->no_hp) $ketua .= ' (' . $kdmp->no_hp . ')';
        
        $penyuluh = $kdmp->nama_penyuluh ?? '-';
        if ($kdmp->no_hp_penyuluh) $penyuluh .= ' (' . $kdmp->no_hp_penyuluh . ')';

        $periode = '-';
        $status = '-';
        $volumePanen = '-';
        $nilaiProduksi = '-';
        $biayaOperasional = '-';
        $keuntungan = '-';
        $sr = '-';
        $kolamAktif = '-';
        $kolamTotal = '-';
        $pembudidaya = '-';
        $kendala = '-';
        $tindakLanjut = '-';
        $catatan = '-';

        if ($lastRecord) {
            $periode = $lastRecord->periode_label ?? ($lastRecord->bulan . ' ' . $lastRecord->tahun);
            $status = $lastRecord->status_label ?? $lastRecord->status_lokasi;
            $volumePanen = $lastRecord->volume_panen_kg;
            $nilaiProduksi = $lastRecord->nilai_produksi;
            $biayaOperasional = $lastRecord->biaya_operasional;
            $keuntungan = (float)$lastRecord->nilai_produksi - (float)$lastRecord->biaya_operasional;
            $sr = $lastRecord->survival_rate !== null ? $lastRecord->survival_rate . '%' : '-';
            $kolamAktif = $lastRecord->jumlah_kolam_aktif ?? '-';
            $kolamTotal = $lastRecord->jumlah_kolam_total ?? '-';
            $pembudidaya = $lastRecord->jumlah_pembudidaya_aktif ?? '-';
            $kendala = $lastRecord->kendala ?? '-';
            $tindakLanjut = $lastRecord->tindak_lanjut ?? '-';
            $catatan = $lastRecord->catatan ?? '-';
        }

        return [
            $kdmp->no,
            $kdmp->nama_kdkmp,
            $alamat,
            $kdmp->komoditas ?? '-',
            $ketua,
            $penyuluh,
            $periode,
            $status,
            $volumePanen,
            $nilaiProduksi,
            $biayaOperasional,
            $keuntungan,
            $sr,
            $kolamAktif,
            $kolamTotal,
            $pembudidaya,
            $kendala,
            $tindakLanjut,
            $catatan,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama KDKMP',
            'Alamat',
            'Komoditas',
            'Ketua / Anggota (Telp)',
            'Penyuluh (Telp)',
            'Periode Laporan Terakhir',
            'Status Lokasi',
            'Volume Panen (kg)',
            'Nilai Produksi (Rp)',
            'Biaya Operasional (Rp)',
            'Keuntungan (Rp)',
            'Survival Rate (%)',
            'Kolam Aktif',
            'Kolam Total',
            'Pembudidaya Aktif',
            'Kendala',
            'Tindak Lanjut',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
