<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check and add role column if not exists
        if (!Schema::hasColumn('users', 'role')) {
            DB::statement('ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT "verifikator"');
        }

        // Create Admin user
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create Verifikator user
        User::updateOrCreate(
            ['username' => 'verifikator'],
            [
                'username' => 'verifikator',
                'password' => Hash::make('verifikator123'),
                'role' => 'verifikator',
            ]
        );

        // Create Administrator Roren user
        User::updateOrCreate(
            ['username' => 'Administrator Roren'],
            [
                'username' => 'Administrator Roren',
                'password' => Hash::make('b10fL0kRoRen!'),
                'role' => 'admin',
            ]
        );
    }
}
