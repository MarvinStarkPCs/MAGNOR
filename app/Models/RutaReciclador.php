<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RutaReciclador extends Model
{
    public $timestamps = false;
    protected $table = 'rutas_reciclador';
    protected $fillable = ['reciclador_id', 'dia_id', 'descripcion'];

    protected static array $DIAS = [
        1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
        4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo',
    ];

    public function reciclador(): BelongsTo
    {
        return $this->belongsTo(Reciclador::class, 'reciclador_id');
    }

    public function getDiaNombreAttribute(): string
    {
        return self::$DIAS[$this->dia_id] ?? '';
    }
}
