<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id',
        'categoria_id',
        'tipo',
        'monto',
        'concepto',
        'observaciones',
        'responsable_id',
        'comprobante',
        'fecha_hora',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_hora' => 'datetime',
    ];

    /**
     * Caja a la que pertenece el movimiento
     */
    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    /**
     * Categoría del movimiento
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaCaja::class, 'categoria_id');
    }

    /**
     * Usuario responsable del movimiento
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
