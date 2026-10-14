<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    public $timestamps = false;
    protected $table = 'materiales';
    protected $fillable = ['familia_id', 'codigo_sui', 'nombre', 'precio_actual', 'activo'];

    protected $casts = [
        'precio_actual' => 'decimal:2',
        'activo'        => 'boolean',
    ];

    public function familia(): BelongsTo
    {
        return $this->belongsTo(FamiliaMaterial::class, 'familia_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleRecibo::class, 'material_id');
    }

    public function historialPrecios(): HasMany
    {
        return $this->hasMany(HistorialPrecio::class, 'material_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
