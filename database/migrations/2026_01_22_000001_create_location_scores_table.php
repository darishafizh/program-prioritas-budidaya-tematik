<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_scores', function (Blueprint $table) {
            $table->id();
            
            // Identitas Lokasi
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            
            // Relasi ke Survey
            $table->foreignId('kdmp_survey_id')->nullable()->constrained('kdmp_surveys')->onDelete('set null');
            $table->foreignId('masyarakat_survey_id')->nullable()->constrained('masyarakat_surveys')->onDelete('set null');
            $table->foreignId('sppg_survey_id')->nullable()->constrained('sppg_surveys')->onDelete('set null');
            
            // Skor per Indikator (0-100)
            $table->decimal('kdmp_score', 5, 2)->default(0);
            $table->decimal('masyarakat_score', 5, 2)->default(0);
            $table->decimal('sppg_score', 5, 2)->default(0);
            
            // Skor Total dengan Bobot (0-100)
            $table->decimal('total_score', 5, 2)->default(0);
            
            // Status Kelayakan
            $table->enum('status', ['SANGAT LAYAK', 'LAYAK', 'CUKUP LAYAK', 'TIDAK LAYAK'])->default('TIDAK LAYAK');
            
            // Detail Breakdown Skor KDMP
            $table->decimal('kdmp_admin_score', 5, 2)->default(0);
            $table->decimal('kdmp_lahan_score', 5, 2)->default(0);
            $table->decimal('kdmp_instalasi_score', 5, 2)->default(0);
            $table->decimal('kdmp_progres_score', 5, 2)->default(0);
            
            // Detail Breakdown Skor Masyarakat
            $table->decimal('masy_tanggapan_score', 5, 2)->default(0);
            $table->decimal('masy_likert_score', 5, 2)->default(0);
            $table->decimal('masy_kelembagaan_score', 5, 2)->default(0);
            
            // Detail Breakdown Skor SPPG
            $table->decimal('sppg_demand_score', 5, 2)->default(0);
            $table->decimal('sppg_minat_score', 5, 2)->default(0);
            $table->decimal('sppg_infra_score', 5, 2)->default(0);
            
            // Catatan
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['kecamatan', 'kabupaten', 'provinsi']);
            $table->index('status');
            $table->index('total_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_scores');
    }
};
