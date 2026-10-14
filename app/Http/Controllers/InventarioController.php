<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use App\Models\Inventario;
use App\Models\Material;
use App\Models\MovimientoInventario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventarioController extends Controller
{
    public function index(Request $request): Response
    {
        $stocks = Inventario::with(['material.familia', 'bodega'])
            ->when($request->bodega_id, fn($q, $b) => $q->where('bodega_id', $b))
            ->when($request->familia_id, fn($q, $f) =>
                $q->whereHas('material', fn($m) => $m->where('familia_id', $f))
            )
            ->when($request->search, fn($q, $s) =>
                $q->whereHas('material', fn($m) => $m->where('nombre', 'like', "%$s%"))
            )
            ->orderByDesc('cantidad_kg')
            ->get()
            ->map(fn($s) => [
                'id'                   => $s->id,
                'material_id'          => $s->material_id,
                'bodega_id'            => $s->bodega_id,
                'material_nombre'      => $s->material->nombre,
                'familia_nombre'       => $s->material->familia->nombre,
                'bodega_nombre'        => $s->bodega?->nombre ?? '—',
                'cantidad_kg'          => (float) $s->cantidad_kg,
                'ultima_actualizacion' => $s->ultima_actualizacion?->toDateTimeString(),
            ]);

        $familias = \App\Models\FamiliaMaterial::orderBy('nombre')->get(['id', 'nombre']);
        $bodegas  = Bodega::where('activa', true)->orderBy('nombre')->get(['id', 'nombre']);
        $totalKg  = $stocks->sum('cantidad_kg');

        return Inertia::render('Inventario/Index', [
            'stocks'   => $stocks,
            'familias' => $familias,
            'bodegas'  => $bodegas,
            'totalKg'  => (float) $totalKg,
            'filtros'  => $request->only('search', 'familia_id', 'bodega_id'),
        ]);
    }

    public function movimientos(Request $request): Response
    {
        $movimientos = MovimientoInventario::with(['material.familia', 'user'])
            ->when($request->material_id, fn($q, $m) => $q->where('material_id', $m))
            ->when($request->tipo, fn($q, $t) => $q->where('tipo', $t))
            ->when($request->fecha_desde, fn($q, $d) => $q->whereDate('fecha', '>=', $d))
            ->when($request->fecha_hasta, fn($q, $d) => $q->whereDate('fecha', '<=', $d))
            ->orderByDesc('created_at')
            ->paginate(30)->withQueryString();

        $materiales = Material::activos()->orderBy('nombre')->get(['id', 'nombre']);

        return Inertia::render('Inventario/Movimientos', [
            'movimientos' => $movimientos,
            'materiales'  => $materiales,
            'filtros'     => $request->only('material_id', 'tipo', 'fecha_desde', 'fecha_hasta'),
        ]);
    }

    public function ajustar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'material_id'   => 'required|exists:materiales,id',
            'bodega_id'     => 'required|exists:bodegas,id',
            'kg'            => 'required|numeric|min:0.001',
            'tipo'          => 'required|in:ajuste',
            'direccion'     => 'required|in:sumar,restar',
            'observaciones' => 'required|string|min:5',
        ]);

        $material = Material::findOrFail($data['material_id']);

        if ($data['direccion'] === 'sumar') {
            Inventario::sumar(
                materialId: $data['material_id'],
                bodegaId:   $data['bodega_id'],
                kg:         $data['kg'],
                tipo:       'ajuste',
                observaciones: $data['observaciones'],
            );
        } else {
            Inventario::restar(
                materialId: $data['material_id'],
                bodegaId:   $data['bodega_id'],
                kg:         $data['kg'],
                observaciones: $data['observaciones'],
            );
        }

        return redirect()->route('inventario.index')
            ->with('success', "Ajuste de {$data['kg']} kg en {$material->nombre} registrado.");
    }

    public function transferir(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'material_id'       => 'required|exists:materiales,id',
            'bodega_origen_id'  => 'required|exists:bodegas,id',
            'bodega_destino_id' => 'required|exists:bodegas,id|different:bodega_origen_id',
            'kg'                => 'required|numeric|min:0.001',
            'observaciones'     => 'nullable|string',
        ]);

        $material = Material::findOrFail($data['material_id']);

        Inventario::transferir(
            materialId:       $data['material_id'],
            bodegaOrigenId:   $data['bodega_origen_id'],
            bodegaDestinoId:  $data['bodega_destino_id'],
            kg:               $data['kg'],
            observaciones:    $data['observaciones'] ?? "Transferencia de {$material->nombre}",
        );

        return redirect()->route('inventario.index')
            ->with('success', "Transferencia de {$data['kg']} kg de {$material->nombre} realizada.");
    }
}
