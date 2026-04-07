<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_records', function (Blueprint $table) {
            $table->id();

            // Relasi ke KDMP master
            $table->unsignedBigInteger('kdmp_id');
            $table->foreign('kdmp_id')->references('id')->on('kdmp')->onDelete('cascade');

            // Relasi ke user yang menginput
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Periode pelaporan
            $table->tinyInteger('bulan')->comment('1-12'); // Bulan laporan
            $table->year('tahun');                          // Tahun laporan

            // Status lokasi
            $table->enum('status_lokasi', [
                'on_track',
                'bermasalah',
                'selesai',
                'vakum',
            ])->default('on_track');

            // Progres fisik (0-100%)
            $table->integer('progres_fisik')->default(0)->comment('0-100 persen');

            // Data produksi
            $table->decimal('volume_panen_kg', 12, 2)->default(0)->comment('dalam kg');
            $table->decimal('nilai_produksi', 20, 2)->default(0)->comment('dalam rupiah');
            $table->decimal('biaya_operasional', 20, 2)->default(0)->comment('dalam rupiah');
            $table->integer('jumlah_pembudidaya_aktif')->default(0);

            // Catatan lapangan
            $table->text('kendala')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('catatan')->nullable();

            // Foto dokumentasi (JSON array of file paths)
            $table->json('foto')->nullable();

            $table->timestamps();

            // Unique: satu KDMP hanya bisa punya satu laporan per bulan per tahun
            $table->unique(['kdmp_id', 'bulan', 'tahun'], 'monitoring_kdmp_periode_unique');

            // Indexes untuk filter
            $table->index(['tahun', 'bulan']);
            $table->index('status_lokasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_records');
    }
};
