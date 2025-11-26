<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Inventario extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string $view = 'filament.pages.inventario';
    protected static ?string $title = 'Inventario';
    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 3;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\InventarioOverview::class,
            \App\Filament\Widgets\InventarioTable::class,
        ];
    }
}
