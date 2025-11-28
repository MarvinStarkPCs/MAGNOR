<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CajasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Caja::with(['usuarioApertura', 'usuarioCierre']);

        // Filtro por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro por fecha
        if ($request->has('fecha')) {
            $query->where('fecha', $request->input('fecha'));
        }

        $cajas = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_apertura', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Obtener caja abierta actual si existe
        $cajaAbierta = Caja::where('estado', 'abierta')
            ->whereDate('fecha', today())
            ->first();

        return Inertia::render('CajaMenor/Cajas/Index', [
            'cajas' => $cajas,
            'cajaAbierta' => $cajaAbierta,
            'filters' => $request->only(['estado', 'fecha']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar si ya hay una caja abierta hoy
        $cajaAbiertaHoy = Caja::where('estado', 'abierta')
            ->whereDate('fecha', today())
            ->first();

        if ($cajaAbiertaHoy) {
            return back()->with('error', 'Ya existe una caja abierta para el día de hoy.');
        }

        return Inertia::render('CajaMenor/Cajas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si ya hay una caja abierta hoy
        $cajaAbiertaHoy = Caja::where('estado', 'abierta')
            ->whereDate('fecha', today())
            ->first();

        if ($cajaAbiertaHoy) {
            return back()->with('error', 'Ya existe una caja abierta para el día de hoy.');
        }

        $validated = $request->validate([
            'monto_apertura' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        Caja::create([
            'fecha' => today(),
            'usuario_apertura_id' => Auth::id(),
            'monto_apertura' => $validated['monto_apertura'],
            'hora_apertura' => now(),
            'estado' => 'abierta',
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return redirect()->route('cajas.index')
            ->with('success', 'Caja abierta exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Caja $caja)
    {
        $caja->load([
            'usuarioApertura',
            'usuarioCierre',
            'movimientos.categoria',
            'movimientos.responsable'
        ]);

        // Calcular totales
        $totalIngresos = $caja->movimientos()
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $totalEgresos = $caja->movimientos()
            ->where('tipo', 'egreso')
            ->sum('monto');

        $montoEsperado = $caja->monto_apertura + $totalIngresos - $totalEgresos;

        return Inertia::render('CajaMenor/Cajas/Show', [
            'caja' => $caja,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'montoEsperado' => $montoEsperado,
        ]);
    }

    /**
     * Cerrar la caja
     */
    public function close(Request $request, Caja $caja)
    {
        if ($caja->estado === 'cerrada') {
            return back()->with('error', 'Esta caja ya está cerrada.');
        }

        $validated = $request->validate([
            'monto_cierre' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        // Calcular totales
        $totalIngresos = $caja->movimientos()
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $totalEgresos = $caja->movimientos()
            ->where('tipo', 'egreso')
            ->sum('monto');

        $montoEsperado = $caja->monto_apertura + $totalIngresos - $totalEgresos;
        $diferencia = $validated['monto_cierre'] - $montoEsperado;

        $caja->update([
            'usuario_cierre_id' => Auth::id(),
            'monto_cierre' => $validated['monto_cierre'],
            'monto_esperado' => $montoEsperado,
            'diferencia' => $diferencia,
            'hora_cierre' => now(),
            'estado' => 'cerrada',
            'observaciones' => $caja->observaciones . "\n\nCierre: " . ($validated['observaciones'] ?? ''),
        ]);

        return redirect()->route('cajas.show', $caja)
            ->with('success', 'Caja cerrada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caja $caja)
    {
        // Solo se pueden eliminar cajas cerradas sin movimientos
        if ($caja->estado === 'abierta') {
            return back()->with('error', 'No se puede eliminar una caja abierta.');
        }

        if ($caja->movimientos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la caja porque tiene movimientos asociados.');
        }

        $caja->delete();

        return redirect()->route('cajas.index')
            ->with('success', 'Caja eliminada exitosamente.');
    }
}
