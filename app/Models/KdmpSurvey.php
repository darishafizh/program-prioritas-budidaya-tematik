<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KdmpSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kdmp_id',
        // Verifikator & Responden
        'verifikator',
        'responden',
        'tempat',
        'tanggal',
        'jam',
        'jenis_kelamin',
        'umur',
        'pendidikan',
        'pekerjaan',
        'alamat',
        // Bagian A
        'nama_koperasi',
        'nomor_badan_hukum',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'luas_lahan',
        'jumlah_paket',
        'komoditas',
        'koordinat',
        'krit_badan_hukum_kbli',
        'kbli_number',
        'krit_ekusuka',
        'krit_jkn_aktif',
        'krit_proposal',
        'krit_kesanggupan',
        'krit_belum_bantuan',
        'krit_pelatihan',
        'pelatihan_hari',
        'krit_kelola_koperasi',
        'hambatan_koperasi',
        'catatan_a',
        // Bagian B
        'jumlah_pembudidaya',
        'jumlah_pokdakan',
        'komoditas_terbanyak',
        'produksi_ikan',
        'konsumsi_perkapita',
        'ikan_pasar',
        'catatan_b',
        // Bagian C
        'koordinat_gps',
        'lahan_datar',
        'lahan_legalitas',
        'lahan_sumber_air',
        'lahan_listrik',
        'lahan_akses',
        'lahan_jauh_pencemar',
        'lahan_prasarana',
        'inst_bak_terpal',
        'inst_lantai',
        'inst_air',
        'inst_listrik',
        'inst_aerasi',
        'inst_atap',
        'inst_peralatan',
        'inst_ipal',
        'progres_bangunan',
        'progres_kolam',
        'progres_listrik',
        'progres_air',
        'progres_aerasi',
        'tk_laki',
        'tk_perempuan',
        'upah_harian',
        'jam_kerja',
        'tk_lokal',
        'tk_luar',
        'kendala_pembangunan',
        'sop_status',
        'sop_kendala',
        // Bagian D
        'responden_pengurus',
        'koperasi_d',
        'tujuan_penjualan',
        'tujuan_lain',
        'pj_operasional',
        'modal_tersedia',
        'strategi_antisipasi',
        'kendala_pemasaran',
        'rencana_antisipasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'hambatan_koperasi' => 'array',
        'kendala_pembangunan' => 'array',
        'tujuan_penjualan' => 'array',
        'krit_badan_hukum_kbli' => 'boolean',
        'krit_ekusuka' => 'boolean',
        'krit_jkn_aktif' => 'boolean',
        'krit_proposal' => 'boolean',
        'krit_kesanggupan' => 'boolean',
        'krit_belum_bantuan' => 'boolean',
        'krit_pelatihan' => 'boolean',
        'krit_kelola_koperasi' => 'boolean',
        'lahan_datar' => 'boolean',
        'lahan_legalitas' => 'boolean',
        'lahan_sumber_air' => 'boolean',
        'lahan_listrik' => 'boolean',
        'lahan_akses' => 'boolean',
        'lahan_jauh_pencemar' => 'boolean',
        'lahan_prasarana' => 'boolean',
        'inst_bak_terpal' => 'boolean',
        'inst_lantai' => 'boolean',
        'inst_air' => 'boolean',
        'inst_listrik' => 'boolean',
        'inst_aerasi' => 'boolean',
        'inst_atap' => 'boolean',
        'inst_peralatan' => 'boolean',
        'inst_ipal' => 'boolean',
    ];

    /**
     * Get the master KDMP data linked to this survey
     */
    public function kdmp()
    {
        return $this->belongsTo(Kdmp::class);
    }

    /**
     * Get the user who created this survey
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get average progress
     */
    public function getAverageProgressAttribute()
    {
        $progresses = [
            $this->progres_bangunan ?? 0,
            $this->progres_kolam ?? 0,
            $this->progres_listrik ?? 0,
            $this->progres_air ?? 0,
            $this->progres_aerasi ?? 0,
        ];
        
        return round(array_sum($progresses) / count($progresses), 1);
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesArrayAttribute()
    {
        if (!$this->koordinat) {
            return null;
        }
        
        $parts = explode(',', $this->koordinat);
        if (count($parts) !== 2) {
            return null;
        }
        
        return [
            'lat' => trim($parts[0]),
            'lng' => trim($parts[1]),
        ];
    }

    /**
     * Scope for filtering by province
     */
    public function scopeByProvince($query, $province)
    {
        return $query->where('provinsi', $province);
    }

    /**
     * Scope for filtering by commodity
     */
    public function scopeByCommodity($query, $commodity)
    {
        return $query->where('komoditas', $commodity);
    }
}
