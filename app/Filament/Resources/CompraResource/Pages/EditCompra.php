<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use App\Models\Material;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompra extends EditRecord
{
    protected static string $resource = CompraResource::class;

    protected $oldDetalles = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function () {
                    // Revert stock when deleting purchase
                    $compra = $this->record;
                    foreach ($compra->detalles as $detalle) {
                        $material = Material::find($detalle->material_id);
                        if ($material) {
                            $material->decrement('stock', $detalle->cantidad);
                        }
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Store old detalles before editing
        $this->oldDetalles = $this->record->detalles->keyBy('id')->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Calculate total from detalles
        $total = 0;
        if (isset($data['detalles']) && is_array($data['detalles'])) {
            foreach ($data['detalles'] as $detalle) {
                $total += $detalle['subtotal'] ?? 0;
            }
        }
        $data['total'] = $total;

        return $data;
    }

    protected function afterSave(): void
    {
        // Revert old stock values
        foreach ($this->oldDetalles as $oldDetalle) {
            $material = Material::find($oldDetalle['material_id']);
            if ($material) {
                $material->decrement('stock', $oldDetalle['cantidad']);
            }
        }

        // Apply new stock values
        $compra = $this->record->fresh();
        foreach ($compra->detalles as $detalle) {
            $material = Material::find($detalle->material_id);
            if ($material) {
                $material->increment('stock', $detalle->cantidad);
            }
        }
    }
}
