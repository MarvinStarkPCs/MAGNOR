<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompras extends ListRecords
{
    protected static string $resource = CompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('nueva_compra')
                ->label('Nueva Compra')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->url(fn (): string => CompraResource::getUrl('index')),
        ];
    }
}
