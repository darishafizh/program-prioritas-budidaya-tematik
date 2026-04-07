<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('location_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('kdmp_id')->nullable()->after('id');
            $table->foreign('kdmp_id')->references('id')->on('kdmp')->onDelete('set null');
            $table->index('kdmp_id');
        });
    }

    public function down(): void
    {
        Schema::table('location_scores', function (Blueprint $table) {
            $table->dropForeign(['kdmp_id']);
            $table->dropIndex(['kdmp_id']);
            $table->dropColumn('kdmp_id');
        });
    }
};
