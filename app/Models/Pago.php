<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'reciclador_id', 'recibo_id', 'fecha', 'monto',
        'forma_pago', 'referencia', 'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'float',
    ];

    public function reciclador(): BelongsTo
    {
        return $this->belongsTo(Reciclador::class);
    }

    public function recibo(): BelongsTo
    {
        return $this->belongsTo(Recibo::class);
    }
}
