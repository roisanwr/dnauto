<?php

// database/seeders/DatabaseSeeder.php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <<< Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User Admin (untuk testing)
        User::factory()->create([
            'name' => 'Admin DN Auto',
            'email' => 'dnautocompany@gmail.com', // Email Admin
            'password' => Hash::make('DNAUTO123'), // Password Admin
            'role' => 'admin', // <<< JADIKAN ADMIN
        ]);

        // 2. Contoh User Biasa (Customer)
        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        
        // Opsional: Buat 10 customer dummy lainnya
        // User::factory(10)->create(['role' => 'customer']);
    }
}
