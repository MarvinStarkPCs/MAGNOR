<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Material;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Compra::with('proveedor');

        // Búsqueda
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('proveedor', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        // Filtrar por fecha
        if ($request->has('fecha_desde')) {
            $query->where('fecha_compra', '>=', $request->input('fecha_desde'));
        }
        if ($request->has('fecha_hasta')) {
            $query->where('fecha_compra', '<=', $request->input('fecha_hasta'));
        }

        $compras = $query->orderBy('fecha_compra', 'desc')->paginate(10)->withQueryString();

        return Inertia::render('Compras/Index', [
            'compras' => $compras,
            'filters' => $request->only(['search', 'fecha_desde', 'fecha_hasta']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        $materiales = Material::orderBy('nombre', 'asc')->get();

        return Inertia::render('Compras/Create', [
            'proveedores' => $proveedores,
            'materiales' => $materiales,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.material_id' => 'required|exists:materiales,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calcular total
            $total = 0;
            foreach ($validated['detalles'] as $detalle) {
                $total += $detalle['cantidad'] * $detalle['precio_unitario'];
            }

            // Crear compra
            $compra = Compra::create([
                'proveedor_id' => $validated['proveedor_id'],
                'fecha_compra' => $validated['fecha_compra'],
                'total' => $total,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Crear detalles y actualizar stock
            foreach ($validated['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'material_id' => $detalle['material_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                // Actualizar stock del material
                $material = Material::find($detalle['material_id']);
                $material->increment('stock', $detalle['cantidad']);
            }

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'detalles.material']);

        return Inertia::render('Compras/Show', [
            'compra' => $compra,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        $compra->load('detalles');
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        $materiales = Material::orderBy('nombre', 'asc')->get();

        return Inertia::render('Compras/Edit', [
            'compra' => $compra,
            'proveedores' => $proveedores,
            'materiales' => $materiales,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.material_id' => 'required|exists:materiales,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Revertir stock de los detalles antiguos
            foreach ($compra->detalles as $detalle) {
                $material = Material::find($detalle->material_id);
                $material->decrement('stock', $detalle->cantidad);
            }

            // Eliminar detalles antiguos
            $compra->detalles()->delete();

            // Calcular nuevo total
            $total = 0;
            foreach ($validated['detalles'] as $detalle) {
                $total += $detalle['cantidad'] * $detalle['precio_unitario'];
            }

            // Actualizar compra
            $compra->update([
                'proveedor_id' => $validated['proveedor_id'],
                'fecha_compra' => $validated['fecha_compra'],
                'total' => $total,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Crear nuevos detalles y actualizar stock
            foreach ($validated['detalles'] as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];

                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'material_id' => $detalle['material_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $subtotal,
                ]);

                // Actualizar stock del material
                $material = Material::find($detalle['material_id']);
                $material->increment('stock', $detalle['cantidad']);
            }

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        DB::beginTransaction();

        try {
            // Revertir stock de los detalles
            foreach ($compra->detalles as $detalle) {
                $material = Material::find($detalle->material_id);

                // Verificar que hay suficiente stock para revertir
                if ($material->stock < $detalle->cantidad) {
                    DB::rollBack();
                    return back()->with('error', 'No se puede eliminar la compra porque el material ' . $material->nombre . ' no tiene suficiente stock para revertir la operación.');
                }

                $material->decrement('stock', $detalle->cantidad);
            }

            // Eliminar detalles y compra
            $compra->detalles()->delete();
            $compra->delete();

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}
