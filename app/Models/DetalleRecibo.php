<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleRecibo extends Model
{
    public $timestamps = false;
    protected $table = 'detalle_recibo';
    protected $fillable = ['recibo_id', 'material_id', 'kg_bruto', 'kg_rechazado', 'precio_kg'];

    protected $casts = [
        'kg_bruto'      => 'decimal:3',
        'kg_rechazado'  => 'decimal:3',
        'kg_neto'       => 'decimal:3',
        'precio_kg'     => 'decimal:2',
        'subtotal'      => 'decimal:2',
    ];

    public function recibo(): BelongsTo
    {
        return $this->belongsTo(Recibo::class, 'recibo_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
