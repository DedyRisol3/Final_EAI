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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('password')
        ]);
        User::factory(10)->create();
        // === PANGGIL PRODUCT SEEDER DI SINI ===
        $this->call([
            ProductSeeder::class,
            // Anda bisa menambahkan Seeder lain di sini nanti
        ]);
        // === AKHIR PANGGILAN ===
    }
}
