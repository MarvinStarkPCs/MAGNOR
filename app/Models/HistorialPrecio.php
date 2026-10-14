<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialPrecio extends Model
{
    public $timestamps = false;
    protected $table = 'historial_precios';
    protected $fillable = ['material_id', 'precio', 'vigente_desde', 'vigente_hasta', 'registrado_por'];

    protected $casts = [
        'precio'        => 'decimal:2',
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
