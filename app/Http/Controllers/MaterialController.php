<?php

namespace App\Http\Controllers;

use App\Models\FamiliaMaterial;
use App\Models\HistorialPrecio;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaterialController extends Controller
{
    public function index(Request $request): Response
    {
        $materiales = Material::with('familia')
            ->when($request->search, fn($q, $s) =>
                $q->where('nombre', 'like', "%$s%")
            )
            ->when($request->filled('familia_id'), fn($q) =>
                $q->where('familia_id', $request->familia_id)
            )
            ->orderBy('familia_id')->orderBy('nombre')
            ->paginate(30)->withQueryString();

        return Inertia::render('Materiales/Index', [
            'materiales' => $materiales,
            'familias'   => FamiliaMaterial::where('activo', true)->orderBy('nombre')->get(),
            'filtros'    => $request->only('search', 'familia_id'),
        ]);
    }

    public function edit(Material $material): Response
    {
        return Inertia::render('Materiales/Edit', [
            'material'  => $material->load('familia', 'historialPrecios'),
            'familias'  => FamiliaMaterial::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $data = $request->validate([
            'precio_actual' => 'required|numeric|min:0',
            'activo'        => 'boolean',
        ]);

        if ($material->precio_actual != $data['precio_actual']) {
            HistorialPrecio::where('material_id', $material->id)
                ->whereNull('vigente_hasta')
                ->update(['vigente_hasta' => now()->subDay()->toDateString()]);

            HistorialPrecio::create([
                'material_id'   => $material->id,
                'precio'        => $data['precio_actual'],
                'vigente_desde' => now()->toDateString(),
            ]);
        }

        $material->update($data);
        return redirect()->route('materiales.index')->with('success', 'Precio actualizado correctamente.');
    }

    public function todos()
    {
        return Material::with('familia')
            ->activos()
            ->orderBy('familia_id')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'precio_actual', 'familia_id', 'codigo_sui']);
    }
}
