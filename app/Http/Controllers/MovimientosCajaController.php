<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CategoriaCaja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MovimientosCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener caja abierta actual
        $cajaAbierta = Caja::where('estado', 'abierta')
            ->whereDate('fecha', today())
            ->first();

        if (!$cajaAbierta) {
            return Inertia::render('CajaMenor/Movimientos/Index', [
                'movimientos' => [],
                'cajaAbierta' => null,
                'categorias' => [],
                'filters' => [],
            ]);
        }

        $query = MovimientoCaja::with(['categoria', 'responsable'])
            ->where('caja_id', $cajaAbierta->id);

        // Filtro por tipo
        if ($request->has('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        // Filtro por categoría
        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->input('categoria_id'));
        }

        $movimientos = $query->orderBy('fecha_hora', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Obtener categorías activas
        $categorias = CategoriaCaja::where('activo', true)
            ->orderBy('nombre')
            ->get();

        // Calcular totales
        $totalIngresos = MovimientoCaja::where('caja_id', $cajaAbierta->id)
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $totalEgresos = MovimientoCaja::where('caja_id', $cajaAbierta->id)
            ->where('tipo', 'egreso')
            ->sum('monto');

        return Inertia::render('CajaMenor/Movimientos/Index', [
            'movimientos' => $movimientos,
            'cajaAbierta' => $cajaAbierta,
            'categorias' => $categorias,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'filters' => $request->only(['tipo', 'categoria_id']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar que haya una caja abierta
        $cajaAbierta = Caja::where('estado', 'abierta')
            ->whereDate('fecha', today())
            ->first();

        if (!$cajaAbierta) {
            return redirect()->route('cajas.index')
                ->with('error', 'Debe abrir una caja antes de registrar movimientos.');
        }

        // Obtener categorías activas
        $categorias = CategoriaCaja::where('activo', true)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('CajaMenor/Movimientos/Create', [
            'cajaAbierta' => $cajaAbierta,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar que haya una caja abierta
        $cajaAbierta = Caja::where('estado', 'abierta')
            ->whereDate('fecha', today())
            ->first();

        if (!$cajaAbierta) {
            return back()->with('error', 'Debe abrir una caja antes de registrar movimientos.');
        }

        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias_caja,id',
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Manejar subida de comprobante
        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        MovimientoCaja::create([
            'caja_id' => $cajaAbierta->id,
            'categoria_id' => $validated['categoria_id'],
            'tipo' => $validated['tipo'],
            'monto' => $validated['monto'],
            'concepto' => $validated['concepto'],
            'observaciones' => $validated['observaciones'] ?? null,
            'responsable_id' => Auth::id(),
            'comprobante' => $comprobantePath,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('movimientos-caja.index')
            ->with('success', 'Movimiento registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MovimientoCaja $movimientosCaja)
    {
        $movimientosCaja->load(['caja', 'categoria', 'responsable']);

        return Inertia::render('CajaMenor/Movimientos/Show', [
            'movimiento' => $movimientosCaja,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MovimientoCaja $movimientosCaja)
    {
        // Solo se pueden editar movimientos de cajas abiertas
        if ($movimientosCaja->caja->estado === 'cerrada') {
            return back()->with('error', 'No se pueden editar movimientos de una caja cerrada.');
        }

        // Obtener categorías activas
        $categorias = CategoriaCaja::where('activo', true)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('CajaMenor/Movimientos/Edit', [
            'movimiento' => $movimientosCaja,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MovimientoCaja $movimientosCaja)
    {
        // Solo se pueden editar movimientos de cajas abiertas
        if ($movimientosCaja->caja->estado === 'cerrada') {
            return back()->with('error', 'No se pueden editar movimientos de una caja cerrada.');
        }

        $validated = $request->validate([
            'categoria_id' => 'required|exists:categorias_caja,id',
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Manejar subida de nuevo comprobante
        $comprobantePath = $movimientosCaja->comprobante;
        if ($request->hasFile('comprobante')) {
            // Eliminar comprobante anterior si existe
            if ($movimientosCaja->comprobante) {
                Storage::disk('public')->delete($movimientosCaja->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $movimientosCaja->update([
            'categoria_id' => $validated['categoria_id'],
            'tipo' => $validated['tipo'],
            'monto' => $validated['monto'],
            'concepto' => $validated['concepto'],
            'observaciones' => $validated['observaciones'] ?? null,
            'comprobante' => $comprobantePath,
        ]);

        return redirect()->route('movimientos-caja.index')
            ->with('success', 'Movimiento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MovimientoCaja $movimientosCaja)
    {
        // Solo se pueden eliminar movimientos de cajas abiertas
        if ($movimientosCaja->caja->estado === 'cerrada') {
            return back()->with('error', 'No se pueden eliminar movimientos de una caja cerrada.');
        }

        // Eliminar comprobante si existe
        if ($movimientosCaja->comprobante) {
            Storage::disk('public')->delete($movimientosCaja->comprobante);
        }

        $movimientosCaja->delete();

        return redirect()->route('movimientos-caja.index')
            ->with('success', 'Movimiento eliminado exitosamente.');
    }
}
