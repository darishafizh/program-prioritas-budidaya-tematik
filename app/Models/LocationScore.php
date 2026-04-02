<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kdmp_survey_id',
        'masyarakat_survey_id',
        'sppg_survey_id',
        'kdmp_score',
        'masyarakat_score',
        'sppg_score',
        'total_score',
        'status',
        'kdmp_admin_score',
        'kdmp_lahan_score',
        'kdmp_instalasi_score',
        'kdmp_progres_score',
        'masy_tanggapan_score',
        'masy_likert_score',
        'masy_kelembagaan_score',
        'sppg_demand_score',
        'sppg_minat_score',
        'sppg_infra_score',
        'notes',
    ];

    protected $casts = [
        'kdmp_score' => 'decimal:2',
        'masyarakat_score' => 'decimal:2',
        'sppg_score' => 'decimal:2',
        'total_score' => 'decimal:2',
    ];

    // Relationships
    public function kdmpSurvey()
    {
        return $this->belongsTo(KdmpSurvey::class);
    }

    public function masyarakatSurvey()
    {
        return $this->belongsTo(MasyarakatSurvey::class);
    }

    public function sppgSurvey()
    {
        return $this->belongsTo(SppgSurvey::class);
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'SANGAT LAYAK' => 'success',
            'LAYAK' => 'primary',
            'CUKUP LAYAK' => 'warning',
            'TIDAK LAYAK' => 'danger',
            default => 'secondary',
        };
    }

    // Get status icon
    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'SANGAT LAYAK' => '🟢',
            'LAYAK' => '🔵',
            'CUKUP LAYAK' => '🟡',
            'TIDAK LAYAK' => '🔴',
            default => '⚪',
        };
    }

    // Get full location name
    public function getFullLocationAttribute()
    {
        return "{$this->kecamatan}, {$this->kabupaten}, {$this->provinsi}";
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for filtering by kabupaten
    public function scopeByKabupaten($query, $kabupaten)
    {
        return $query->where('kabupaten', $kabupaten);
    }

    // Scope for filtering by provinsi
    public function scopeByProvinsi($query, $provinsi)
    {
        return $query->where('provinsi', $provinsi);
    }

    // Scope for ordering by score
    public function scopeRanked($query)
    {
        return $query->orderByDesc('total_score');
    }
}
