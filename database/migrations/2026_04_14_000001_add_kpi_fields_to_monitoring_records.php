<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->decimal('survival_rate', 5, 2)->nullable()->after('jumlah_pembudidaya_aktif')->comment('Survival Rate 0-100%');
            $table->integer('jumlah_kolam_aktif')->nullable()->after('survival_rate');
            $table->integer('jumlah_kolam_total')->nullable()->after('jumlah_kolam_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn(['survival_rate', 'jumlah_kolam_aktif', 'jumlah_kolam_total']);
        });
    }
};
