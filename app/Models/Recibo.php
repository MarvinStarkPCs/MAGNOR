<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recibo extends Model
{
    protected $table = 'recibos';
    protected $fillable = [
        'numero', 'fecha', 'reciclador_id', 'bodega_id',
        'subtotal', 'retefuente', 'observaciones',
        'anulado', 'anulado_en', 'anulado_motivo',
    ];

    protected $casts = [
        'fecha'      => 'date',
        'anulado'    => 'boolean',
        'anulado_en' => 'datetime',
        'subtotal'   => 'decimal:2',
        'retefuente' => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function reciclador(): BelongsTo
    {
        return $this->belongsTo(Reciclador::class, 'reciclador_id');
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleRecibo::class, 'recibo_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'recibo_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('anulado', false);
    }

    public static function proximoNumero(): int
    {
        $max = static::max('numero') ?? 0;
        return $max + 1;
    }

    public function recalcularSubtotal(): void
    {
        $this->subtotal = $this->detalles()->sum(\DB::raw('ROUND(kg_bruto * precio_kg, 2)'));
        $this->save();
    }
}
