<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Material;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Venta::with('cliente');

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('cliente', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        // Filtrar por fecha
        if ($request->has('fecha_desde')) {
            $query->where('fecha', '>=', $request->input('fecha_desde'));
        }
        if ($request->has('fecha_hasta')) {
            $query->where('fecha', '<=', $request->input('fecha_hasta'));
        }

        $ventas = $query->orderBy('fecha', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('Ventas/Index', [
            'ventas' => $ventas,
            'filters' => $request->only(['search', 'fecha_desde', 'fecha_hasta']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre', 'asc')->get();
        $materiales = Material::where('stock', '>', 0)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Ventas/Create', [
            'clientes' => $clientes,
            'materiales' => $materiales,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.material_id' => 'required|exists:materiales,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Verificar stock disponible
            foreach ($validated['detalles'] as $detalle) {
                $material = Material::find($detalle['material_id']);
                if ($material->stock < $detalle['cantidad']) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Stock insuficiente para el material: ' . $material->nombre . '. Stock disponible: ' . $material->stock);
                }
            }

            // Calcular total
            $total = 0;
            foreach ($validated['detalles'] as $detalle) {
                $total += $detalle['cantidad'] * $detalle['precio_unitario'];
            }

            // Crear venta
            $venta = Venta::create([
                'cliente_id' => $validated['cliente_id'],
                'fecha' => $validated['fecha'],
                'total' => $total,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Crear detalles y actualizar stock
            foreach ($validated['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'material_id' => $detalle['material_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                // Actualizar stock del material (disminuir)
                $material = Material::find($detalle['material_id']);
                $material->decrement('stock', $detalle['cantidad']);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'detalles.material']);

        return Inertia::render('Ventas/Show', [
            'venta' => $venta,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        $venta->load('detalles');
        $clientes = Cliente::orderBy('nombre', 'asc')->get();
        $materiales = Material::orderBy('nombre', 'asc')->get();

        return Inertia::render('Ventas/Edit', [
            'venta' => $venta,
            'clientes' => $clientes,
            'materiales' => $materiales,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.material_id' => 'required|exists:materiales,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Revertir stock de los detalles antiguos (sumar de vuelta)
            foreach ($venta->detalles as $detalle) {
                $material = Material::find($detalle->material_id);
                $material->increment('stock', $detalle->cantidad);
            }

            // Verificar stock disponible para los nuevos detalles
            foreach ($validated['detalles'] as $detalle) {
                $material = Material::find($detalle['material_id']);
                if ($material->stock < $detalle['cantidad']) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Stock insuficiente para el material: ' . $material->nombre . '. Stock disponible: ' . $material->stock);
                }
            }

            // Eliminar detalles antiguos
            $venta->detalles()->delete();

            // Calcular nuevo total
            $total = 0;
            foreach ($validated['detalles'] as $detalle) {
                $total += $detalle['cantidad'] * $detalle['precio_unitario'];
            }

            // Actualizar venta
            $venta->update([
                'cliente_id' => $validated['cliente_id'],
                'fecha' => $validated['fecha'],
                'total' => $total,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Crear nuevos detalles y actualizar stock
            foreach ($validated['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'material_id' => $detalle['material_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                // Actualizar stock del material (disminuir)
                $material = Material::find($detalle['material_id']);
                $material->decrement('stock', $detalle['cantidad']);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        DB::beginTransaction();

        try {
            // Revertir stock de los detalles (sumar de vuelta)
            foreach ($venta->detalles as $detalle) {
                $material = Material::find($detalle->material_id);
                $material->increment('stock', $detalle->cantidad);
            }

            // Eliminar detalles y venta
            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}
