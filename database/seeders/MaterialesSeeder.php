<?php

namespace Database\Seeders;

use App\Models\FamiliaMaterial;
use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialesSeeder extends Seeder
{
    public function run(): void
    {
        $familias = [
            ['nombre' => 'METALES',        'codigo_sui' => 100],
            ['nombre' => 'PAPEL Y CARTÓN', 'codigo_sui' => 200],
            ['nombre' => 'PLÁSTICOS',      'codigo_sui' => 300],
            ['nombre' => 'VIDRIO',         'codigo_sui' => 400],
            ['nombre' => 'RECHAZO',        'codigo_sui' => 700],
        ];

        foreach ($familias as $f) {
            FamiliaMaterial::firstOrCreate(['nombre' => $f['nombre']], $f);
        }

        $materiales = [
            // METALES
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO LATA',    'precio_actual' => 7000],
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO GRUESO',  'precio_actual' => 5500],
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO BLANDO',  'precio_actual' => 5500],
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO PERFIL',  'precio_actual' => 7000],
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO BANDA',   'precio_actual' => 4000],
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO GUAYA',   'precio_actual' => 8000],
            ['familia' => 'METALES', 'codigo_sui' => 101, 'nombre' => 'ALUMINIO RINES',   'precio_actual' => 6500],
            ['familia' => 'METALES', 'codigo_sui' => 102, 'nombre' => 'CHATARRA GRIS',    'precio_actual' => 1200],
            ['familia' => 'METALES', 'codigo_sui' => 102, 'nombre' => 'CHATARRA',         'precio_actual' => 700],
            ['familia' => 'METALES', 'codigo_sui' => 103, 'nombre' => 'COBRE #1',         'precio_actual' => 34000],
            ['familia' => 'METALES', 'codigo_sui' => 103, 'nombre' => 'COBRE #2',         'precio_actual' => 33000],
            ['familia' => 'METALES', 'codigo_sui' => 104, 'nombre' => 'BRONCE',           'precio_actual' => 18000],
            ['familia' => 'METALES', 'codigo_sui' => 105, 'nombre' => 'ANTIMONIO',        'precio_actual' => 5500],
            ['familia' => 'METALES', 'codigo_sui' => 106, 'nombre' => 'ACERO',            'precio_actual' => 2800],
            ['familia' => 'METALES', 'codigo_sui' => 199, 'nombre' => 'OTROS METALES',    'precio_actual' => 5500],
            // PAPEL Y CARTÓN
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 201, 'nombre' => 'ARCHIVO',               'precio_actual' => 700],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 202, 'nombre' => 'CARTÓN',                'precio_actual' => 350],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 203, 'nombre' => 'CUBETAS O PANELES',     'precio_actual' => 50],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 204, 'nombre' => 'PERIÓDICO',             'precio_actual' => 200],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 205, 'nombre' => 'PLEGADIZA',             'precio_actual' => 150],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 206, 'nombre' => 'TETRA PACK',            'precio_actual' => 150],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 207, 'nombre' => 'PLASTIFICADO',          'precio_actual' => 0],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 208, 'nombre' => 'KRAF',                  'precio_actual' => 0],
            ['familia' => 'PAPEL Y CARTÓN', 'codigo_sui' => 299, 'nombre' => 'OTROS PAPEL Y CARTÓN',  'precio_actual' => 350],
            // PLÁSTICOS
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 301, 'nombre' => 'ACRÍLICO',          'precio_actual' => 0],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 302, 'nombre' => 'PASTA',             'precio_actual' => 800],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 302, 'nombre' => 'PASTA NEGRA',       'precio_actual' => 700],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 302, 'nombre' => 'PASTA SILLA',       'precio_actual' => 1500],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 302, 'nombre' => 'PASTA LAVADORA',    'precio_actual' => 1400],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 303, 'nombre' => 'PET TRANSPARENTE',  'precio_actual' => 1000],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 303, 'nombre' => 'PET AMBAR',         'precio_actual' => 300],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 303, 'nombre' => 'PET VERDE',         'precio_actual' => 300],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 303, 'nombre' => 'PET ACEITE',        'precio_actual' => 150],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 303, 'nombre' => 'PET REVUELTO',      'precio_actual' => 500],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 304, 'nombre' => 'PVC TUBO',          'precio_actual' => 300],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 304, 'nombre' => 'PVC LAMINA',        'precio_actual' => 200],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 305, 'nombre' => 'BOLSA',             'precio_actual' => 600],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 306, 'nombre' => 'MANGUERA',          'precio_actual' => 800],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 307, 'nombre' => 'SOPLADO',           'precio_actual' => 700],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 308, 'nombre' => 'POLIPROPILENO',     'precio_actual' => 0],
            ['familia' => 'PLÁSTICOS', 'codigo_sui' => 399, 'nombre' => 'OTROS PLÁSTICOS',   'precio_actual' => 0],
            // VIDRIO
            ['familia' => 'VIDRIO',  'codigo_sui' => 499, 'nombre' => 'OTROS VIDRIOS', 'precio_actual' => 50],
            // RECHAZO
            ['familia' => 'RECHAZO', 'codigo_sui' => 799, 'nombre' => 'RECHAZO',       'precio_actual' => 0],
        ];

        foreach ($materiales as $m) {
            $familia = FamiliaMaterial::where('nombre', $m['familia'])->first();
            Material::firstOrCreate(
                ['nombre' => $m['nombre']],
                ['familia_id' => $familia->id, 'codigo_sui' => $m['codigo_sui'], 'precio_actual' => $m['precio_actual']]
            );
        }
    }
}
