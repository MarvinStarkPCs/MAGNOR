<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'material_id', 'tipo', 'kg', 'kg_antes', 'kg_despues',
        'referencia_id', 'referencia_type',
        'user_id', 'fecha', 'observaciones',
    ];

    protected $casts = [
        'kg'        => 'float',
        'kg_antes'  => 'float',
        'kg_despues'=> 'float',
        'fecha'     => 'date',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function referencia(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
