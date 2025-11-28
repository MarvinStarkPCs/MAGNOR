<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        // Ordenamiento
        $sortBy = $request->input('sort_by', 'nombre');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $materiales = $query->paginate(10)->withQueryString();

        return Inertia::render('Materiales/Index', [
            'materiales' => $materiales,
            'filters' => $request->only(['search', 'sort_by', 'sort_order']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Materiales/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_dia_compra' => 'nullable|numeric|min:0',
            'precio_dia_venta' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        Material::create($validated);

        return redirect()->route('materiales.index')
            ->with('success', 'Material creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        $material->load(['detallesCompras.compra.proveedor', 'detallesVentas.venta.cliente']);

        return Inertia::render('Materiales/Show', [
            'material' => $material,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return Inertia::render('Materiales/Edit', [
            'material' => $material,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'precio_dia_compra' => 'nullable|numeric|min:0',
            'precio_dia_venta' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        $material->update($validated);

        return redirect()->route('materiales.index')
            ->with('success', 'Material actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        // Verificar si tiene movimientos
        if ($material->detallesCompras()->count() > 0 || $material->detallesVentas()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el material porque tiene movimientos asociados.');
        }

        $material->delete();

        return redirect()->route('materiales.index')
            ->with('success', 'Material eliminado exitosamente.');
    }
}
