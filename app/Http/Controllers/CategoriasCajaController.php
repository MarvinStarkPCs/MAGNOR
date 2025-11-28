<?php

namespace App\Http\Controllers;

use App\Models\CategoriaCaja;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriasCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CategoriaCaja::query();

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        $categorias = $query->orderBy('nombre')->paginate(10)->withQueryString();

        return Inertia::render('CajaMenor/Categorias/Index', [
            'categorias' => $categorias,
            'filters' => $request->only(['search', 'tipo']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CajaMenor/Categorias/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        CategoriaCaja::create($validated);

        return redirect()->route('categorias-caja.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaCaja $categoriasCaja)
    {
        $categoriasCaja->load(['movimientos.caja', 'movimientos.responsable']);

        return Inertia::render('CajaMenor/Categorias/Show', [
            'categoria' => $categoriasCaja,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaCaja $categoriasCaja)
    {
        return Inertia::render('CajaMenor/Categorias/Edit', [
            'categoria' => $categoriasCaja,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriaCaja $categoriasCaja)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $categoriasCaja->update($validated);

        return redirect()->route('categorias-caja.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaCaja $categoriasCaja)
    {
        // Verificar si tiene movimientos asociados
        if ($categoriasCaja->movimientos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la categoría porque tiene movimientos asociados.');
        }

        $categoriasCaja->delete();

        return redirect()->route('categorias-caja.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
