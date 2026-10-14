<?php

use App\Http\Controllers\BodegaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\RecicladorController;
use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Recicladores
    Route::get('/recicladores/buscar', [RecicladorController::class, 'buscar'])->name('recicladores.buscar');
    Route::resource('recicladores', RecicladorController::class)->except(['show', 'destroy']);
    Route::delete('/recicladores/{reciclador}/desactivar', [RecicladorController::class, 'destroy'])->name('recicladores.destroy');

    // Materiales
    Route::get('/materiales/todos', [MaterialController::class, 'todos'])->name('materiales.todos');
    Route::resource('materiales', MaterialController::class)->only(['index', 'edit', 'update']);

    // Recibos
    Route::resource('recibos', ReciboController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::patch('/recibos/{recibo}/anular', [ReciboController::class, 'anular'])->name('recibos.anular');
    Route::get('/recibos/{recibo}/pdf', [ReciboController::class, 'pdf'])->name('recibos.pdf');

    // Pagos a recicladores
    Route::resource('pagos', PagoController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('/pagos/recibos-por-reciclador', [PagoController::class, 'recibosPorReciclador'])->name('pagos.recibos-por-reciclador');

    // Vehículos
    Route::resource('vehiculos', VehiculoController::class)->only(['index', 'store', 'update', 'destroy']);

    // Bodegas
    Route::resource('bodegas', BodegaController::class)->only(['index', 'store', 'update', 'destroy']);

    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/movimientos', [InventarioController::class, 'movimientos'])->name('inventario.movimientos');
    Route::post('/inventario/ajustar', [InventarioController::class, 'ajustar'])->name('inventario.ajustar');
    Route::post('/inventario/transferir', [InventarioController::class, 'transferir'])->name('inventario.transferir');

    // Registro / Balance de Masa
    Route::get('/registro', [RegistroController::class, 'index'])->name('registro.index');
    Route::get('/registro/exportar-sui', [RegistroController::class, 'exportarSui'])->name('registro.exportar-sui');
    Route::get('/registro/exportar-csv', [RegistroController::class, 'exportarCsv'])->name('registro.exportar-csv');
});

require __DIR__.'/settings.php';
