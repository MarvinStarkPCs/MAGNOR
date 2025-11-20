<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Proveedor;
use App\Models\Compra;
use App\Models\DetalleCompra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Materiales
        $materiales = [
            [
                'nombre' => 'Cobre',
                'descripcion' => 'Cobre en diferentes presentaciones (cables, tubos, etc.)',
                'unidad_medida' => 'kg',
                'precio_compra' => 8500,
                'precio_venta' => 12000,
                'stock' => 0,
            ],
            [
                'nombre' => 'Aluminio',
                'descripcion' => 'Aluminio reciclado de ventanas, latas, perfiles',
                'unidad_medida' => 'kg',
                'precio_compra' => 2800,
                'precio_venta' => 4200,
                'stock' => 0,
            ],
            [
                'nombre' => 'Hierro',
                'descripcion' => 'Hierro y acero de diferentes calibres',
                'unidad_medida' => 'kg',
                'precio_compra' => 500,
                'precio_venta' => 850,
                'stock' => 0,
            ],
            [
                'nombre' => 'Bronce',
                'descripcion' => 'Bronce de grifería y accesorios',
                'unidad_medida' => 'kg',
                'precio_compra' => 6500,
                'precio_venta' => 9500,
                'stock' => 0,
            ],
            [
                'nombre' => 'Acero Inoxidable',
                'descripcion' => 'Acero inoxidable de cocina y equipos',
                'unidad_medida' => 'kg',
                'precio_compra' => 3200,
                'precio_venta' => 5000,
                'stock' => 0,
            ],
            [
                'nombre' => 'Baterías de Auto',
                'descripcion' => 'Baterías usadas de vehículos',
                'unidad_medida' => 'unidad',
                'precio_compra' => 15000,
                'precio_venta' => 25000,
                'stock' => 0,
            ],
            [
                'nombre' => 'Cable Eléctrico',
                'descripcion' => 'Cable eléctrico de cobre con aislamiento',
                'unidad_medida' => 'metro',
                'precio_compra' => 1200,
                'precio_venta' => 2000,
                'stock' => 0,
            ],
            [
                'nombre' => 'Chatarra Pesada',
                'descripcion' => 'Chatarra de maquinaria y estructuras grandes',
                'unidad_medida' => 'tonelada',
                'precio_compra' => 450000,
                'precio_venta' => 650000,
                'stock' => 0,
            ],
            [
                'nombre' => 'Plástico PET',
                'descripcion' => 'Botellas y envases de plástico PET',
                'unidad_medida' => 'kg',
                'precio_compra' => 800,
                'precio_venta' => 1400,
                'stock' => 0,
            ],
            [
                'nombre' => 'Aceite Usado',
                'descripcion' => 'Aceite de motor usado para reciclaje',
                'unidad_medida' => 'litro',
                'precio_compra' => 500,
                'precio_venta' => 1000,
                'stock' => 0,
            ],
        ];

        foreach ($materiales as $materialData) {
            Material::create(array_merge($materialData, [
                'precio_dia_compra' => $materialData['precio_compra'],
                'precio_dia_venta' => $materialData['precio_venta'],
                'fecha_actualizacion_precio' => now()->format('Y-m-d'),
            ]));
        }

        // Crear Proveedores y Recicladores
        $proveedores = [
            [
                'nombre' => 'Reciclajes El Progreso',
                'documento' => '900123456-7',
                'telefono' => '3101234567',
                'direccion' => 'Calle 45 #23-67, Bogotá',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Juan Carlos Martínez',
                'documento' => '1023456789',
                'telefono' => '3209876543',
                'direccion' => 'Carrera 12 #34-56, Medellín',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Chatarra Industrial S.A.S',
                'documento' => '900987654-3',
                'telefono' => '3156789012',
                'direccion' => 'Avenida 68 #45-23, Bogotá',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'María Elena Rodríguez',
                'documento' => '1045678912',
                'telefono' => '3187654321',
                'direccion' => 'Calle 100 #15-20, Cali',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Metales del Norte',
                'documento' => '900555444-1',
                'telefono' => '3201122334',
                'direccion' => 'Carrera 50 #80-45, Barranquilla',
                'es_reciclador' => false,
            ],
            [
                'nombre' => 'Pedro Ramírez',
                'documento' => '1067890123',
                'telefono' => '3145556677',
                'direccion' => 'Calle 30 #20-10, Cartagena',
                'es_reciclador' => true,
            ],
            [
                'nombre' => 'Eco Reciclaje Ltda',
                'documento' => '900777888-9',
                'telefono' => '3169998877',
                'direccion' => 'Avenida 80 #50-30, Bucaramanga',
                'es_reciclador' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }

        // Crear Compras con Detalles
        $this->crearComprasConDetalles();

        echo "\n✓ Datos de prueba creados exitosamente\n";
        echo "  - " . Material::count() . " materiales\n";
        echo "  - " . Proveedor::count() . " proveedores\n";
        echo "  - " . Compra::count() . " compras\n";
        echo "  - " . DetalleCompra::count() . " detalles de compra\n\n";
    }

    private function crearComprasConDetalles(): void
    {
        $materiales = Material::all();
        $proveedores = Proveedor::all();

        // Compra 1: Compra grande de cobre y aluminio
        $compra1 = Compra::create([
            'proveedor_id' => $proveedores->where('nombre', 'Reciclajes El Progreso')->first()->id,
            'fecha_compra' => now()->subDays(15)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => 'Compra de materiales variados, excelente calidad',
        ]);

        $detalles1 = [
            [
                'material_id' => $materiales->where('nombre', 'Cobre')->first()->id,
                'cantidad' => 45.5,
                'precio_unitario' => 8500,
                'subtotal' => 45.5 * 8500,
            ],
            [
                'material_id' => $materiales->where('nombre', 'Aluminio')->first()->id,
                'cantidad' => 120.0,
                'precio_unitario' => 2800,
                'subtotal' => 120.0 * 2800,
            ],
        ];

        foreach ($detalles1 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra1->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra1->update(['total' => collect($detalles1)->sum('subtotal')]);

        // Compra 2: Compra de baterías
        $compra2 = Compra::create([
            'proveedor_id' => $proveedores->where('nombre', 'Juan Carlos Martínez')->first()->id,
            'fecha_compra' => now()->subDays(12)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => 'Baterías en buen estado',
        ]);

        $detalles2 = [
            [
                'material_id' => $materiales->where('nombre', 'Baterías de Auto')->first()->id,
                'cantidad' => 24,
                'precio_unitario' => 15000,
                'subtotal' => 24 * 15000,
            ],
        ];

        foreach ($detalles2 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra2->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra2->update(['total' => collect($detalles2)->sum('subtotal')]);

        // Compra 3: Compra de chatarra pesada
        $compra3 = Compra::create([
            'proveedor_id' => $proveedores->where('nombre', 'Chatarra Industrial S.A.S')->first()->id,
            'fecha_compra' => now()->subDays(10)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => 'Chatarra de maquinaria industrial',
        ]);

        $detalles3 = [
            [
                'material_id' => $materiales->where('nombre', 'Chatarra Pesada')->first()->id,
                'cantidad' => 3.5,
                'precio_unitario' => 450000,
                'subtotal' => 3.5 * 450000,
            ],
            [
                'material_id' => $materiales->where('nombre', 'Hierro')->first()->id,
                'cantidad' => 250.0,
                'precio_unitario' => 500,
                'subtotal' => 250.0 * 500,
            ],
        ];

        foreach ($detalles3 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra3->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra3->update(['total' => collect($detalles3)->sum('subtotal')]);

        // Compra 4: Cable eléctrico
        $compra4 = Compra::create([
            'proveedor_id' => $proveedores->where('nombre', 'María Elena Rodríguez')->first()->id,
            'fecha_compra' => now()->subDays(8)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => null,
        ]);

        $detalles4 = [
            [
                'material_id' => $materiales->where('nombre', 'Cable Eléctrico')->first()->id,
                'cantidad' => 450.0,
                'precio_unitario' => 1200,
                'subtotal' => 450.0 * 1200,
            ],
        ];

        foreach ($detalles4 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra4->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra4->update(['total' => collect($detalles4)->sum('subtotal')]);

        // Compra 5: Compra mixta
        $compra5 = Compra::create([
            'proveedor_id' => $proveedores->where('nombre', 'Pedro Ramírez')->first()->id,
            'fecha_compra' => now()->subDays(5)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => 'Materiales variados de demolición',
        ]);

        $detalles5 = [
            [
                'material_id' => $materiales->where('nombre', 'Bronce')->first()->id,
                'cantidad' => 18.5,
                'precio_unitario' => 6500,
                'subtotal' => 18.5 * 6500,
            ],
            [
                'material_id' => $materiales->where('nombre', 'Acero Inoxidable')->first()->id,
                'cantidad' => 65.0,
                'precio_unitario' => 3200,
                'subtotal' => 65.0 * 3200,
            ],
            [
                'material_id' => $materiales->where('nombre', 'Aluminio')->first()->id,
                'cantidad' => 88.5,
                'precio_unitario' => 2800,
                'subtotal' => 88.5 * 2800,
            ],
        ];

        foreach ($detalles5 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra5->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra5->update(['total' => collect($detalles5)->sum('subtotal')]);

        // Compra 6: Plásticos y aceite
        $compra6 = Compra::create([
            'proveedor_id' => $proveedores->where('nombre', 'Eco Reciclaje Ltda')->first()->id,
            'fecha_compra' => now()->subDays(3)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => 'Materiales para reciclaje ecológico',
        ]);

        $detalles6 = [
            [
                'material_id' => $materiales->where('nombre', 'Plástico PET')->first()->id,
                'cantidad' => 350.0,
                'precio_unitario' => 800,
                'subtotal' => 350.0 * 800,
            ],
            [
                'material_id' => $materiales->where('nombre', 'Aceite Usado')->first()->id,
                'cantidad' => 180.0,
                'precio_unitario' => 500,
                'subtotal' => 180.0 * 500,
            ],
        ];

        foreach ($detalles6 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra6->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra6->update(['total' => collect($detalles6)->sum('subtotal')]);

        // Compra 7: Sin proveedor (compra directa)
        $compra7 = Compra::create([
            'proveedor_id' => null,
            'fecha_compra' => now()->subDays(1)->format('Y-m-d'),
            'total' => 0,
            'observaciones' => 'Compra directa sin proveedor registrado',
        ]);

        $detalles7 = [
            [
                'material_id' => $materiales->where('nombre', 'Hierro')->first()->id,
                'cantidad' => 75.0,
                'precio_unitario' => 500,
                'subtotal' => 75.0 * 500,
            ],
            [
                'material_id' => $materiales->where('nombre', 'Cobre')->first()->id,
                'cantidad' => 12.5,
                'precio_unitario' => 8500,
                'subtotal' => 12.5 * 8500,
            ],
        ];

        foreach ($detalles7 as $detalle) {
            DetalleCompra::create(array_merge($detalle, ['compra_id' => $compra7->id]));
            $material = Material::find($detalle['material_id']);
            $material->increment('stock', $detalle['cantidad']);
        }

        $compra7->update(['total' => collect($detalles7)->sum('subtotal')]);
    }
}
