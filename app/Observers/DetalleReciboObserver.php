<?php

namespace App\Observers;

use App\Models\DetalleRecibo;
use App\Models\Inventario;

class DetalleReciboObserver
{
    /**
     * Al crear un detalle (nuevo recibo o edición): suma kg_neto al inventario.
     */
    public function created(DetalleRecibo $detalle): void
    {
        // Solo si el recibo no está anulado
        if ($detalle->recibo?->anulado) {
            return;
        }

        Inventario::sumar(
            materialId: $detalle->material_id,
            kg: (float) $detalle->kg_neto,
            tipo: 'entrada',
            referencia: $detalle->recibo,
            observaciones: "Recibo #{$detalle->recibo?->numero}",
            fecha: $detalle->recibo?->fecha?->toDateString(),
        );
    }

    /**
     * Al eliminar un detalle (edición del recibo): revierte los kg del inventario.
     */
    public function deleted(DetalleRecibo $detalle): void
    {
        // Si el recibo está anulado, el anular() ya gestiona el inventario
        if ($detalle->recibo?->anulado) {
            return;
        }

        Inventario::restar(
            materialId: $detalle->material_id,
            kg: (float) $detalle->kg_neto,
            referencia: $detalle->recibo,
            observaciones: "Reversa edición recibo #{$detalle->recibo?->numero}",
            fecha: now()->toDateString(),
        );
    }
}
