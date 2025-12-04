<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiales = [
            [
                'nombre' => 'Cobre Brillante',
                'descripcion' => 'Cobre limpio de primera calidad, sin impurezas',
                'unidad_medida' => 'kg',
                'precio_compra' => 85.50,
                'precio_venta' => 110.00,
                'stock' => 250.00,
            ],
            [
                'nombre' => 'Cobre Quemado',
                'descripcion' => 'Cobre recuperado de cables quemados',
                'unidad_medida' => 'kg',
                'precio_compra' => 65.00,
                'precio_venta' => 85.00,
                'stock' => 180.50,
            ],
            [
                'nombre' => 'Aluminio Limpio',
                'descripcion' => 'Aluminio puro sin anodizar',
                'unidad_medida' => 'kg',
                'precio_compra' => 22.00,
                'precio_venta' => 30.00,
                'stock' => 450.00,
            ],
            [
                'nombre' => 'Aluminio de Perfil',
                'descripcion' => 'Perfiles de aluminio de ventanas y puertas',
                'unidad_medida' => 'kg',
                'precio_compra' => 18.00,
                'precio_venta' => 25.00,
                'stock' => 320.75,
            ],
            [
                'nombre' => 'Fierro de Primera',
                'descripcion' => 'Acero estructural de primera calidad',
                'unidad_medida' => 'kg',
                'precio_compra' => 3.50,
                'precio_venta' => 5.50,
                'stock' => 1250.00,
            ],
            [
                'nombre' => 'Fierro de Segunda',
                'descripcion' => 'Chatarra de fierro mezclada',
                'unidad_medida' => 'kg',
                'precio_compra' => 2.50,
                'precio_venta' => 4.00,
                'stock' => 980.00,
            ],
            [
                'nombre' => 'Acero Inoxidable',
                'descripcion' => 'Acero inoxidable limpio (304/316)',
                'unidad_medida' => 'kg',
                'precio_compra' => 25.00,
                'precio_venta' => 35.00,
                'stock' => 150.00,
            ],
            [
                'nombre' => 'Bronce',
                'descripcion' => 'Aleación de cobre y estaño',
                'unidad_medida' => 'kg',
                'precio_compra' => 45.00,
                'precio_venta' => 60.00,
                'stock' => 85.50,
            ],
            [
                'nombre' => 'Latón',
                'descripcion' => 'Aleación de cobre y zinc',
                'unidad_medida' => 'kg',
                'precio_compra' => 40.00,
                'precio_venta' => 55.00,
                'stock' => 120.25,
            ],
            [
                'nombre' => 'Batería Usada',
                'descripcion' => 'Baterías de plomo-ácido usadas',
                'unidad_medida' => 'pza',
                'precio_compra' => 15.00,
                'precio_venta' => 25.00,
                'stock' => 45.00,
            ],
            [
                'nombre' => 'Cable de Cobre',
                'descripcion' => 'Cable eléctrico con aislamiento',
                'unidad_medida' => 'kg',
                'precio_compra' => 35.00,
                'precio_venta' => 50.00,
                'stock' => 200.00,
            ],
            [
                'nombre' => 'Radiador de Aluminio',
                'descripcion' => 'Radiadores automotrices de aluminio',
                'unidad_medida' => 'pza',
                'precio_compra' => 80.00,
                'precio_venta' => 120.00,
                'stock' => 25.00,
            ],
            [
                'nombre' => 'Lata de Aluminio',
                'descripcion' => 'Latas de refresco y cerveza',
                'unidad_medida' => 'kg',
                'precio_compra' => 12.00,
                'precio_venta' => 18.00,
                'stock' => 150.00,
            ],
            [
                'nombre' => 'Cartón',
                'descripcion' => 'Cartón limpio y seco',
                'unidad_medida' => 'kg',
                'precio_compra' => 1.50,
                'precio_venta' => 2.50,
                'stock' => 500.00,
            ],
            [
                'nombre' => 'Plástico PET',
                'descripcion' => 'Botellas de plástico PET',
                'unidad_medida' => 'kg',
                'precio_compra' => 3.00,
                'precio_venta' => 5.00,
                'stock' => 380.00,
            ],
        ];

        foreach ($materiales as $material) {
            \App\Models\Material::create($material);
        }
    }
}
