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
        // Drop foreign key first (MySQL requires this before dropping unique index)
        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->dropForeign('monitoring_records_kdmp_id_foreign');
            $table->dropUnique('monitoring_kdmp_periode_unique');
            $table->dropIndex('monitoring_records_tahun_bulan_index');
        });

        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
            $table->unique(['kdmp_id', 'tanggal'], 'monitoring_kdmp_tanggal_unique');
            $table->foreign('kdmp_id')->references('id')->on('kdmp')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->dropForeign(['kdmp_id']);
            $table->dropUnique('monitoring_kdmp_tanggal_unique');
            $table->tinyInteger('bulan')->nullable();
            $table->year('tahun')->nullable();
            $table->unique(['kdmp_id', 'bulan', 'tahun'], 'monitoring_kdmp_periode_unique');
            $table->foreign('kdmp_id')->references('id')->on('kdmp')->onDelete('cascade');
        });
    }
};
