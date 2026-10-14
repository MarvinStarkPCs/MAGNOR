<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_documento')->insertOrIgnore([
            ['id' => 1, 'codigo' => 'CC',  'descripcion' => 'Cédula de Ciudadanía'],
            ['id' => 2, 'codigo' => 'NIT', 'descripcion' => 'Número de Identificación Tributaria'],
            ['id' => 3, 'codigo' => 'CE',  'descripcion' => 'Cédula de Extranjería'],
            ['id' => 4, 'codigo' => 'PA',  'descripcion' => 'Pasaporte'],
            ['id' => 5, 'codigo' => 'TI',  'descripcion' => 'Tarjeta de Identidad'],
        ]);

        DB::table('dias_semana')->insertOrIgnore([
            ['id' => 1, 'nombre' => 'Lunes',      'abreviatura' => 'LUN'],
            ['id' => 2, 'nombre' => 'Martes',     'abreviatura' => 'MAR'],
            ['id' => 3, 'nombre' => 'Miércoles',  'abreviatura' => 'MIE'],
            ['id' => 4, 'nombre' => 'Jueves',     'abreviatura' => 'JUE'],
            ['id' => 5, 'nombre' => 'Viernes',    'abreviatura' => 'VIE'],
            ['id' => 6, 'nombre' => 'Sábado',     'abreviatura' => 'SAB'],
            ['id' => 7, 'nombre' => 'Domingo',    'abreviatura' => 'DOM'],
        ]);

        DB::table('tipos_sitio_destino')->insertOrIgnore([
            ['id' => 1, 'descripcion' => 'Estación de clasificación y aprovechamiento (ECA)'],
            ['id' => 2, 'descripcion' => 'Planta de aprovechamiento'],
            ['id' => 3, 'descripcion' => 'Relleno sanitario'],
            ['id' => 4, 'descripcion' => 'Celda de seguridad'],
            ['id' => 5, 'descripcion' => 'Bodega de almacenamiento'],
        ]);

        DB::table('municipios')->insertOrIgnore([
            ['nombre' => 'Ocaña',       'departamento' => 'Norte de Santander', 'codigo_dane' => '54498'],
            ['nombre' => 'Ábrego',      'departamento' => 'Norte de Santander', 'codigo_dane' => '54003'],
            ['nombre' => 'Convención',  'departamento' => 'Norte de Santander', 'codigo_dane' => '54206'],
            ['nombre' => 'El Carmen',   'departamento' => 'Norte de Santander', 'codigo_dane' => '54245'],
            ['nombre' => 'La Playa',    'departamento' => 'Norte de Santander', 'codigo_dane' => '54347'],
            ['nombre' => 'San Calixto', 'departamento' => 'Norte de Santander', 'codigo_dane' => '54670'],
            ['nombre' => 'Teorama',     'departamento' => 'Norte de Santander', 'codigo_dane' => '54800'],
        ]);

        DB::table('vehiculos')->insertOrIgnore([
            ['placa' => '999',    'tipo' => 'Sin vehículo / carreta manual', 'activo' => true],
            ['placa' => 'ZOD786', 'tipo' => 'Vehículo motorizado',           'activo' => true],
        ]);

        $config = [
            ['clave' => 'empresa_nombre',           'valor' => 'ARECUPERAR',            'descripcion' => 'Razón social'],
            ['clave' => 'empresa_municipio',        'valor' => 'Ocaña',                 'descripcion' => 'Municipio sede'],
            ['clave' => 'empresa_departamento',     'valor' => 'Norte de Santander',    'descripcion' => 'Departamento'],
            ['clave' => 'empresa_telefono',         'valor' => '',                      'descripcion' => 'Teléfono'],
            ['clave' => 'empresa_direccion',        'valor' => '',                      'descripcion' => 'Dirección'],
            ['clave' => 'nueca',                    'valor' => '1286754498',            'descripcion' => 'Número Único Establecimiento Comercial (SUI)'],
            ['clave' => 'numacro',                  'valor' => '449054498',             'descripcion' => 'Número de Macroruta (SUI)'],
            ['clave' => 'numero_sitio_destino',     'valor' => '206654498',             'descripcion' => 'Código sitio destino del rechazo'],
            ['clave' => 'tipo_sitio_destino',       'valor' => '1',                     'descripcion' => 'Tipo de sitio de destino'],
            ['clave' => 'placa_sin_vehiculo',       'valor' => '999',                   'descripcion' => 'Placa para recicladores sin vehículo'],
            ['clave' => 'retefuente_porcentaje',    'valor' => '0',                     'descripcion' => 'Porcentaje retención en la fuente (%)'],
            ['clave' => 'porcentaje_rechazo_def',   'valor' => '1',                     'descripcion' => 'Porcentaje de rechazo por defecto (%)'],
        ];

        foreach ($config as $item) {
            DB::table('configuracion_empresa')->insertOrIgnore($item);
        }
    }
}
