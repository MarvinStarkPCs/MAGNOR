<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCompra extends ViewRecord
{
    protected static string $resource = CompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Imprimir Factura')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action(function () {
                    $compra = $this->record->load(['proveedor', 'detalles.material']);

                    $pdf = Pdf::loadView('pdf.compra', ['compra' => $compra]);

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'factura-compra-' . $this->record->id . '.pdf'
                    );
                }),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
