<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ConfiguracionEmpresa extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'configuracion_empresa';
    protected $primaryKey = 'clave';
    protected $keyType = 'string';
    protected $fillable = ['clave', 'valor', 'descripcion'];

    public static function get(string $clave, mixed $default = null): mixed
    {
        return Cache::remember("config_{$clave}", 300, fn () =>
            static::where('clave', $clave)->value('valor') ?? $default
        );
    }

    public static function set(string $clave, mixed $valor): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
        Cache::forget("config_{$clave}");
    }
}
