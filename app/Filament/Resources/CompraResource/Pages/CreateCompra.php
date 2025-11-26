<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use App\Models\Material;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCompra extends CreateRecord
{
    protected static string $resource = CompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('ver_compras')
                ->label('Ver Compras')
                ->icon('heroicon-o-list-bullet')
                ->color('info')
                ->url(fn (): string => CompraResource::getUrl('list')),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set automatic date
        $data['fecha_compra'] = now()->format('Y-m-d');

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

    protected function afterCreate(): void
    {
        // Update inventory stock after creating purchase
        $compra = $this->record;

        foreach ($compra->detalles as $detalle) {
            $material = Material::find($detalle->material_id);
            if ($material) {
                $material->increment('stock', $detalle->cantidad);
            }
        }
    }
}
