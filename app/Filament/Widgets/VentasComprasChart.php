<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class VentasComprasChart extends Widget
{
    protected static string $view = 'filament.widgets.ventas-compras-chart';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getData(): array
    {
        // Aquí puedes obtener datos reales de la base de datos
        return [
            'ventas' => [15000, 22000, 18000, 28000, 32000, 25000],
            'compras' => [12000, 18000, 15000, 21000, 25000, 20000],
            'labels' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
        ];
    }
}
