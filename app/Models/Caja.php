<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'fecha',
        'usuario_apertura_id',
        'monto_apertura',
        'hora_apertura',
        'usuario_cierre_id',
        'monto_cierre',
        'monto_esperado',
        'diferencia',
        'hora_cierre',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto_apertura' => 'decimal:2',
        'monto_cierre' => 'decimal:2',
        'monto_esperado' => 'decimal:2',
        'diferencia' => 'decimal:2',
        'hora_apertura' => 'datetime',
        'hora_cierre' => 'datetime',
    ];

    /**
     * Usuario que abrió la caja
     */
    public function usuarioApertura()
    {
        return $this->belongsTo(User::class, 'usuario_apertura_id');
    }

    /**
     * Usuario que cerró la caja
     */
    public function usuarioCierre()
    {
        return $this->belongsTo(User::class, 'usuario_cierre_id');
    }

    /**
     * Movimientos de esta caja
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class, 'caja_id');
    }
}
