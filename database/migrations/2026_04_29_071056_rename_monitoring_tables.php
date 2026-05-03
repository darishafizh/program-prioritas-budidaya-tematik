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
        Schema::rename('monitoring_records', 'monitoring_produksi');
        Schema::rename('progres_fisik_records', 'monitoring_progres_fisik');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('monitoring_produksi', 'monitoring_records');
        Schema::rename('monitoring_progres_fisik', 'progres_fisik_records');
    }
};
