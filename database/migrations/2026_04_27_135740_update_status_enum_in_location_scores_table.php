<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: First change to VARCHAR to allow any value
        DB::statement("ALTER TABLE location_scores MODIFY COLUMN status VARCHAR(50) DEFAULT 'TIDAK POTENSIAL'");

        // Step 2: Update existing data to new values
        DB::statement("UPDATE location_scores SET status = 'POTENSIAL' WHERE total_score >= 60");
        DB::statement("UPDATE location_scores SET status = 'TIDAK POTENSIAL' WHERE total_score < 60");

        // Step 3: Now convert to ENUM with new values
        DB::statement("ALTER TABLE location_scores MODIFY COLUMN status ENUM('POTENSIAL', 'TIDAK POTENSIAL') DEFAULT 'TIDAK POTENSIAL'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE location_scores MODIFY COLUMN status ENUM('SANGAT LAYAK', 'LAYAK', 'CUKUP LAYAK', 'TIDAK LAYAK') DEFAULT 'TIDAK LAYAK'");
    }
};
