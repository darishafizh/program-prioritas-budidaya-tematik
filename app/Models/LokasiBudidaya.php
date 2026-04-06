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
        'lokasi',
        'volume_hasil_panen',
        'nilai_hasil_panen',
        'biaya_operasional',
        'harga_jual_per_kg',
    ];

    protected $casts = [
        'volume_hasil_panen' => 'decimal:2',
        'nilai_hasil_panen' => 'decimal:2',
        'biaya_operasional' => 'decimal:2',
        'harga_jual_per_kg' => 'decimal:2',
    ];
}
