<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresFisikRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'kdmp_id',
        'user_id',
        'bulan',
        'tahun',
        'progres_bangunan',
        'progres_kolam',
        'progres_listrik',
        'progres_air',
        'progres_aerasi',
        'kendala',
        'tindak_lanjut',
        'catatan',
        'foto_sebelum',
        'foto_sesudah',
    ];

    protected $casts = [
        'foto_sebelum' => 'array',
        'foto_sesudah' => 'array',
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
    // ACCESSORS
    // ==========================================

    /**
     * Rata-rata progres seluruh komponen
     */
    public function getAverageProgressAttribute(): float
    {
        $values = [
            $this->progres_bangunan,
            $this->progres_kolam,
            $this->progres_listrik,
            $this->progres_air,
            $this->progres_aerasi,
        ];

        return round(array_sum($values) / count($values), 1);
    }

    /**
     * Nama bulan dalam Bahasa Indonesia
     */
    public function getBulanLabelAttribute(): string
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
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
     * Status progres berdasarkan rata-rata
     */
    public function getStatusAttribute(): string
    {
        $avg = $this->average_progress;
        if ($avg >= 100) return 'selesai';
        if ($avg >= 50) return 'berjalan';
        if ($avg > 0) return 'awal';
        return 'belum';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'Selesai',
            'berjalan' => 'Berjalan',
            'awal' => 'Tahap Awal',
            'belum' => 'Belum Mulai',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'success',
            'berjalan' => 'primary',
            'awal' => 'warning',
            'belum' => 'secondary',
            default => 'secondary',
        };
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeByPeriode($query, int $bulan, int $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }
}
