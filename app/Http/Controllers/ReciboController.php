<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use App\Models\ConfiguracionEmpresa;
use App\Models\DetalleRecibo;
use App\Models\Inventario;
use App\Models\Material;
use App\Models\Recibo;
use App\Models\Reciclador;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReciboController extends Controller
{
    public function index(Request $request): Response
    {
        $recibos = Recibo::with('reciclador')
            ->when($request->search, fn($q, $s) =>
                $q->where('numero', $s)
                  ->orWhereHas('reciclador', fn($r) => $r->where('nombre', 'like', "%$s%"))
            )
            ->when($request->fecha_desde, fn($q, $d) => $q->whereDate('fecha', '>=', $d))
            ->when($request->fecha_hasta, fn($q, $d) => $q->whereDate('fecha', '<=', $d))
            ->when($request->filled('anulado'), fn($q) => $q->where('anulado', $request->boolean('anulado')))
            ->withCount('detalles')
            ->withSum('detalles as total_kg', 'kg_neto')
            ->orderByDesc('fecha')->orderByDesc('numero')
            ->paginate(20)->withQueryString();

        return Inertia::render('Recibos/Index', [
            'recibos' => $recibos,
            'filtros' => $request->only('search', 'fecha_desde', 'fecha_hasta', 'anulado'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Recibos/Form', [
            'numero'     => Recibo::proximoNumero(),
            'fecha'      => now()->toDateString(),
            'materiales' => Material::with('familia')->activos()->orderBy('familia_id')->orderBy('nombre')->get(),
            'bodegas'    => Bodega::where('activa', true)->orderBy('nombre')->get(['id', 'nombre']),
            'pctRechazo' => (float) ConfiguracionEmpresa::get('porcentaje_rechazo_def', 1),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reciclador_id' => 'required|exists:recicladores,id',
            'bodega_id'     => 'required|exists:bodegas,id',
            'fecha'         => 'required|date',
            'observaciones' => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.material_id'  => 'required|exists:materiales,id',
            'items.*.kg_bruto'     => 'required|numeric|min:0.001',
            'items.*.kg_rechazado' => 'required|numeric|min:0',
            'items.*.precio_kg'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $subtotal = collect($data['items'])->sum(fn($i) => round($i['kg_bruto'] * $i['precio_kg'], 2));

            $recibo = Recibo::create([
                'numero'        => Recibo::proximoNumero(),
                'fecha'         => $data['fecha'],
                'reciclador_id' => $data['reciclador_id'],
                'bodega_id'     => $data['bodega_id'],
                'subtotal'      => $subtotal,
                'retefuente'    => 0,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $detalle = DetalleRecibo::create([
                    'recibo_id'    => $recibo->id,
                    'material_id'  => $item['material_id'],
                    'kg_bruto'     => $item['kg_bruto'],
                    'kg_rechazado' => $item['kg_rechazado'],
                    'precio_kg'    => $item['precio_kg'],
                ]);

                Inventario::sumar(
                    materialId: $item['material_id'],
                    bodegaId:   $data['bodega_id'],
                    kg:         (float) ($item['kg_bruto'] - $item['kg_rechazado']),
                    tipo:       'entrada',
                    referencia: $recibo,
                    observaciones: "Recibo #{$recibo->numero}",
                    fecha:      $data['fecha'],
                );
            }
        });

        return redirect()->route('recibos.index')->with('success', 'Recibo guardado correctamente.');
    }

    public function edit(Recibo $recibo): Response
    {
        abort_if($recibo->anulado, 403, 'No se puede editar un recibo anulado.');

        return Inertia::render('Recibos/Edit', [
            'recibo'     => $recibo->load(['reciclador', 'detalles.material.familia', 'bodega']),
            'materiales' => Material::with('familia')->activos()->orderBy('familia_id')->orderBy('nombre')->get(),
            'bodegas'    => Bodega::where('activa', true)->orderBy('nombre')->get(['id', 'nombre']),
            'pctRechazo' => (float) ConfiguracionEmpresa::get('porcentaje_rechazo_def', 1),
        ]);
    }

    public function update(Request $request, Recibo $recibo): RedirectResponse
    {
        abort_if($recibo->anulado, 403, 'No se puede editar un recibo anulado.');

        $data = $request->validate([
            'reciclador_id' => 'required|exists:recicladores,id',
            'bodega_id'     => 'required|exists:bodegas,id',
            'fecha'         => 'required|date',
            'observaciones' => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.material_id'  => 'required|exists:materiales,id',
            'items.*.kg_bruto'     => 'required|numeric|min:0.001',
            'items.*.kg_rechazado' => 'required|numeric|min:0',
            'items.*.precio_kg'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data, $recibo) {
            $bodegaAnterior = (int) $recibo->bodega_id;

            // Revertir inventario de los detalles anteriores
            foreach ($recibo->detalles as $detalle) {
                if ($bodegaAnterior) {
                    Inventario::restar(
                        materialId: $detalle->material_id,
                        bodegaId:   $bodegaAnterior,
                        kg:         (float) $detalle->kg_neto,
                        referencia: $recibo,
                        observaciones: "Edición recibo #{$recibo->numero} - reversión anterior",
                    );
                }
            }

            $subtotal = collect($data['items'])->sum(fn($i) => round($i['kg_bruto'] * $i['precio_kg'], 2));

            $recibo->update([
                'fecha'         => $data['fecha'],
                'reciclador_id' => $data['reciclador_id'],
                'bodega_id'     => $data['bodega_id'],
                'subtotal'      => $subtotal,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $recibo->detalles()->delete();

            foreach ($data['items'] as $item) {
                DetalleRecibo::create([
                    'recibo_id'    => $recibo->id,
                    'material_id'  => $item['material_id'],
                    'kg_bruto'     => $item['kg_bruto'],
                    'kg_rechazado' => $item['kg_rechazado'],
                    'precio_kg'    => $item['precio_kg'],
                ]);

                Inventario::sumar(
                    materialId: $item['material_id'],
                    bodegaId:   $data['bodega_id'],
                    kg:         (float) ($item['kg_bruto'] - $item['kg_rechazado']),
                    tipo:       'entrada',
                    referencia: $recibo,
                    observaciones: "Recibo #{$recibo->numero} (editado)",
                    fecha:      $data['fecha'],
                );
            }
        });

        return redirect()->route('recibos.show', $recibo)->with('success', 'Recibo actualizado correctamente.');
    }

    public function show(Recibo $recibo): Response
    {
        return Inertia::render('Recibos/Show', [
            'recibo' => $recibo->load(['reciclador.municipio', 'detalles.material.familia']),
        ]);
    }

    public function pdf(Recibo $recibo): HttpResponse
    {
        $recibo->load(['reciclador.municipio', 'detalles.material.familia']);

        $empresa = [
            'nombre'    => ConfiguracionEmpresa::get('empresa_nombre', 'ARECUPERAR'),
            'municipio' => ConfiguracionEmpresa::get('empresa_municipio'),
            'nueca'     => ConfiguracionEmpresa::get('nueca'),
        ];

        $pdf = Pdf::loadView('pdf.recibo', compact('recibo', 'empresa'))
            ->setPaper('a4', 'portrait');

        $filename = 'recibo-' . str_pad($recibo->numero, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    public function anular(Request $request, Recibo $recibo): RedirectResponse
    {
        $request->validate(['motivo' => 'required|string|min:5']);

        $recibo->update([
            'anulado'        => true,
            'anulado_en'     => now(),
            'anulado_motivo' => $request->motivo,
        ]);

        // Revertir inventario: restar lo que este recibo había sumado
        if ($recibo->bodega_id) {
            foreach ($recibo->detalles as $detalle) {
                Inventario::restar(
                    materialId: $detalle->material_id,
                    bodegaId:   $recibo->bodega_id,
                    kg:         (float) $detalle->kg_neto,
                    referencia: $recibo,
                    observaciones: "Anulación recibo #{$recibo->numero}: {$request->motivo}",
                    fecha:      now()->toDateString(),
                );
            }
        }

        return redirect()->route('recibos.index')->with('success', 'Recibo anulado.');
    }
}
