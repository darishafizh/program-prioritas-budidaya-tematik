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
        Schema::create('kdmp', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('desa')->nullable();
            $table->string('nama_kdkmp')->nullable();
            $table->string('komoditas')->nullable();
            $table->string('ketua_anggota')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('nama_penyuluh')->nullable();
            $table->string('no_hp_penyuluh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kdmp');
    }
};
