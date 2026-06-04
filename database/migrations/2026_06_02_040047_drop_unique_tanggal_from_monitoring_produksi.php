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
        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->dropForeign('monitoring_produksi_kdmp_id_foreign');
            $table->dropUnique('monitoring_kdmp_tanggal_unique');
        });
        
        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->foreign('kdmp_id', 'monitoring_produksi_kdmp_id_foreign')
                  ->references('id')
                  ->on('kdmp')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->unique(['kdmp_id', 'tanggal'], 'monitoring_kdmp_tanggal_unique');
        });
    }
};
