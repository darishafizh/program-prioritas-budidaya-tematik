<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiBudidaya extends Model
{
    use HasFactory;

    protected $table = 'lokasi_budidaya';

    protected $fillable = [
        'nama_koperasi',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'desa',
        'volume',
        'hasil_panen_kg',
        'nilai_hasil_panen',
        'biaya_operasional',
        'harga_jual_per_kg',
    ];

    protected $casts = [
        'volume'          => 'decimal:2',
        'hasil_panen_kg'  => 'decimal:2',
        'nilai_hasil_panen' => 'decimal:2',
        'biaya_operasional' => 'decimal:2',
        'harga_jual_per_kg' => 'decimal:2',
    ];

    /**
     * Mengembalikan string lokasi lengkap (Provinsi, Kab/Kota, Kecamatan, Desa)
     */
    public function getLokasiLengkapAttribute(): string
    {
        return collect([
            $this->provinsi,
            $this->kabupaten_kota,
            $this->kecamatan,
            $this->desa,
        ])->filter()->implode(', ');
    }
}
