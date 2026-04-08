<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'kdmp_id',
        'user_id',
        'bulan',
        'tahun',
        'status_lokasi',
        'progres_fisik',
        'volume_panen_kg',
        'nilai_produksi',
        'biaya_operasional',
        'jumlah_pembudidaya_aktif',
        'kendala',
        'tindak_lanjut',
        'catatan',
        'foto',
    ];

    protected $casts = [
        'foto' => 'array',
        'volume_panen_kg' => 'decimal:2',
        'nilai_produksi' => 'decimal:2',
        'biaya_operasional' => 'decimal:2',
    ];

    // ==========================================
    // RELASI
    // ==========================================

    public function kdmp()
    {
        return $this->belongsTo(Kdmp::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==========================================
    // ACCESSORS / HELPERS
    // ==========================================

    /**
     * Label status lokasi untuk tampilan
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_lokasi) {
            'on_track' => 'On Track',
            'bermasalah' => 'Bermasalah',
            'selesai' => 'Selesai',
            'vakum' => 'Vakum',
            default => '-',
        };
    }

    /**
     * Warna badge status lokasi
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status_lokasi) {
            'on_track' => 'success',
            'bermasalah' => 'danger',
            'selesai' => 'primary',
            'vakum' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Icon status lokasi
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status_lokasi) {
            'on_track' => '<i class="fa-solid fa-circle-check"></i>',
            'bermasalah' => '<i class="fa-solid fa-circle-xmark"></i>',
            'selesai' => '<i class="fa-solid fa-circle-check"></i>',
            'vakum' => '<i class="fa-solid fa-circle-exclamation"></i>',
            default => '<i class="fa-solid fa-circle"></i>',
        };
    }

    /**
     * Nama bulan dalam Bahasa Indonesia
     */
    public function getBulanLabelAttribute(): string
    {
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        return $bulanList[$this->bulan] ?? '-';
    }

    /**
     * Label periode: "Januari 2026"
     */
    public function getPeriodeLabelAttribute(): string
    {
        return $this->bulan_label . ' ' . $this->tahun;
    }

    /**
     * Profit (nilai produksi - biaya operasional)
     */
    public function getProfitAttribute(): float
    {
        return (float) $this->nilai_produksi - (float) $this->biaya_operasional;
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status_lokasi', $status);
    }

    public function scopeByPeriode($query, int $bulan, int $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    public function scopeByTahun($query, int $tahun)
    {
        return $query->where('tahun', $tahun);
    }
}
