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
        Schema::table('kdmp', function (Blueprint $table) {
            $table->string('long')->after('no_hp_penyuluh')->nullable();
            $table->string('lat')->after('long')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kdmp', function (Blueprint $table) {
            $table->dropColumn(['long', 'lat']);
        });
    }
};
