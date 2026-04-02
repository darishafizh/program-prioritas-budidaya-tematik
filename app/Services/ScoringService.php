<?php

namespace App\Services;

use App\Models\KdmpSurvey;
use App\Models\MasyarakatSurvey;
use App\Models\SppgSurvey;
use App\Models\LocationScore;

class ScoringService
{
    // Bobot untuk setiap indikator
    const WEIGHT_KDMP = 0.40;
    const WEIGHT_MASYARAKAT = 0.30;
    const WEIGHT_SPPG = 0.30;

    /**
     * Calculate score for a specific kecamatan
     */
    public function calculateForKecamatan(string $kecamatan, string $kabupaten, string $provinsi): LocationScore
    {
        // Find related surveys
        $kdmpSurvey = KdmpSurvey::where('kecamatan', $kecamatan)
            ->where('kabupaten', $kabupaten)
            ->latest()
            ->first();

        $masyarakatSurvey = MasyarakatSurvey::whereHas('kdmpSurvey', function($q) use ($kecamatan, $kabupaten) {
            $q->where('kecamatan', $kecamatan)->where('kabupaten', $kabupaten);
        })->latest()->first();

        // If no direct relation, try to find by location match
        if (!$masyarakatSurvey) {
            $masyarakatSurvey = MasyarakatSurvey::where('tempat', 'like', "%{$kecamatan}%")
                ->latest()
                ->first();
        }

        $sppgSurvey = SppgSurvey::where('kabupaten', $kabupaten)
            ->latest()
            ->first();

        // Calculate individual scores
        $kdmpScores = $this->calculateKdmpScore($kdmpSurvey);
        $masyarakatScores = $this->calculateMasyarakatScore($masyarakatSurvey);
        $sppgScores = $this->calculateSppgScore($sppgSurvey);

        // Calculate weighted total
        $totalScore = 
            ($kdmpScores['total'] * self::WEIGHT_KDMP) +
            ($masyarakatScores['total'] * self::WEIGHT_MASYARAKAT) +
            ($sppgScores['total'] * self::WEIGHT_SPPG);

        // Determine status
        $status = $this->determineStatus($totalScore);

        // Create or update location score
        $locationScore = LocationScore::updateOrCreate(
            [
                'kecamatan' => $kecamatan,
                'kabupaten' => $kabupaten,
                'provinsi' => $provinsi,
            ],
            [
                'kdmp_survey_id' => $kdmpSurvey?->id,
                'masyarakat_survey_id' => $masyarakatSurvey?->id,
                'sppg_survey_id' => $sppgSurvey?->id,
                'kdmp_score' => $kdmpScores['total'],
                'masyarakat_score' => $masyarakatScores['total'],
                'sppg_score' => $sppgScores['total'],
                'total_score' => $totalScore,
                'status' => $status,
                'kdmp_admin_score' => $kdmpScores['admin'],
                'kdmp_lahan_score' => $kdmpScores['lahan'],
                'kdmp_instalasi_score' => $kdmpScores['instalasi'],
                'kdmp_progres_score' => $kdmpScores['progres'],
                'masy_tanggapan_score' => $masyarakatScores['tanggapan'],
                'masy_likert_score' => $masyarakatScores['likert'],
                'masy_kelembagaan_score' => $masyarakatScores['kelembagaan'],
                'sppg_demand_score' => $sppgScores['demand'],
                'sppg_minat_score' => $sppgScores['minat'],
                'sppg_infra_score' => $sppgScores['infra'],
            ]
        );

        return $locationScore;
    }

    /**
     * Calculate KDMP Score (0-100)
     */
    public function calculateKdmpScore(?KdmpSurvey $survey): array
    {
        if (!$survey) {
            return ['total' => 0, 'admin' => 0, 'lahan' => 0, 'instalasi' => 0, 'progres' => 0];
        }

        // 1. Kriteria Administrasi (9 items, 25% of KDMP score)
        $adminItems = [
            $survey->krit_badan_hukum_kbli ?? false,
            $survey->krit_ekusuka ?? false,
            $survey->krit_jkn_aktif ?? false,
            $survey->krit_proposal ?? false,
            $survey->krit_kesanggupan ?? false,
            $survey->krit_belum_bantuan ?? false,
            $survey->krit_pelatihan ?? false,
            $survey->krit_kelola_koperasi ?? false,
        ];
        
        // Convert string 'Ya'/'Tidak' to boolean if needed
        $adminCount = collect($adminItems)->filter(function($item) {
            return $item === true || $item === 'Ya' || $item === 1 || $item === '1';
        })->count();
        $adminScore = ($adminCount / 8) * 100;

        // 2. Kriteria Lahan (8 items, 25% of KDMP score)
        $lahanItems = [
            $survey->lahan_datar ?? $survey->krit_lahan_datar ?? false,
            $survey->lahan_legalitas ?? $survey->krit_legalitas ?? false,
            $survey->lahan_sumber_air ?? $survey->krit_sumber_air ?? false,
            $survey->lahan_listrik ?? $survey->krit_listrik ?? false,
            $survey->lahan_akses ?? $survey->krit_akses ?? false,
            $survey->lahan_jauh_pencemar ?? $survey->krit_pencemar ?? false,
            $survey->lahan_prasarana ?? $survey->krit_prasarana ?? false,
            $survey->krit_penyimpanan ?? false,
        ];
        $lahanCount = collect($lahanItems)->filter(function($item) {
            return $item === true || $item === 'Ya' || $item === 1 || $item === '1';
        })->count();
        $lahanScore = ($lahanCount / 8) * 100;

        // 3. Status Instalasi (8 items, 25% of KDMP score)
        $instalasiItems = [
            $survey->inst_bak_terpal ?? $survey->inst_bak_status ?? false,
            $survey->inst_lantai ?? false,
            $survey->inst_air ?? false,
            $survey->inst_listrik ?? false,
            $survey->inst_aerasi ?? false,
            $survey->inst_atap ?? false,
            $survey->inst_peralatan ?? false,
            $survey->inst_ipal ?? false,
        ];
        $instalasiCount = collect($instalasiItems)->filter(function($item) {
            return $item === true || $item === 'Ya' || $item === 'Terinstal' || $item === 1 || $item === '1';
        })->count();
        $instalasiScore = ($instalasiCount / 8) * 100;

        // 4. Progres Teknis (5 items, rata-rata %, 25% of KDMP score)
        $progresItems = [
            $survey->progres_bangunan ?? 0,
            $survey->progres_kolam ?? 0,
            $survey->progres_listrik ?? 0,
            $survey->progres_air ?? 0,
            $survey->progres_aerasi ?? 0,
        ];
        $progresScore = collect($progresItems)->avg() ?: 0;

        // Total KDMP Score
        $totalScore = ($adminScore * 0.25) + ($lahanScore * 0.25) + ($instalasiScore * 0.25) + ($progresScore * 0.25);

        return [
            'total' => round($totalScore, 2),
            'admin' => round($adminScore, 2),
            'lahan' => round($lahanScore, 2),
            'instalasi' => round($instalasiScore, 2),
            'progres' => round($progresScore, 2),
        ];
    }

    /**
     * Calculate Masyarakat Score (0-100)
     */
    public function calculateMasyarakatScore(?MasyarakatSurvey $survey): array
    {
        if (!$survey) {
            return ['total' => 0, 'tanggapan' => 0, 'likert' => 0, 'kelembagaan' => 0];
        }

        // 1. Tanggapan Masyarakat (30% of Masyarakat score)
        $tanggapanScore = 0;
        
        // Sesuai kebutuhan
        if (in_array($survey->sesuai_kebutuhan, ['Ya, sesuai', 'Ya'])) {
            $tanggapanScore += 50;
        }
        
        // Perasaan
        if ($survey->perasaan === 'Senang') {
            $tanggapanScore += 50;
        } elseif ($survey->perasaan === 'Biasa saja') {
            $tanggapanScore += 25;
        }

        // 2. Likert Score (50% of Masyarakat score)
        $likertValues = [];
        for ($i = 1; $i <= 15; $i++) {
            $value = $survey->{"likert_q{$i}"};
            if ($value !== null) {
                // Convert letter to number if needed (a=1, b=2, c=3, d=4, e=5)
                if (is_string($value) && in_array(strtolower($value), ['a', 'b', 'c', 'd', 'e'])) {
                    $value = ord(strtolower($value)) - ord('a') + 1;
                }
                $likertValues[] = (int) $value;
            }
        }
        $likertScore = count($likertValues) > 0 
            ? (collect($likertValues)->avg() / 5) * 100 
            : 0;

        // 3. Kelembagaan (20% of Masyarakat score)
        $kelembagaanScore = 0;
        
        // Anggota KUB
        if (in_array($survey->anggota_kub, ['Ya sangat aktif', 'Ya, sangat aktif'])) {
            $kelembagaanScore += 25;
        } elseif (in_array($survey->anggota_kub, ['Ya tidak aktif', 'Ya, tidak aktif'])) {
            $kelembagaanScore += 15;
        }
        
        // Manfaat KUB
        if (in_array($survey->manfaat_kub, ['Sangat Setuju', 'Setuju'])) {
            $kelembagaanScore += 25;
        }
        
        // Anggota Koperasi
        if (in_array($survey->anggota_koperasi, ['Ya sangat aktif', 'Ya, sangat aktif'])) {
            $kelembagaanScore += 25;
        } elseif (in_array($survey->anggota_koperasi, ['Ya tidak aktif', 'Ya, tidak aktif'])) {
            $kelembagaanScore += 15;
        }
        
        // Manfaat Koperasi
        if (in_array($survey->manfaat_koperasi, ['Sangat Setuju', 'Setuju'])) {
            $kelembagaanScore += 25;
        }

        // Total Masyarakat Score
        $totalScore = ($tanggapanScore * 0.30) + ($likertScore * 0.50) + ($kelembagaanScore * 0.20);

        return [
            'total' => round($totalScore, 2),
            'tanggapan' => round($tanggapanScore, 2),
            'likert' => round($likertScore, 2),
            'kelembagaan' => round($kelembagaanScore, 2),
        ];
    }

    /**
     * Calculate SPPG Score (0-100)
     */
    public function calculateSppgScore(?SppgSurvey $survey): array
    {
        if (!$survey) {
            return ['total' => 0, 'demand' => 0, 'minat' => 0, 'infra' => 0];
        }

        // 1. Supply-Demand Match (40% of SPPG score)
        $demandScore = 0;
        
        // Kebutuhan terpenuhi?
        if ($survey->status_terpenuhi === 'Belum') {
            // Ada kebutuhan = ada potensi pasar
            $demandScore = 80;
            
            // Kekurangan besar = potensi lebih besar
            if ($survey->kekurangan && $survey->kekurangan > 100) {
                $demandScore = 100;
            }
        } elseif ($survey->status_terpenuhi === 'Ya') {
            $demandScore = 50; // Masih bisa supply tambahan
        }
        
        // Total kebutuhan ikan
        $totalKebutuhan = ($survey->kebutuhan_lele ?? 0) + ($survey->kebutuhan_nila ?? 0) + ($survey->kebutuhan_lain ?? 0);
        if ($totalKebutuhan > 500) {
            $demandScore = min(100, $demandScore + 20);
        }

        // 2. Minat Kerjasama (30% of SPPG score)
        $minatScore = match ($survey->minat_kerjasama) {
            'Ya, sangat berminat', 'Ya' => 100,
            'Ya, berminat' => 80,
            'Mungkin' => 50,
            'Tidak' => 0,
            default => 0,
        };

        // 3. Infrastruktur SPPG (30% of SPPG score)
        $infraScore = 0;
        
        // Jumlah sekolah
        if ($survey->jumlah_sekolah) {
            if ($survey->jumlah_sekolah >= 50) {
                $infraScore += 40;
            } elseif ($survey->jumlah_sekolah >= 20) {
                $infraScore += 30;
            } elseif ($survey->jumlah_sekolah >= 10) {
                $infraScore += 20;
            } else {
                $infraScore += 10;
            }
        }
        
        // Porsi bulanan
        if ($survey->porsi_bulanan) {
            if ($survey->porsi_bulanan >= 10000) {
                $infraScore += 30;
            } elseif ($survey->porsi_bulanan >= 5000) {
                $infraScore += 20;
            } else {
                $infraScore += 10;
            }
        }
        
        // Anggaran per porsi
        if ($survey->anggaran_porsi) {
            if ($survey->anggaran_porsi >= 15000) {
                $infraScore += 30;
            } elseif ($survey->anggaran_porsi >= 10000) {
                $infraScore += 20;
            } else {
                $infraScore += 10;
            }
        }

        // Total SPPG Score
        $totalScore = ($demandScore * 0.40) + ($minatScore * 0.30) + ($infraScore * 0.30);

        return [
            'total' => round($totalScore, 2),
            'demand' => round($demandScore, 2),
            'minat' => round($minatScore, 2),
            'infra' => round($infraScore, 2),
        ];
    }

    /**
     * Determine status based on total score
     */
    public function determineStatus(float $totalScore): string
    {
        if ($totalScore >= 85) {
            return 'SANGAT LAYAK';
        } elseif ($totalScore >= 70) {
            return 'LAYAK';
        } elseif ($totalScore >= 55) {
            return 'CUKUP LAYAK';
        } else {
            return 'TIDAK LAYAK';
        }
    }

    /**
     * Recalculate all location scores
     */
    public function recalculateAll(): int
    {
        $count = 0;
        
        // Get all unique locations from KDMP surveys
        $locations = KdmpSurvey::select('kecamatan', 'kabupaten', 'provinsi')
            ->whereNotNull('kecamatan')
            ->whereNotNull('kabupaten')
            ->distinct()
            ->get();

        foreach ($locations as $location) {
            $this->calculateForKecamatan(
                $location->kecamatan,
                $location->kabupaten,
                $location->provinsi ?? '-'
            );
            $count++;
        }

        return $count;
    }
}
