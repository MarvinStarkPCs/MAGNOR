<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Crear usuario administrador
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@magnor.com',
            'password' => bcrypt('password'),
        ]);

        // Seeders de catálogos base
        $this->call([
            MaterialesSeeder::class,
            ProveedoresSeeder::class,
            ClientesSeeder::class,
            CategoriasCajaSeeder::class,
        ]);

        // Seeders de transacciones (dependen de catálogos)
        $this->call([
            ComprasSeeder::class,
            VentasSeeder::class,
            CajasSeeder::class,
        ]);
    }
}
