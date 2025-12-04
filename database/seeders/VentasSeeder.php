<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ventas = [
            // Venta 1
            [
                'cliente_id' => 1,
                'fecha' => '2025-11-17',
                'observaciones' => 'Venta de acero para construcción',
                'detalles' => [
                    ['material_id' => 5, 'cantidad' => 200.00, 'precio_unitario' => 5.50],
                    ['material_id' => 6, 'cantidad' => 150.00, 'precio_unitario' => 4.00],
                ],
            ],
            // Venta 2
            [
                'cliente_id' => 2,
                'fecha' => '2025-11-19',
                'observaciones' => 'Cobre para fundición',
                'detalles' => [
                    ['material_id' => 1, 'cantidad' => 40.00, 'precio_unitario' => 110.00],
                    ['material_id' => 2, 'cantidad' => 25.00, 'precio_unitario' => 85.00],
                ],
            ],
            // Venta 3
            [
                'cliente_id' => 3,
                'fecha' => '2025-11-21',
                'observaciones' => 'Aluminio para taller mecánico',
                'detalles' => [
                    ['material_id' => 3, 'cantidad' => 80.00, 'precio_unitario' => 30.00],
                    ['material_id' => 4, 'cantidad' => 60.00, 'precio_unitario' => 25.00],
                ],
            ],
            // Venta 4
            [
                'cliente_id' => 5,
                'fecha' => '2025-11-23',
                'observaciones' => 'Metales diversos para fundidora',
                'detalles' => [
                    ['material_id' => 8, 'cantidad' => 20.00, 'precio_unitario' => 60.00],
                    ['material_id' => 9, 'cantidad' => 35.00, 'precio_unitario' => 55.00],
                    ['material_id' => 7, 'cantidad' => 50.00, 'precio_unitario' => 35.00],
                ],
            ],
            // Venta 5
            [
                'cliente_id' => 6,
                'fecha' => '2025-11-24',
                'observaciones' => 'Materiales para autopartes',
                'detalles' => [
                    ['material_id' => 12, 'cantidad' => 10.00, 'precio_unitario' => 120.00],
                    ['material_id' => 11, 'cantidad' => 30.00, 'precio_unitario' => 50.00],
                ],
            ],
            // Venta 6
            [
                'cliente_id' => 8,
                'fecha' => '2025-11-26',
                'observaciones' => 'Fierro para herrería',
                'detalles' => [
                    ['material_id' => 5, 'cantidad' => 300.00, 'precio_unitario' => 5.50],
                    ['material_id' => 7, 'cantidad' => 40.00, 'precio_unitario' => 35.00],
                ],
            ],
            // Venta 7
            [
                'cliente_id' => 9,
                'fecha' => '2025-11-27',
                'observaciones' => 'Exportación de cobre y aluminio',
                'detalles' => [
                    ['material_id' => 1, 'cantidad' => 100.00, 'precio_unitario' => 110.00],
                    ['material_id' => 3, 'cantidad' => 200.00, 'precio_unitario' => 30.00],
                ],
            ],
            // Venta 8
            [
                'cliente_id' => 11,
                'fecha' => '2025-11-28',
                'observaciones' => 'Lote de aluminio y plástico',
                'detalles' => [
                    ['material_id' => 13, 'cantidad' => 80.00, 'precio_unitario' => 18.00],
                    ['material_id' => 15, 'cantidad' => 100.00, 'precio_unitario' => 5.00],
                ],
            ],
            // Venta 9
            [
                'cliente_id' => 12,
                'fecha' => '2025-11-29',
                'observaciones' => 'Aluminio para transformación',
                'detalles' => [
                    ['material_id' => 3, 'cantidad' => 150.00, 'precio_unitario' => 30.00],
                    ['material_id' => 4, 'cantidad' => 100.00, 'precio_unitario' => 25.00],
                ],
            ],
            // Venta 10
            [
                'cliente_id' => 14,
                'fecha' => '2025-11-30',
                'observaciones' => 'Metales para fabricación',
                'detalles' => [
                    ['material_id' => 5, 'cantidad' => 250.00, 'precio_unitario' => 5.50],
                    ['material_id' => 8, 'cantidad' => 15.00, 'precio_unitario' => 60.00],
                    ['material_id' => 1, 'cantidad' => 30.00, 'precio_unitario' => 110.00],
                ],
            ],
            // Venta 11
            [
                'cliente_id' => 4,
                'fecha' => '2025-12-01',
                'observaciones' => 'Cable de cobre',
                'detalles' => [
                    ['material_id' => 11, 'cantidad' => 50.00, 'precio_unitario' => 50.00],
                ],
            ],
            // Venta 12
            [
                'cliente_id' => 7,
                'fecha' => '2025-12-02',
                'observaciones' => 'Cartón y plástico reciclado',
                'detalles' => [
                    ['material_id' => 14, 'cantidad' => 200.00, 'precio_unitario' => 2.50],
                    ['material_id' => 15, 'cantidad' => 150.00, 'precio_unitario' => 5.00],
                ],
            ],
        ];

        foreach ($ventas as $ventaData) {
            $detalles = $ventaData['detalles'];
            unset($ventaData['detalles']);

            // Calcular el total de la venta
            $total = 0;
            foreach ($detalles as $detalle) {
                $total += $detalle['cantidad'] * $detalle['precio_unitario'];
            }
            $ventaData['total'] = $total;

            // Crear la venta
            $venta = \App\Models\Venta::create($ventaData);

            // Crear los detalles
            foreach ($detalles as $detalle) {
                $detalle['venta_id'] = $venta->id;
                $detalle['subtotal'] = $detalle['cantidad'] * $detalle['precio_unitario'];
                \App\Models\DetalleVenta::create($detalle);
            }
        }
    }
}
