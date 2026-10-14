<?php

namespace Database\Seeders;

use App\Models\Municipio;
use App\Models\Reciclador;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class RecicladoresSeeder extends Seeder
{
    public function run(): void
    {
        $ocana   = Municipio::where('nombre', 'Ocaña')->first()?->id;
        $veh999  = Vehiculo::where('placa', '999')->first()?->id;
        $vehZod  = Vehiculo::where('placa', 'ZOD786')->first()?->id;

        $recicladores = [
            ['documento' => '5528028',    'nombre' => 'ALEJO PÉREZ PÉREZ',                   'telefono' => '3204433879', 'direccion' => 'KDX 284-991 Barrio Junín',           'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '12520822',   'nombre' => 'ALFONSO PABÓN GUERRERO',              'telefono' => null,         'direccion' => 'KDX 217-307 Brr. El Dorado',          'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '88141122',   'nombre' => 'ALIRIO ALBERTO PABÓN',                'telefono' => '3167291735', 'direccion' => 'KDX 088-580 Brr. Las Colinas',        'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '13357815',   'nombre' => 'ALVARO DE JESUS PICON MANTILLA',      'telefono' => null,         'direccion' => 'Barrio Simon Bolivar',                'municipio_id' => null,   'vehiculo_id' => $veh999],
            ['documento' => '27764436',   'nombre' => 'ANTONIA QUINTERO CALDERÓN',           'telefono' => '3175597912', 'direccion' => 'Brr. Simón Bolívar Parte Baja',       'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '6227271',    'nombre' => 'ARNOLDO CELESTINO HERRERA SÁNCHEZ',   'telefono' => '3180215040', 'direccion' => 'Brr la perla',                        'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '13084631',   'nombre' => 'BERTHA YADIRA COLMENARES ARROLLO',    'telefono' => '3212675643', 'direccion' => 'Brr la perla',                        'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '5974496',    'nombre' => 'BRITHEY ESMERALDA GUTIERREZ VERA',    'telefono' => '3203218346', 'direccion' => 'Hatillo - Transparencia 1',           'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '5084647',    'nombre' => 'CARLOS ARTURO MEJÍA ROJAS',           'telefono' => '3125096017', 'direccion' => 'KDX 906-360 Brr. El Dorado',          'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '88281105',   'nombre' => 'CARLOS VICENTE MARTÍNEZ',             'telefono' => '3184091938', 'direccion' => 'Cra. 10A-32 Gustavo Alayon',          'municipio_id' => null,   'vehiculo_id' => $veh999],
            ['documento' => '37317139',   'nombre' => 'CARMEN CECILIA MEZA ASCANIO',         'telefono' => '3227226362', 'direccion' => 'KDX 909-120 Brr. El Dorado',          'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '37318870',   'nombre' => 'CARMEN OLINTA MELO CLARO',            'telefono' => '3138370065', 'direccion' => 'KDX 114-025 Brr. El Carmen',          'municipio_id' => null,   'vehiculo_id' => $veh999],
            ['documento' => '1004864110', 'nombre' => 'CARMENZA RINCON PACHECO',             'telefono' => '3184592781', 'direccion' => 'KDX 265-240 Brr. Los Fundadores',     'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '5470631',    'nombre' => 'CRISTO FERNANDO AMAYA JULIO',         'telefono' => '3222863338', 'direccion' => 'Brr el peñon',                        'municipio_id' => $ocana, 'vehiculo_id' => $vehZod],
            ['documento' => '27745768',   'nombre' => 'DELMIRA BALAGUERA',                   'telefono' => '3170935963', 'direccion' => null,                                  'municipio_id' => null,   'vehiculo_id' => $veh999],
            ['documento' => '1091679067', 'nombre' => 'DENIER ELIECER CARRASCAL SÁNCHEZ',   'telefono' => '3218161229', 'direccion' => 'CALL 13 # 24A-14 B. EL PEÑON',        'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '1064840915', 'nombre' => 'DIANA MILEIDY PUENTE CABALLERO',      'telefono' => '3227883197', 'direccion' => 'BR. LA PERLA',                        'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '5975221',    'nombre' => 'EDUARDO JOSÉ BASTIDAS',               'telefono' => '3134663843', 'direccion' => 'Hatillo - Transparencia 1',           'municipio_id' => null,   'vehiculo_id' => $veh999],
            ['documento' => '22828163',   'nombre' => 'ESMERALDA VERA DÍAZ',                 'telefono' => '3203167834', 'direccion' => 'Brr. Juan XXIII',                     'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '88142844',   'nombre' => 'EUCLIDES BAUTISTA BAYONA',            'telefono' => '3157863157', 'direccion' => null,                                  'municipio_id' => null,   'vehiculo_id' => $veh999],
            ['documento' => '5459799',    'nombre' => 'EUSEBIO BAYONA GUERERO',              'telefono' => '3157863157', 'direccion' => 'KDX 295-390 Brr. Simón Bolívar',      'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '13177382',   'nombre' => 'FABIO DANILO PALLARES CARRASCAL',     'telefono' => '3242592568', 'direccion' => 'KDX 112-350 Brr. El Carmen',          'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '1757979',    'nombre' => 'FRANCISCO ANTONIO ILLERA BARBOSA',    'telefono' => '3124722539', 'direccion' => 'BR. JUAN 23',                         'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '6795346',    'nombre' => 'FREDDY REYES',                        'telefono' => '3134644769', 'direccion' => 'KDX 278-260 Brr. Juan XXIII',         'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '13360501',   'nombre' => 'GABRIEL ANGEL PEÑA TRILLOS',          'telefono' => null,         'direccion' => 'KDX 558-535 Brr. El Carmen',          'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '1148441078', 'nombre' => 'GERMÁN ALONSO PÁEZ TARAZONA',         'telefono' => '3163214416', 'direccion' => 'KDX 311-130 Brr. Simón Bolívar',      'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '13363957',   'nombre' => 'HERMES ANTONIO QUINTERO',             'telefono' => '3202965397', 'direccion' => 'KDX 558-389 Brr. El Carmen',          'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '88144580',   'nombre' => 'JAIRO ALFONSO RUEDAS',                'telefono' => '3124875596', 'direccion' => 'KDX 405-300 Brr. Libardo Alonso',     'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '88135539',   'nombre' => 'JAIRO GALEANO PRADA',                 'telefono' => '3184667077', 'direccion' => 'Cra.28 D 9-29 Barrio el Carmen',      'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
            ['documento' => '37314486',   'nombre' => 'MARIA ESPERANZA PACHECO',             'telefono' => '3167291735', 'direccion' => 'KDX 088-580 Brr. Colina del Tanque',  'municipio_id' => $ocana, 'vehiculo_id' => $veh999],
        ];

        foreach ($recicladores as $r) {
            Reciclador::firstOrCreate(
                ['documento' => $r['documento']],
                array_merge($r, ['tipo_documento_id' => 1, 'numero_sitio_destino' => '206654498', 'tipo_sitio_destino_id' => 1])
            );
        }
    }
}
