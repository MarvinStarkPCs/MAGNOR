<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComprasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $compras = [
            // Compra 1
            [
                'proveedor_id' => 1,
                'fecha_compra' => '2025-11-15',
                'observaciones' => 'Compra de cobre de primera calidad',
                'detalles' => [
                    ['material_id' => 1, 'cantidad' => 50.00, 'precio_unitario' => 85.50],
                    ['material_id' => 2, 'cantidad' => 30.50, 'precio_unitario' => 65.00],
                ],
            ],
            // Compra 2
            [
                'proveedor_id' => 2,
                'fecha_compra' => '2025-11-16',
                'observaciones' => 'Aluminio limpio y perfiles',
                'detalles' => [
                    ['material_id' => 3, 'cantidad' => 120.00, 'precio_unitario' => 22.00],
                    ['material_id' => 4, 'cantidad' => 85.75, 'precio_unitario' => 18.00],
                ],
            ],
            // Compra 3
            [
                'proveedor_id' => 4,
                'fecha_compra' => '2025-11-18',
                'observaciones' => 'Fierro mezclado',
                'detalles' => [
                    ['material_id' => 5, 'cantidad' => 500.00, 'precio_unitario' => 3.50],
                    ['material_id' => 6, 'cantidad' => 350.00, 'precio_unitario' => 2.50],
                ],
            ],
            // Compra 4
            [
                'proveedor_id' => 6,
                'fecha_compra' => '2025-11-20',
                'observaciones' => 'Bronce y latón de calidad',
                'detalles' => [
                    ['material_id' => 8, 'cantidad' => 25.50, 'precio_unitario' => 45.00],
                    ['material_id' => 9, 'cantidad' => 40.25, 'precio_unitario' => 40.00],
                ],
            ],
            // Compra 5
            [
                'proveedor_id' => 8,
                'fecha_compra' => '2025-11-22',
                'observaciones' => 'Cables y radiadores',
                'detalles' => [
                    ['material_id' => 11, 'cantidad' => 80.00, 'precio_unitario' => 35.00],
                    ['material_id' => 12, 'cantidad' => 15.00, 'precio_unitario' => 80.00],
                ],
            ],
            // Compra 6
            [
                'proveedor_id' => 10,
                'fecha_compra' => '2025-11-25',
                'observaciones' => 'Materiales reciclables varios',
                'detalles' => [
                    ['material_id' => 13, 'cantidad' => 100.00, 'precio_unitario' => 12.00],
                    ['material_id' => 14, 'cantidad' => 150.00, 'precio_unitario' => 1.50],
                    ['material_id' => 15, 'cantidad' => 120.00, 'precio_unitario' => 3.00],
                ],
            ],
            // Compra 7
            [
                'proveedor_id' => 3,
                'fecha_compra' => '2025-11-26',
                'observaciones' => 'Acero inoxidable y fierro',
                'detalles' => [
                    ['material_id' => 7, 'cantidad' => 60.00, 'precio_unitario' => 25.00],
                    ['material_id' => 5, 'cantidad' => 200.00, 'precio_unitario' => 3.50],
                ],
            ],
            // Compra 8
            [
                'proveedor_id' => 5,
                'fecha_compra' => '2025-11-28',
                'observaciones' => 'Baterías usadas y cable',
                'detalles' => [
                    ['material_id' => 10, 'cantidad' => 25.00, 'precio_unitario' => 15.00],
                    ['material_id' => 11, 'cantidad' => 45.00, 'precio_unitario' => 35.00],
                ],
            ],
            // Compra 9
            [
                'proveedor_id' => 7,
                'fecha_compra' => '2025-11-29',
                'observaciones' => 'Cobre y aluminio de alta calidad',
                'detalles' => [
                    ['material_id' => 1, 'cantidad' => 75.00, 'precio_unitario' => 85.50],
                    ['material_id' => 3, 'cantidad' => 150.00, 'precio_unitario' => 22.00],
                ],
            ],
            // Compra 10
            [
                'proveedor_id' => 9,
                'fecha_compra' => '2025-11-30',
                'observaciones' => 'Materiales mixtos',
                'detalles' => [
                    ['material_id' => 4, 'cantidad' => 90.00, 'precio_unitario' => 18.00],
                    ['material_id' => 6, 'cantidad' => 250.00, 'precio_unitario' => 2.50],
                    ['material_id' => 9, 'cantidad' => 30.00, 'precio_unitario' => 40.00],
                ],
            ],
        ];

        foreach ($compras as $compraData) {
            $detalles = $compraData['detalles'];
            unset($compraData['detalles']);

            // Calcular el total de la compra
            $total = 0;
            foreach ($detalles as $detalle) {
                $total += $detalle['cantidad'] * $detalle['precio_unitario'];
            }
            $compraData['total'] = $total;

            // Crear la compra
            $compra = \App\Models\Compra::create($compraData);

            // Crear los detalles
            foreach ($detalles as $detalle) {
                $detalle['compra_id'] = $compra->id;
                $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precio_unitario'];
                \App\Models\DetalleCompra::create($detalle);
            }
        }
    }
}
