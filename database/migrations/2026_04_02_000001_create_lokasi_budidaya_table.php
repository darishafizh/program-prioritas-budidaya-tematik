<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_budidaya', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koperasi');
            $table->string('lokasi');
            $table->decimal('volume_hasil_panen', 15, 2)->default(0)->comment('dalam kg');
            $table->decimal('nilai_hasil_panen', 20, 2)->default(0)->comment('dalam rupiah');
            $table->decimal('biaya_operasional', 20, 2)->default(0)->comment('dalam rupiah');
            $table->decimal('harga_jual_per_kg', 15, 2)->default(0)->comment('dalam rupiah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_budidaya');
    }
};
