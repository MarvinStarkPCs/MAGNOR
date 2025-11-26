<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturaController;
use Illuminate\Support\Facades\Log;


Route::get('/', function () {
    return view('welcome');
});

// Factura Ventas routes
Route::get('/venta/{id}/factura/print', [FacturaController::class, 'print'])->name('venta.factura.print');
Route::get('/venta/{id}/factura/download', [FacturaController::class, 'download'])->name('venta.factura.download');

// Factura Compras routes
Route::get('/compra/{id}/factura/print', [FacturaController::class, 'printCompra'])->name('compra.factura.print');
Route::get('/compra/{id}/factura/download', [FacturaController::class, 'downloadCompra'])->name('compra.factura.download');

Route::get('/log-test', function() {
    Log::info('Prueba de logging funcionando en cPanel');
    return 'Log generado';
});
