<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi_budidaya', function (Blueprint $table) {
            // Tambah kolom-kolom lokasi detail (setelah nama_koperasi)
            $table->string('provinsi')->nullable()->after('nama_koperasi');
            $table->string('kabupaten_kota')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kabupaten_kota');
            $table->string('desa')->nullable()->after('kecamatan');

            // Tambah kolom volume dan hasil_panen_kg (setelah desa)
            $table->decimal('volume', 15, 2)->default(0)->comment('volume dalam satuan tertentu')->after('desa');
            $table->decimal('hasil_panen_kg', 15, 2)->default(0)->comment('hasil panen dalam kg')->after('volume');
        });

        // Migrasi data lama: salin lokasi ke provinsi, volume_hasil_panen ke hasil_panen_kg
        DB::table('lokasi_budidaya')->orderBy('id')->each(function ($row) {
            DB::table('lokasi_budidaya')->where('id', $row->id)->update([
                'provinsi' => $row->lokasi,
                'hasil_panen_kg' => $row->volume_hasil_panen,
            ]);
        });

        Schema::table('lokasi_budidaya', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['lokasi', 'volume_hasil_panen']);
        });
    }

    public function down(): void
    {
        Schema::table('lokasi_budidaya', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('nama_koperasi');
            $table->decimal('volume_hasil_panen', 15, 2)->default(0)->after('lokasi');
        });

        DB::table('lokasi_budidaya')->orderBy('id')->each(function ($row) {
            DB::table('lokasi_budidaya')->where('id', $row->id)->update([
                'lokasi' => $row->provinsi,
                'volume_hasil_panen' => $row->hasil_panen_kg,
            ]);
        });

        Schema::table('lokasi_budidaya', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'kabupaten_kota', 'kecamatan', 'desa', 'volume', 'hasil_panen_kg']);
        });
    }
};
