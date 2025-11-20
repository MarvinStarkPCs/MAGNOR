<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaDiaria extends Model
{
    protected $table = 'caja_diaria';

    protected $fillable = [
        'fecha',
        'monto_inicial',
        'monto_final',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto_inicial' => 'decimal:2',
        'monto_final' => 'decimal:2',
    ];
}
