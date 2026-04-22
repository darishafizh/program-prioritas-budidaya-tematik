<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom foto_sebelum & foto_sesudah di progres_fisik_records
        Schema::table('progres_fisik_records', function (Blueprint $table) {
            $table->json('foto_sebelum')->nullable()->after('foto')->comment('Foto kondisi sebelum pembangunan');
            $table->json('foto_sesudah')->nullable()->after('foto_sebelum')->comment('Foto kondisi sesudah pembangunan');
        });

        // 2. Hapus kolom foto lama dari progres_fisik_records
        Schema::table('progres_fisik_records', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        // 3. Hapus kolom foto dari monitoring_records
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }

    public function down(): void
    {
        // Kembalikan kolom foto di monitoring_records
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->json('foto')->nullable();
        });

        // Kembalikan kolom foto di progres_fisik_records
        Schema::table('progres_fisik_records', function (Blueprint $table) {
            $table->json('foto')->nullable();
        });

        // Hapus kolom baru
        Schema::table('progres_fisik_records', function (Blueprint $table) {
            $table->dropColumn(['foto_sebelum', 'foto_sesudah']);
        });
    }
};
