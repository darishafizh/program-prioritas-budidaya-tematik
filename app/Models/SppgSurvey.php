<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashids;

class SppgSurvey extends Model
{
    use HasFactory, HasHashids;

    protected $fillable = [
        'user_id',
        // Verifikator & Responden
        'verifikator',
        'responden',
        'tempat',
        'tanggal',
        'jam',
        'koordinat',
        'jenis_kelamin',
        'umur',
        'pendidikan',
        'pekerjaan',
        'alamat',
        // Bagian A
        'nama_sppg',
        'kabupaten',
        'jumlah_sekolah',
        'jumlah_siswa',
        'porsi_harian',
        'porsi_bulanan',
        'kebutuhan_lele',
        'kebutuhan_nila',
        'kebutuhan_lain',
        'frekuensi_menu',
        'anggaran_porsi',
        'status_terpenuhi',
        'kekurangan',
        // Bagian B
        'jenis_ikan_prioritas',
        'jenis_ikan_lain',
        'ukuran_kondisi',
        'ukuran_detail',
        'standar_kualitas',
        'sertifikasi',
        'sumber_prioritas',
        // Bagian C
        'penting_jenis',
        'harga_jenis',
        'penting_ukuran',
        'harga_ukuran',
        'penting_kualitas',
        'harga_kualitas',
        'penting_sertifikasi',
        'harga_sertifikasi',
        'penting_sumber',
        'harga_sumber',
        // Bagian D
        'kendala_pasokan',
        'kendala_lain',
        'minat_kerjasama',
        'alasan_minat',
        'volume_kebutuhan',
        'jenis_ikan_dibutuhkan',
        'kontrak_status',
        'target_kontrak',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jenis_ikan_prioritas' => 'array',
        'ukuran_kondisi' => 'array',
        'standar_kualitas' => 'array',
        'sertifikasi' => 'array',
        'sumber_prioritas' => 'array',
        'kendala_pasokan' => 'array',
    ];

    /**
     * Get the user who created this survey
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total fish needs
     */
    public function getTotalKebutuhanAttribute()
    {
        return ($this->kebutuhan_lele ?? 0) + 
               ($this->kebutuhan_nila ?? 0) + 
               ($this->kebutuhan_lain ?? 0);
    }

    /**
     * Get average importance score
     */
    public function getAverageImportanceAttribute()
    {
        $scores = array_filter([
            $this->penting_jenis,
            $this->penting_ukuran,
            $this->penting_kualitas,
            $this->penting_sertifikasi,
            $this->penting_sumber,
        ], fn($v) => $v !== null);
        
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Check if interested in partnership
     */
    public function getIsInterestedAttribute()
    {
        return in_array($this->minat_kerjasama, ['Ya, sangat berminat', 'Ya, berminat']);
    }
}
