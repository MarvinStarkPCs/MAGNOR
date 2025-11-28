<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Venta;
use App\Models\Material;
use App\Models\Cliente;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalMateriales = Material::count();
        $totalClientes = Cliente::count();
        $totalProveedores = Proveedor::count();

        // Ventas y compras del mes actual
        $mesActual = now()->format('Y-m');
        $ventasMesActual = Venta::whereRaw('DATE_FORMAT(fecha, "%Y-%m") = ?', [$mesActual])->sum('total');
        $comprasMesActual = Compra::whereRaw('DATE_FORMAT(fecha_compra, "%Y-%m") = ?', [$mesActual])->sum('total');

        // Materiales con bajo stock (menos del 20% del promedio)
        $promedioStock = Material::avg('stock');
        $materialesBajoStock = Material::where('stock', '<', $promedioStock * 0.2)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Últimas 5 ventas
        $ultimasVentas = Venta::with('cliente')
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        // Últimas 5 compras
        $ultimasCompras = Compra::with('proveedor')
            ->orderBy('fecha_compra', 'desc')
            ->limit(5)
            ->get();

        // Datos para gráficos - Ventas vs Compras últimos 6 meses
        $ventasPorMes = Venta::select(
            DB::raw('DATE_FORMAT(fecha, "%Y-%m") as mes'),
            DB::raw('SUM(total) as total')
        )
        ->where('fecha', '>=', now()->subMonths(6))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        $comprasPorMes = Compra::select(
            DB::raw('DATE_FORMAT(fecha_compra, "%Y-%m") as mes'),
            DB::raw('SUM(total) as total')
        )
        ->where('fecha_compra', '>=', now()->subMonths(6))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        // Top 5 materiales más vendidos (por cantidad)
        $materialesMasVendidos = Material::select('materiales.*', DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'))
            ->join('detalle_ventas', 'materiales.id', '=', 'detalle_ventas.material_id')
            ->groupBy('materiales.id')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalMateriales' => $totalMateriales,
                'totalClientes' => $totalClientes,
                'totalProveedores' => $totalProveedores,
                'ventasMesActual' => $ventasMesActual,
                'comprasMesActual' => $comprasMesActual,
            ],
            'materialesBajoStock' => $materialesBajoStock,
            'ultimasVentas' => $ultimasVentas,
            'ultimasCompras' => $ultimasCompras,
            'ventasPorMes' => $ventasPorMes,
            'comprasPorMes' => $comprasPorMes,
            'materialesMasVendidos' => $materialesMasVendidos,
        ]);
    }
}
