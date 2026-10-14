<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BodegaController extends Controller
{
    public function index(): Response
    {
        $bodegas = Bodega::withCount(['recibos', 'inventarios'])
            ->orderBy('nombre')
            ->get();

        return Inertia::render('Bodegas/Index', [
            'bodegas' => $bodegas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:100|unique:bodegas,nombre',
            'direccion' => 'nullable|string|max:255',
        ]);

        Bodega::create($data + ['activa' => true]);

        return back()->with('success', 'Bodega creada correctamente.');
    }

    public function update(Request $request, Bodega $bodega): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => "required|string|max:100|unique:bodegas,nombre,{$bodega->id}",
            'direccion' => 'nullable|string|max:255',
            'activa'    => 'boolean',
        ]);

        $bodega->update($data);

        return back()->with('success', 'Bodega actualizada.');
    }

    public function destroy(Bodega $bodega): RedirectResponse
    {
        if ($bodega->recibos()->exists() || $bodega->inventarios()->exists()) {
            return back()->with('error', 'No se puede eliminar una bodega que tiene recibos o stock registrado.');
        }

        $bodega->delete();

        return back()->with('success', 'Bodega eliminada.');
    }
}
