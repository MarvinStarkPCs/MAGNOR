<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reciclador;
use App\Models\Recibo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PagoController extends Controller
{
    public function index(Request $request): Response
    {
        $pagos = Pago::with(['reciclador', 'recibo'])
            ->when($request->search, fn($q, $s) =>
                $q->whereHas('reciclador', fn($r) => $r->where('nombre', 'like', "%$s%"))
            )
            ->when($request->fecha_desde, fn($q, $d) => $q->whereDate('fecha', '>=', $d))
            ->when($request->fecha_hasta, fn($q, $d) => $q->whereDate('fecha', '<=', $d))
            ->when($request->forma_pago, fn($q, $f) => $q->where('forma_pago', $f))
            ->orderByDesc('fecha')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        // Saldo pendiente global (recibos activos sin cubrir)
        $totalRecibos = Recibo::where('anulado', false)->sum('total');
        $totalPagado  = Pago::sum('monto');
        $saldoPendiente = max(0, $totalRecibos - $totalPagado);

        return Inertia::render('Pagos/Index', [
            'pagos'          => $pagos,
            'filtros'        => $request->only('search', 'fecha_desde', 'fecha_hasta', 'forma_pago'),
            'saldoPendiente' => (float) $saldoPendiente,
            'totalPagado'    => (float) $totalPagado,
            'totalRecibos'   => (float) $totalRecibos,
        ]);
    }

    public function create(Request $request): Response
    {
        $recicladores = Reciclador::activos()->orderBy('nombre')->get(['id', 'nombre', 'documento']);

        // Si viene reciclador_id pre-seleccionado (desde show de reciclador)
        $recicladorId = $request->integer('reciclador_id') ?: null;

        // Recibos pendientes de pago del reciclador seleccionado
        $recibosPendientes = $recicladorId
            ? $this->recibosPendientes($recicladorId)
            : collect();

        return Inertia::render('Pagos/Form', [
            'recicladores'     => $recicladores,
            'recicladorId'     => $recicladorId,
            'recibosPendientes'=> $recibosPendientes,
            'fecha'            => now()->toDateString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reciclador_id' => 'required|exists:recicladores,id',
            'recibo_id'     => 'nullable|exists:recibos,id',
            'fecha'         => 'required|date',
            'monto'         => 'required|numeric|min:0.01',
            'forma_pago'    => 'required|in:efectivo,transferencia,cheque',
            'referencia'    => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        Pago::create($data);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado.');
    }

    // API: recibos pendientes de un reciclador (llamada AJAX desde el Form)
    public function recibosPorReciclador(Request $request): \Illuminate\Http\JsonResponse
    {
        $recicladorId = $request->integer('reciclador_id');
        return response()->json($this->recibosPendientes($recicladorId));
    }

    private function recibosPendientes(int $recicladorId): \Illuminate\Support\Collection
    {
        return Recibo::where('reciclador_id', $recicladorId)
            ->where('anulado', false)
            ->orderByDesc('fecha')
            ->get(['id', 'numero', 'fecha', 'total'])
            ->map(fn($r) => [
                'id'     => $r->id,
                'numero' => $r->numero,
                'fecha'  => $r->fecha->toDateString(),
                'total'  => (float) $r->total,
            ]);
    }
}
