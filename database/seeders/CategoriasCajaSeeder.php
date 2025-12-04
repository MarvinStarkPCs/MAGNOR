<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriasCajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            // Categorías de Ingreso
            [
                'nombre' => 'Venta de Chatarra',
                'tipo' => 'ingreso',
                'descripcion' => 'Ingresos por venta de materiales reciclables',
                'activo' => true,
            ],
            [
                'nombre' => 'Servicios de Recolección',
                'tipo' => 'ingreso',
                'descripcion' => 'Ingresos por servicios de recolección de chatarra',
                'activo' => true,
            ],
            [
                'nombre' => 'Venta de Equipo',
                'tipo' => 'ingreso',
                'descripcion' => 'Ingresos por venta de equipo o maquinaria',
                'activo' => true,
            ],
            [
                'nombre' => 'Otros Ingresos',
                'tipo' => 'ingreso',
                'descripcion' => 'Ingresos diversos no clasificados',
                'activo' => true,
            ],

            // Categorías de Egreso
            [
                'nombre' => 'Compra de Material',
                'tipo' => 'egreso',
                'descripcion' => 'Pagos por compra de chatarra a proveedores',
                'activo' => true,
            ],
            [
                'nombre' => 'Combustible',
                'tipo' => 'egreso',
                'descripcion' => 'Gastos en gasolina, diesel y combustibles',
                'activo' => true,
            ],
            [
                'nombre' => 'Nómina y Sueldos',
                'tipo' => 'egreso',
                'descripcion' => 'Pagos de sueldos y prestaciones a empleados',
                'activo' => true,
            ],
            [
                'nombre' => 'Servicios Públicos',
                'tipo' => 'egreso',
                'descripcion' => 'Pagos de luz, agua, teléfono e internet',
                'activo' => true,
            ],
            [
                'nombre' => 'Mantenimiento',
                'tipo' => 'egreso',
                'descripcion' => 'Gastos en mantenimiento de equipo y vehículos',
                'activo' => true,
            ],
            [
                'nombre' => 'Papelería y Oficina',
                'tipo' => 'egreso',
                'descripcion' => 'Gastos en materiales de oficina y papelería',
                'activo' => true,
            ],
            [
                'nombre' => 'Arrendamiento',
                'tipo' => 'egreso',
                'descripcion' => 'Pago de renta de inmueble o terreno',
                'activo' => true,
            ],
            [
                'nombre' => 'Transporte y Fletes',
                'tipo' => 'egreso',
                'descripcion' => 'Gastos en transporte y servicios de carga',
                'activo' => true,
            ],
            [
                'nombre' => 'Herramientas y Equipo',
                'tipo' => 'egreso',
                'descripcion' => 'Compra de herramientas y equipo menor',
                'activo' => true,
            ],
            [
                'nombre' => 'Impuestos y Licencias',
                'tipo' => 'egreso',
                'descripcion' => 'Pago de impuestos, permisos y licencias',
                'activo' => true,
            ],
            [
                'nombre' => 'Comidas y Viáticos',
                'tipo' => 'egreso',
                'descripcion' => 'Gastos en alimentación y viáticos',
                'activo' => true,
            ],
            [
                'nombre' => 'Otros Egresos',
                'tipo' => 'egreso',
                'descripcion' => 'Gastos diversos no clasificados',
                'activo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            \App\Models\CategoriaCaja::create($categoria);
        }
    }
}
