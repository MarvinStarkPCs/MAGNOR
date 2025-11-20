<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventarioOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $materiales = Material::all();

        $totalMateriales = $materiales->count();
        $materialesConStock = $materiales->where('stock', '>', 0)->count();
        $materialesSinStock = $materiales->where('stock', '<=', 0)->count();

        // Calcular valor total del inventario (stock * precio_venta)
        $valorInventario = $materiales->sum(function ($material) {
            return $material->stock * $material->precio_venta;
        });

        // Calcular costo del inventario (stock * precio_compra)
        $costoInventario = $materiales->sum(function ($material) {
            return $material->stock * $material->precio_compra;
        });

        // Material con más stock
        $materialMasStock = $materiales->sortByDesc('stock')->first();

        return [
            Stat::make('Total Materiales', $totalMateriales)
                ->description($materialesConStock . ' con stock disponible')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Valor del Inventario', '$' . number_format($valorInventario, 0))
                ->description('Precio de venta total')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Costo del Inventario', '$' . number_format($costoInventario, 0))
                ->description('Inversión total')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Material Top', $materialMasStock ? $materialMasStock->nombre : 'N/A')
                ->description($materialMasStock ? number_format($materialMasStock->stock, 2) . ' ' . $materialMasStock->unidad_medida : 'Sin stock')
                ->descriptionIcon('heroicon-m-star')
                ->color('info'),
        ];
    }
}
