<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kdmp extends Model
{
    use HasFactory;

    protected $table = 'kdmp';

    protected $fillable = [
        'no',
        'provinsi',
        'kabupaten',
        'desa',
        'nama_kdkmp',
        'komoditas',
        'ketua_anggota',
        'no_hp',
        'nama_penyuluh',
        'no_hp_penyuluh',
    ];
}
