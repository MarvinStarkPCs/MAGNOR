<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reciclador extends Model
{
    protected $table = 'recicladores';
    protected $fillable = [
        'tipo_documento_id', 'documento', 'nombre', 'telefono',
        'direccion', 'municipio_id', 'activo', 'vehiculo_id',
        'numero_sitio_destino', 'tipo_sitio_destino_id',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function rutas(): HasMany
    {
        return $this->hasMany(RutaReciclador::class, 'reciclador_id')->orderBy('dia_id');
    }

    public function recibos(): HasMany
    {
        return $this->hasMany(Recibo::class, 'reciclador_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'reciclador_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} ({$this->documento})";
    }
}
