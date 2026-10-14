<?php

namespace App\Http\Controllers;

use App\Models\DetalleRecibo;
use App\Models\Inventario;
use App\Models\Recibo;
use App\Models\Reciclador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $mes  = $request->integer('mes', now()->month);
        $anio = $request->integer('anio', now()->year);

        $resumen = Recibo::query()
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->where('anulado', false)
            ->selectRaw('COUNT(*) as total_recibos, COUNT(DISTINCT reciclador_id) as total_recicladores')
            ->first();

        $totalesKg = DetalleRecibo::query()
            ->join('recibos', 'recibos.id', '=', 'detalle_recibo.recibo_id')
            ->whereMonth('recibos.fecha', $mes)
            ->whereYear('recibos.fecha', $anio)
            ->where('recibos.anulado', false)
            ->selectRaw('
                COALESCE(SUM(kg_bruto), 0) as total_kg_bruto,
                COALESCE(SUM(kg_neto), 0) as total_kg_neto,
                COALESCE(SUM(ROUND(kg_bruto * detalle_recibo.precio_kg, 2)), 0) as total_pagado
            ')
            ->first();

        $topRecicladores = Reciclador::query()
            ->join('recibos', 'recibos.reciclador_id', '=', 'recicladores.id')
            ->join('detalle_recibo', 'detalle_recibo.recibo_id', '=', 'recibos.id')
            ->whereMonth('recibos.fecha', $mes)
            ->whereYear('recibos.fecha', $anio)
            ->where('recibos.anulado', false)
            ->groupBy('recicladores.id', 'recicladores.nombre')
            ->selectRaw('recicladores.nombre, SUM(kg_neto) as total_kg, SUM(ROUND(kg_bruto * detalle_recibo.precio_kg,2)) as total_pagado')
            ->orderByDesc('total_pagado')
            ->limit(10)
            ->get();

        $topMateriales = DB::table('detalle_recibo')
            ->join('recibos', 'recibos.id', '=', 'detalle_recibo.recibo_id')
            ->join('materiales', 'materiales.id', '=', 'detalle_recibo.material_id')
            ->whereMonth('recibos.fecha', $mes)
            ->whereYear('recibos.fecha', $anio)
            ->where('recibos.anulado', false)
            ->groupBy('materiales.id', 'materiales.nombre')
            ->selectRaw('materiales.nombre, SUM(detalle_recibo.kg_neto) as total_kg')
            ->orderByDesc('total_kg')
            ->limit(10)
            ->get();

        $actividadSemanal = Recibo::query()
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('anulado', false)
            ->join('detalle_recibo', 'detalle_recibo.recibo_id', '=', 'recibos.id')
            ->groupBy('semana')
            ->selectRaw('semana, SUM(kg_neto) as total_kg, SUM(ROUND(kg_bruto * detalle_recibo.precio_kg,2)) as total_pagado')
            ->orderBy('semana')
            ->get();

        $stockTotal = (float) Inventario::sum('cantidad_kg');
        $stockMateriales = Inventario::with('material')
            ->where('cantidad_kg', '>', 0)
            ->orderByDesc('cantidad_kg')
            ->limit(5)
            ->get()
            ->map(fn($s) => ['nombre' => $s->material->nombre, 'kg' => (float) $s->cantidad_kg]);

        return Inertia::render('dashboard', [
            'resumen'          => [
                'total_recibos'      => $resumen->total_recibos,
                'total_recicladores' => $resumen->total_recicladores,
                'total_kg_bruto'     => $totalesKg->total_kg_bruto,
                'total_kg_neto'      => $totalesKg->total_kg_neto,
                'total_pagado'       => $totalesKg->total_pagado,
            ],
            'topRecicladores'  => $topRecicladores,
            'topMateriales'    => $topMateriales,
            'actividadSemanal' => $actividadSemanal,
            'stockTotal'       => $stockTotal,
            'stockMateriales'  => $stockMateriales,
            'mes'              => $mes,
            'anio'             => $anio,
        ]);
    }
}
