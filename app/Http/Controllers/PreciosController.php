<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PreciosController extends Controller
{
    public function index()
    {
        $materiales = Material::orderBy('nombre', 'asc')->get();

        return Inertia::render('Precios/Index', [
            'materiales' => $materiales,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'materiales' => 'required|array',
            'materiales.*.id' => 'required|exists:materiales,id',
            'materiales.*.precio_dia_compra' => 'nullable|numeric|min:0',
            'materiales.*.precio_dia_venta' => 'nullable|numeric|min:0',
        ]);

        $fechaActualizacion = now();

        foreach ($validated['materiales'] as $materialData) {
            $material = Material::find($materialData['id']);

            $updateData = [];

            if (isset($materialData['precio_dia_compra'])) {
                $updateData['precio_dia_compra'] = $materialData['precio_dia_compra'];
            }

            if (isset($materialData['precio_dia_venta'])) {
                $updateData['precio_dia_venta'] = $materialData['precio_dia_venta'];
            }

            if (!empty($updateData)) {
                $updateData['fecha_actualizacion_precio'] = $fechaActualizacion;
                $material->update($updateData);
            }
        }

        return redirect()->route('precios.index')
            ->with('success', 'Precios actualizados exitosamente.');
    }

    public function aplicarPreciosDia()
    {
        // Aplicar precios del día como precios base
        Material::whereNotNull('precio_dia_compra')
            ->update(['precio_compra' => DB::raw('precio_dia_compra')]);

        Material::whereNotNull('precio_dia_venta')
            ->update(['precio_venta' => DB::raw('precio_dia_venta')]);

        return redirect()->route('precios.index')
            ->with('success', 'Precios del día aplicados como precios base exitosamente.');
    }
}
