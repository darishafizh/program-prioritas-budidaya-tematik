<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashids;

class MonitoringRecord extends Model
{
    use HasFactory, HasHashids;

    protected $table = 'monitoring_produksi';

    protected $fillable = [
        'kdmp_id',
        'user_id',
        'tanggal',
        'status_lokasi',
        'progres_fisik',
        'volume_panen_kg',
        'tujuan_pasar',
        'dokumentasi',
        'nilai_produksi',
        'biaya_pakan',
        'biaya_bibit',
        'biaya_lainnya',
        'biaya_operasional',
        'jumlah_pembudidaya_aktif',
        'survival_rate',
        'fcr',
        'jumlah_kolam_aktif',
        'jumlah_kolam_total',
        'kendala',
        'tindak_lanjut',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'volume_panen_kg' => 'decimal:2',
        'nilai_produksi' => 'decimal:2',
        'biaya_pakan' => 'decimal:2',
        'biaya_bibit' => 'decimal:2',
        'biaya_lainnya' => 'decimal:2',
        'biaya_operasional' => 'decimal:2',
        'survival_rate' => 'decimal:2',
        'fcr' => 'decimal:2',
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
        $keuntungan = (float) $this->nilai_produksi - (float) $this->biaya_operasional;
        return $keuntungan >= 15000000 ? 'On Track' : 'Underperform';
    }

    /**
     * Warna badge status lokasi
     */
    public function getStatusColorAttribute(): string
    {
        $keuntungan = (float) $this->nilai_produksi - (float) $this->biaya_operasional;
        return $keuntungan >= 15000000 ? 'success' : 'danger';
    }

    /**
     * Icon status lokasi
     */
    public function getStatusIconAttribute(): string
    {
        $keuntungan = (float) $this->nilai_produksi - (float) $this->biaya_operasional;
        return $keuntungan >= 15000000 ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>';
    }

    /**
     * Nama bulan dalam Bahasa Indonesia (derived from tanggal)
     */
    public function getBulanLabelAttribute(): string
    {
        if (!$this->tanggal) return '-';
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
        return $bulanList[$this->tanggal->month] ?? '-';
    }

    /**
     * Label periode: "Januari 2026" (derived from tanggal)
     */
    public function getPeriodeLabelAttribute(): string
    {
        if (!$this->tanggal) return '-';
        return $this->bulan_label . ' ' . $this->tanggal->year;
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
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    public function scopeByTahun($query, int $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }
}
