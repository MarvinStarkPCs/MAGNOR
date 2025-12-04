<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Construcciones y Edificaciones del Sur S.A.S.',
                'documento' => 'NIT: 900.111.222-3',
                'telefono' => '601-234-5678',
                'direccion' => 'Av. El Dorado #78-90, Bogotá',
            ],
            [
                'nombre' => 'Industrias Metalúrgicas Colombianas',
                'documento' => 'NIT: 800.222.333-4',
                'telefono' => '604-345-6789',
                'direccion' => 'Parque Industrial Oriente, Nave 15, Medellín',
            ],
            [
                'nombre' => 'Taller Mecánico El Progreso',
                'documento' => 'NIT: 900.333.444-5',
                'telefono' => '602-456-7890',
                'direccion' => 'Calle 5 #45-67, Cali',
            ],
            [
                'nombre' => 'Carlos Alberto Jiménez Ruiz',
                'documento' => 'CC: 1.012.345.678',
                'telefono' => '317-567-8901',
                'direccion' => 'Cra 30 #12-34, Bucaramanga',
            ],
            [
                'nombre' => 'Fundidora del Norte S.A.S.',
                'documento' => 'NIT: 800.444.555-6',
                'telefono' => '605-678-9012',
                'direccion' => 'Autopista Norte Km 8.5, Barranquilla',
            ],
            [
                'nombre' => 'Autopartes y Refacciones del Valle',
                'documento' => 'NIT: 900.555.666-7',
                'telefono' => '602-789-0123',
                'direccion' => 'Calle 70 #23-45, Cali',
            ],
            [
                'nombre' => 'Ana Patricia Vega Moreno',
                'documento' => 'CC: 1.098.234.567',
                'telefono' => '313-890-1234',
                'direccion' => 'Cra 15 #56-78, Cartagena',
            ],
            [
                'nombre' => 'Herrería y Cancelería Moderna',
                'documento' => 'NIT: 900.666.777-8',
                'telefono' => '601-901-2345',
                'direccion' => 'Av. Boyacá #89-01, Bogotá',
            ],
            [
                'nombre' => 'Exportadora de Metales Internacionales',
                'documento' => 'NIT: 800.777.888-9',
                'telefono' => '605-012-3456',
                'direccion' => 'Zona Portuaria, Bodega 23, Barranquilla',
            ],
            [
                'nombre' => 'Miguel Ángel Castillo Pérez',
                'documento' => 'CC: 1.077.345.678',
                'telefono' => '318-123-4567',
                'direccion' => 'Calle 85 #34-56, Bogotá',
            ],
            [
                'nombre' => 'Recicladora Industrial del Pacífico',
                'documento' => 'NIT: 900.888.999-0',
                'telefono' => '602-234-5678',
                'direccion' => 'Zona Industrial Poniente, Lote 45, Cali',
            ],
            [
                'nombre' => 'Transformadora de Aluminio del Cauca',
                'documento' => 'NIT: 800.999.000-1',
                'telefono' => '602-345-6789',
                'direccion' => 'Parque Industrial Sur, Nave 8, Popayán',
            ],
            [
                'nombre' => 'Laura Fernanda González Soto',
                'documento' => 'CC: 1.082.456.789',
                'telefono' => '314-456-7890',
                'direccion' => 'Cra 27 #67-89, Pereira',
            ],
            [
                'nombre' => 'Fabricaciones Metálicas del Santander',
                'documento' => 'NIT: 900.000.111-2',
                'telefono' => '607-567-8901',
                'direccion' => 'Autopista Norte Km 12.3, Bucaramanga',
            ],
            [
                'nombre' => 'Comercial de Chatarra y Reciclaje',
                'documento' => 'NIT: 800.111.222-4',
                'telefono' => '604-678-9012',
                'direccion' => 'Calle 52 #90-12, Medellín',
            ],
        ];

        foreach ($clientes as $cliente) {
            \App\Models\Cliente::create($cliente);
        }
    }
}
