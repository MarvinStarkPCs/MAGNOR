<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filtrar por stock bajo
        if ($request->has('stock_bajo') && $request->input('stock_bajo')) {
            $promedioStock = Material::avg('stock');
            $query->where('stock', '<', $promedioStock * 0.2);
        }

        // Ordenamiento
        $sortBy = $request->input('sort_by', 'nombre');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Cargar movimientos recientes para cada material
        $materiales = $query->with([
            'detallesCompras' => function ($query) {
                $query->latest()->limit(5);
            },
            'detallesCompras.compra',
            'detallesVentas' => function ($query) {
                $query->latest()->limit(5);
            },
            'detallesVentas.venta'
        ])->paginate(10)->withQueryString();

        // Calcular valor total del inventario
        $valorTotalInventario = Material::selectRaw('SUM(stock * precio_compra) as total')->first()->total ?? 0;

        return Inertia::render('Inventario/Index', [
            'materiales' => $materiales,
            'filters' => $request->only(['search', 'stock_bajo', 'sort_by', 'sort_order']),
            'valorTotalInventario' => $valorTotalInventario,
        ]);
    }

    public function show(Material $material)
    {
        // Cargar todos los movimientos del material
        $material->load([
            'detallesCompras.compra.proveedor',
            'detallesVentas.venta.cliente'
        ]);

        // Calcular estadísticas
        $totalComprado = $material->detallesCompras->sum('cantidad');
        $totalVendido = $material->detallesVentas->sum('cantidad');
        $valorInventario = $material->stock * $material->precio_compra;

        return Inertia::render('Inventario/Show', [
            'material' => $material,
            'stats' => [
                'totalComprado' => $totalComprado,
                'totalVendido' => $totalVendido,
                'valorInventario' => $valorInventario,
            ],
        ]);
    }
}
