<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CajasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cajas = [
            // Caja 1 - Cerrada
            [
                'fecha' => '2025-11-25',
                'usuario_apertura_id' => 1,
                'monto_apertura' => 1000.00,
                'hora_apertura' => '2025-11-25 08:00:00',
                'usuario_cierre_id' => 1,
                'monto_cierre' => 4850.00,
                'monto_esperado' => 4845.00,
                'diferencia' => 5.00,
                'hora_cierre' => '2025-11-25 18:00:00',
                'estado' => 'cerrada',
                'observaciones' => 'Caja con ligera diferencia positiva',
                'movimientos' => [
                    [
                        'categoria_id' => 1, // Venta de Chatarra
                        'tipo' => 'ingreso',
                        'monto' => 2500.00,
                        'concepto' => 'Venta de cobre a cliente',
                        'observaciones' => 'Pago en efectivo',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-25 10:30:00',
                    ],
                    [
                        'categoria_id' => 1,
                        'tipo' => 'ingreso',
                        'monto' => 1800.00,
                        'concepto' => 'Venta de aluminio',
                        'observaciones' => null,
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-25 12:15:00',
                    ],
                    [
                        'categoria_id' => 5, // Compra de Material
                        'tipo' => 'egreso',
                        'monto' => 350.00,
                        'concepto' => 'Compra de fierro a reciclador',
                        'observaciones' => 'Pago a Juan Pérez',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-25 14:00:00',
                    ],
                    [
                        'categoria_id' => 6, // Combustible
                        'tipo' => 'egreso',
                        'monto' => 95.00,
                        'concepto' => 'Gasolina para camioneta',
                        'observaciones' => 'Tanque lleno',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-25 16:00:00',
                    ],
                ],
            ],
            // Caja 2 - Cerrada
            [
                'fecha' => '2025-11-26',
                'usuario_apertura_id' => 1,
                'monto_apertura' => 1000.00,
                'hora_apertura' => '2025-11-26 08:00:00',
                'usuario_cierre_id' => 1,
                'monto_cierre' => 3280.00,
                'monto_esperado' => 3300.00,
                'diferencia' => -20.00,
                'hora_cierre' => '2025-11-26 18:00:00',
                'estado' => 'cerrada',
                'observaciones' => 'Diferencia negativa por error en cambio',
                'movimientos' => [
                    [
                        'categoria_id' => 1,
                        'tipo' => 'ingreso',
                        'monto' => 3500.00,
                        'concepto' => 'Venta de acero inoxidable',
                        'observaciones' => 'Cliente preferencial',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-26 09:30:00',
                    ],
                    [
                        'categoria_id' => 5,
                        'tipo' => 'egreso',
                        'monto' => 800.00,
                        'concepto' => 'Compra de bronce',
                        'observaciones' => null,
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-26 11:00:00',
                    ],
                    [
                        'categoria_id' => 10, // Papelería y Oficina
                        'tipo' => 'egreso',
                        'monto' => 120.00,
                        'concepto' => 'Compra de formatos y papelería',
                        'observaciones' => 'Facturas y notas',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-26 13:00:00',
                    ],
                    [
                        'categoria_id' => 15, // Comidas y Viáticos
                        'tipo' => 'egreso',
                        'monto' => 300.00,
                        'concepto' => 'Comida para empleados',
                        'observaciones' => 'Fonda del mercado',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-11-26 15:00:00',
                    ],
                ],
            ],
            // Caja 3 - Abierta (actual)
            [
                'fecha' => '2025-12-03',
                'usuario_apertura_id' => 1,
                'monto_apertura' => 1000.00,
                'hora_apertura' => '2025-12-03 08:00:00',
                'usuario_cierre_id' => null,
                'monto_cierre' => null,
                'monto_esperado' => null,
                'diferencia' => null,
                'hora_cierre' => null,
                'estado' => 'abierta',
                'observaciones' => 'Caja del día actual',
                'movimientos' => [
                    [
                        'categoria_id' => 1,
                        'tipo' => 'ingreso',
                        'monto' => 1500.00,
                        'concepto' => 'Venta de fierro de primera',
                        'observaciones' => null,
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-12-03 10:00:00',
                    ],
                    [
                        'categoria_id' => 2, // Servicios de Recolección
                        'tipo' => 'ingreso',
                        'monto' => 800.00,
                        'concepto' => 'Servicio de recolección a domicilio',
                        'observaciones' => 'Cliente zona norte',
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-12-03 11:30:00',
                    ],
                    [
                        'categoria_id' => 6,
                        'tipo' => 'egreso',
                        'monto' => 150.00,
                        'concepto' => 'Diesel para camión',
                        'observaciones' => null,
                        'responsable_id' => 1,
                        'fecha_hora' => '2025-12-03 12:00:00',
                    ],
                ],
            ],
        ];

        foreach ($cajas as $cajaData) {
            $movimientos = $cajaData['movimientos'];
            unset($cajaData['movimientos']);

            // Crear la caja
            $caja = \App\Models\Caja::create($cajaData);

            // Crear los movimientos
            foreach ($movimientos as $movimiento) {
                $movimiento['caja_id'] = $caja->id;
                \App\Models\MovimientoCaja::create($movimiento);
            }
        }
    }
}
