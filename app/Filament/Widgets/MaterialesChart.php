<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Material;

class MaterialesChart extends Widget
{
    protected static string $view = 'filament.widgets.materiales-chart';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getData(): array
    {
        $materiales = Material::orderBy('stock', 'desc')->limit(6)->get();

        return [
            'labels' => $materiales->pluck('nombre')->toArray(),
            'values' => $materiales->pluck('stock')->toArray(),
        ];
    }
}
