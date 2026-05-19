<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashids;

class MasyarakatSurvey extends Model
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
        'sesuai_kebutuhan',
        'item_tidak_sesuai',
        'perasaan',
        'alasan',
        'harapan',
        'saran',
        // Bagian B - Likert
        'likert_q1',
        'likert_q2',
        'likert_q3',
        'likert_q4',
        'likert_q5',
        'likert_q6',
        'likert_q7',
        'likert_q8',
        'likert_q9',
        'likert_q10',
        'likert_q11',
        'likert_q12',
        'likert_q13',
        'likert_q14',
        'likert_q15',
        // Bagian C
        'pendapatan_ikan',
        'pendapatan_lain',
        'total_pendapatan',
        'kontribusi',
        'jumlah_sumber',
        'ketergantungan',
        'stabilitas',
        'peran_perempuan',
        'kontribusi_perempuan',
        // Bagian D
        'anggota_kub',
        'manfaat_kub',
        'anggota_koperasi',
        'tertarik_koperasi',
        'manfaat_koperasi',
        'kop_rapat_tahunan',
        'kop_aktif_partisipasi',
        'kop_pengurus_kompeten',
        'kop_transparan',
        'kop_keuangan_sehat',
        'kop_jaringan_luas',
        'kop_percaya_profesional',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'kop_rapat_tahunan' => 'boolean',
        'kop_aktif_partisipasi' => 'boolean',
        'kop_pengurus_kompeten' => 'boolean',
        'kop_transparan' => 'boolean',
        'kop_keuangan_sehat' => 'boolean',
        'kop_jaringan_luas' => 'boolean',
        'kop_percaya_profesional' => 'boolean',
    ];

    /**
     * Get the user who created this survey
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get average Likert score for safety section (Q1-Q5)
     */
    public function getSafetyScoreAttribute()
    {
        $scores = array_filter([
            $this->likert_q1,
            $this->likert_q2,
            $this->likert_q3,
            $this->likert_q4,
            $this->likert_q5,
        ], fn($v) => $v !== null);
        
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Get average Likert score for social-economic section (Q6-Q10)
     */
    public function getSocialEconomicScoreAttribute()
    {
        $scores = array_filter([
            $this->likert_q6,
            $this->likert_q7,
            $this->likert_q8,
            $this->likert_q9,
            $this->likert_q10,
        ], fn($v) => $v !== null);
        
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Get average Likert score for acceptance section (Q11-Q15)
     */
    public function getAcceptanceScoreAttribute()
    {
        $scores = array_filter([
            $this->likert_q11,
            $this->likert_q12,
            $this->likert_q13,
            $this->likert_q14,
            $this->likert_q15,
        ], fn($v) => $v !== null);
        
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }

    /**
     * Get overall Likert average
     */
    public function getOverallScoreAttribute()
    {
        $scores = array_filter([
            $this->likert_q1, $this->likert_q2, $this->likert_q3, $this->likert_q4, $this->likert_q5,
            $this->likert_q6, $this->likert_q7, $this->likert_q8, $this->likert_q9, $this->likert_q10,
            $this->likert_q11, $this->likert_q12, $this->likert_q13, $this->likert_q14, $this->likert_q15,
        ], fn($v) => $v !== null);
        
        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
    }
}
