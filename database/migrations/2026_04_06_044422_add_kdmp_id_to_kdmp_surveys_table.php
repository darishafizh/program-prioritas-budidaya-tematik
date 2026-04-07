<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kdmp_surveys', function (Blueprint $table) {
            $table->unsignedBigInteger('kdmp_id')->nullable()->after('user_id');
            $table->foreign('kdmp_id')->references('id')->on('kdmp')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('kdmp_surveys', function (Blueprint $table) {
            $table->dropForeign(['kdmp_id']);
            $table->dropColumn('kdmp_id');
        });
    }
};
