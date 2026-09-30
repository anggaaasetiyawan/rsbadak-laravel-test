<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === USERS ===
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@rs.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $petugas = User::create([
            'name' => 'Petugas Pendaftaran',
            'email' => 'petugas@rs.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);
    }
}
