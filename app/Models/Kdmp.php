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
        'long',
        'lat',
    ];

    /**
     * Kuesioner KDMP yang terhubung ke lokasi ini
     */
    public function kdmpSurveys()
    {
        return $this->hasMany(KdmpSurvey::class);
    }

    /**
     * Skor kelayakan yang terhubung ke lokasi ini
     */
    public function locationScores()
    {
        return $this->hasMany(LocationScore::class);
    }

    /**
     * Laporan monitoring periodik lokasi ini
     */
    public function monitoringRecords()
    {
        return $this->hasMany(MonitoringRecord::class);
    }
}
