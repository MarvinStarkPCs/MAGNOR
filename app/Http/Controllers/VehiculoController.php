<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehiculoController extends Controller
{
    public function index(): Response
    {
        $vehiculos = Vehiculo::orderBy('placa')->get();

        return Inertia::render('Vehiculos/Index', [
            'vehiculos' => $vehiculos,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'placa' => 'required|string|max:10|unique:vehiculos,placa',
            'tipo'  => 'nullable|string|max:50',
        ]);

        Vehiculo::create(array_merge($data, ['activo' => true]));

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado.');
    }

    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $data = $request->validate([
            'placa'  => 'required|string|max:10|unique:vehiculos,placa,' . $vehiculo->id,
            'tipo'   => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        $vehiculo->update($data);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        // Desactivar en lugar de eliminar (puede tener recicladores asociados)
        $vehiculo->update(['activo' => false]);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo desactivado.');
    }
}
