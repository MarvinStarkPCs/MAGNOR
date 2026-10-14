<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Inventario extends Model
{
    protected $table = 'inventario';

    protected $fillable = [
        'material_id', 'bodega_id', 'cantidad_kg', 'ultima_actualizacion',
    ];

    protected $casts = [
        'cantidad_kg'          => 'float',
        'ultima_actualizacion' => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'material_id', 'material_id');
    }

    public static function sumar(
        int $materialId,
        int $bodegaId,
        float $kg,
        string $tipo = 'entrada',
        ?Model $referencia = null,
        ?string $observaciones = null,
        ?string $fecha = null,
        ?int $bodegaDestinoId = null,
    ): void {
        $stock = static::firstOrCreate(
            ['material_id' => $materialId, 'bodega_id' => $bodegaId],
            ['cantidad_kg' => 0]
        );

        $antes   = (float) $stock->cantidad_kg;
        $despues = max(0, $tipo === 'salida' ? $antes - $kg : $antes + $kg);

        $stock->update([
            'cantidad_kg'          => $despues,
            'ultima_actualizacion' => now(),
        ]);

        MovimientoInventario::create([
            'material_id'       => $materialId,
            'bodega_id'         => $bodegaId,
            'bodega_destino_id' => $bodegaDestinoId,
            'tipo'              => $tipo,
            'kg'                => $kg,
            'kg_antes'          => $antes,
            'kg_despues'        => $despues,
            'referencia_id'     => $referencia?->getKey(),
            'referencia_type'   => $referencia ? get_class($referencia) : null,
            'user_id'           => Auth::id(),
            'fecha'             => $fecha ?? now()->toDateString(),
            'observaciones'     => $observaciones,
        ]);
    }

    public static function restar(
        int $materialId,
        int $bodegaId,
        float $kg,
        ?Model $referencia = null,
        ?string $observaciones = null,
        ?string $fecha = null
    ): void {
        static::sumar($materialId, $bodegaId, $kg, 'salida', $referencia, $observaciones, $fecha);
    }

    public static function transferir(
        int $materialId,
        int $bodegaOrigenId,
        int $bodegaDestinoId,
        float $kg,
        ?string $observaciones = null,
    ): void {
        // Restar del origen y registrar movimiento con referencia al destino
        static::sumar($materialId, $bodegaOrigenId, $kg, 'salida', null, $observaciones, now()->toDateString(), $bodegaDestinoId);
        // Sumar al destino
        static::sumar($materialId, $bodegaDestinoId, $kg, 'entrada', null, $observaciones);
    }
}
