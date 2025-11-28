<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PreciosController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CategoriasCajaController;
use App\Http\Controllers\CajasController;
use App\Http\Controllers\MovimientosCajaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Materiales
    Route::resource('materiales', MaterialController::class);

    // Proveedores
    Route::resource('proveedores', ProveedorController::class);

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Compras
    Route::resource('compras', CompraController::class);

    // Ventas
    Route::resource('ventas', VentaController::class);

    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/{material}', [InventarioController::class, 'show'])->name('inventario.show');

    // Precios del Día
    Route::get('/precios', [PreciosController::class, 'index'])->name('precios.index');
    Route::post('/precios', [PreciosController::class, 'update'])->name('precios.update');
    Route::post('/precios/aplicar', [PreciosController::class, 'aplicarPreciosDia'])->name('precios.aplicar');

    // Facturas
    Route::get('/ventas/{venta}/factura', [FacturaController::class, 'generarFactura'])->name('ventas.factura');
    Route::get('/compras/{compra}/factura', [FacturaController::class, 'generarFacturaCompra'])->name('compras.factura');

    // Caja Menor - Categorías
    Route::resource('categorias-caja', CategoriasCajaController::class);

    // Caja Menor - Cajas
    Route::resource('cajas', CajasController::class)->except(['edit', 'update']);
    Route::post('/cajas/{caja}/close', [CajasController::class, 'close'])->name('cajas.close');

    // Caja Menor - Movimientos
    Route::resource('movimientos-caja', MovimientosCajaController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
