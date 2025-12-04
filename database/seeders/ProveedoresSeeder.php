<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Juan Carlos Pérez García',
                'documento' => 'CC: 1.052.345.678',
                'telefono' => '301-234-5678',
                'direccion' => 'Cra 15 #45-23, Bogotá',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'María Elena Rodríguez López',
                'documento' => 'CC: 1.098.765.432',
                'telefono' => '310-456-7890',
                'direccion' => 'Calle 72 #10-34, Medellín',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Recicladora del Norte S.A.S.',
                'documento' => 'NIT: 900.123.456-7',
                'telefono' => '320-567-8901',
                'direccion' => 'Zona Industrial Norte, Bodega 7, Bogotá',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'Roberto Sánchez Martínez',
                'documento' => 'CC: 1.076.543.210',
                'telefono' => '315-678-9012',
                'direccion' => 'Calle 80 #25-67, Cali',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Metales y Chatarra La Esperanza',
                'documento' => 'NIT: 800.987.654-3',
                'telefono' => '304-789-0123',
                'direccion' => 'Zona Industrial Sur, Lote 12, Barranquilla',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'José Luis Hernández Cruz',
                'documento' => 'CC: 1.088.234.567',
                'telefono' => '318-890-1234',
                'direccion' => 'Cra 50 #12-89, Cartagena',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Grupo Reciclador del Valle',
                'documento' => 'NIT: 900.456.789-1',
                'telefono' => '312-901-2345',
                'direccion' => 'Autopista Sur Km 15, Cali',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'Guadalupe Ramírez Flores',
                'documento' => 'CC: 1.063.456.789',
                'telefono' => '305-012-3456',
                'direccion' => 'Calle 45 #23-45, Bucaramanga',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Comercializadora de Metales del Atlántico',
                'documento' => 'NIT: 800.234.567-8',
                'telefono' => '311-123-4567',
                'direccion' => 'Cra 38 #74-123, Barranquilla',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'Francisco Gómez Torres',
                'documento' => 'CC: 1.045.678.901',
                'telefono' => '316-234-5678',
                'direccion' => 'Calle 100 #15-67, Bogotá',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Ecología y Reciclaje del Caribe',
                'documento' => 'NIT: 900.789.012-3',
                'telefono' => '323-345-6789',
                'direccion' => 'Av. del Río #101, Santa Marta',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'Pedro Antonio Morales Díaz',
                'documento' => 'CC: 1.071.234.567',
                'telefono' => '314-456-7890',
                'direccion' => 'Cra 7 #34-56, Pereira',
                'es_reciclador' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            \App\Models\Proveedor::create($proveedor);
        }
    }
}
