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
            $table->decimal('fcr', 8, 2)->nullable()->after('survival_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_produksi', function (Blueprint $table) {
            $table->dropColumn('fcr');
        });
    }
};
