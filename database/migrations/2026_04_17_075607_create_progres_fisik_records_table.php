<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progres_fisik_records', function (Blueprint $table) {
            $table->id();

            // Relasi ke KDMP master
            $table->unsignedBigInteger('kdmp_id');
            $table->foreign('kdmp_id')->references('id')->on('kdmp')->onDelete('cascade');

            // Relasi ke user yang menginput
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Periode pelaporan
            $table->tinyInteger('bulan')->comment('1-12');
            $table->year('tahun');

            // Detail progres fisik per komponen (0-100%)
            $table->integer('progres_bangunan')->default(0)->comment('0-100 persen');
            $table->integer('progres_kolam')->default(0)->comment('0-100 persen');
            $table->integer('progres_listrik')->default(0)->comment('0-100 persen');
            $table->integer('progres_air')->default(0)->comment('0-100 persen');
            $table->integer('progres_aerasi')->default(0)->comment('0-100 persen');

            // Catatan
            $table->text('kendala')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('catatan')->nullable();

            // Foto dokumentasi
            $table->json('foto')->nullable();

            $table->timestamps();

            // Unique: satu KDMP hanya bisa punya satu record per bulan per tahun
            $table->unique(['kdmp_id', 'bulan', 'tahun'], 'progres_fisik_kdmp_periode_unique');
            $table->index(['tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progres_fisik_records');
    }
};
