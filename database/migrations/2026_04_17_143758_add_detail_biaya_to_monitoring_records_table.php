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
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->decimal('biaya_pakan', 15, 2)->nullable()->after('nilai_produksi');
            $table->decimal('biaya_bibit', 15, 2)->nullable()->after('biaya_pakan');
            $table->decimal('biaya_lainnya', 15, 2)->nullable()->after('biaya_bibit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn(['biaya_pakan', 'biaya_bibit', 'biaya_lainnya']);
        });
    }
};
