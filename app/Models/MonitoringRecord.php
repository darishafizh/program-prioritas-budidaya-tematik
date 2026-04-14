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
        'survival_rate',
        'jumlah_kolam_aktif',
        'jumlah_kolam_total',
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
        'survival_rate' => 'decimal:2',
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
    // KPI COMPUTED ACCESSORS
    // ==========================================

    /**
     * Biaya per kg produksi
     */
    public function getBiayaPerKgAttribute(): ?float
    {
        if (!$this->volume_panen_kg || (float) $this->volume_panen_kg == 0) return null;
        return (float) $this->biaya_operasional / (float) $this->volume_panen_kg;
    }

    /**
     * Utilisasi kolam (%)
     */
    public function getUtilisasiKolamAttribute(): ?float
    {
        if (!$this->jumlah_kolam_total || $this->jumlah_kolam_total == 0) return null;
        return round(($this->jumlah_kolam_aktif / $this->jumlah_kolam_total) * 100, 1);
    }

    /**
     * Produksi per kolam aktif
     */
    public function getProduksiPerKolamAttribute(): ?float
    {
        if (!$this->jumlah_kolam_aktif || $this->jumlah_kolam_aktif == 0) return null;
        return round((float) $this->volume_panen_kg / $this->jumlah_kolam_aktif, 2);
    }

    /**
     * Status SR → warna (danger/warning/success)
     */
    public function getSrStatusAttribute(): string
    {
        if ($this->survival_rate === null) return 'secondary';
        if ($this->survival_rate < 70) return 'danger';
        if ($this->survival_rate <= 80) return 'warning';
        return 'success';
    }

    /**
     * Apakah lokasi ini prioritas intervensi?
     * SR rendah + produksi rendah + ada kendala
     */
    public function getIsPrioritasAttribute(): bool
    {
        return $this->survival_rate !== null
            && $this->survival_rate < 70
            && (float) $this->volume_panen_kg < 100
            && !empty($this->kendala);
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
