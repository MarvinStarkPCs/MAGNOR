<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionEmpresa;
use App\Models\DetalleRecibo;
use App\Models\FamiliaMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistroController extends Controller
{
    public function index(Request $request)
    {
        $query = DetalleRecibo::query()
            ->join('recibos', 'recibos.id', '=', 'detalle_recibo.recibo_id')
            ->join('recicladores', 'recicladores.id', '=', 'recibos.reciclador_id')
            ->join('materiales', 'materiales.id', '=', 'detalle_recibo.material_id')
            ->join('familias_material', 'familias_material.id', '=', 'materiales.familia_id')
            ->where('recibos.anulado', false)
            ->select([
                'detalle_recibo.id',
                'recibos.numero as numero_recibo',
                'recibos.fecha',
                'recibos.semana',
                'recicladores.nombre as reciclador',
                'familias_material.nombre as familia',
                'materiales.nombre as material',
                'detalle_recibo.kg_bruto',
                'detalle_recibo.kg_rechazado',
                'detalle_recibo.kg_neto',
                'detalle_recibo.precio_kg',
                'detalle_recibo.subtotal',
            ])
            ->orderBy('recibos.fecha', 'desc')
            ->orderBy('recibos.numero', 'desc');

        if ($request->filled('desde')) {
            $query->where('recibos.fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->where('recibos.fecha', '<=', $request->hasta);
        }
        if ($request->filled('familia_id')) {
            $query->where('materiales.familia_id', $request->familia_id);
        }
        if ($request->filled('semana')) {
            $query->where('recibos.semana', $request->semana);
        }

        $registros = $query->paginate(50)->withQueryString();

        $totales = DetalleRecibo::query()
            ->join('recibos', 'recibos.id', '=', 'detalle_recibo.recibo_id')
            ->join('materiales', 'materiales.id', '=', 'detalle_recibo.material_id')
            ->where('recibos.anulado', false)
            ->when($request->filled('desde'), fn($q) => $q->where('recibos.fecha', '>=', $request->desde))
            ->when($request->filled('hasta'), fn($q) => $q->where('recibos.fecha', '<=', $request->hasta))
            ->when($request->filled('familia_id'), fn($q) => $q->where('materiales.familia_id', $request->familia_id))
            ->when($request->filled('semana'), fn($q) => $q->where('recibos.semana', $request->semana))
            ->selectRaw('COALESCE(SUM(kg_bruto),0) as total_kg_bruto, COALESCE(SUM(kg_neto),0) as total_kg_neto, COALESCE(SUM(detalle_recibo.subtotal),0) as total_pagado, COUNT(*) as total_filas')
            ->first();

        $familias = FamiliaMaterial::orderBy('nombre')->get(['id', 'nombre']);

        return Inertia::render('Registro/Index', [
            'registros' => $registros,
            'totales'   => $totales,
            'familias'  => $familias,
            'filtros'   => $request->only(['desde', 'hasta', 'familia_id', 'semana']),
        ]);
    }

    public function exportarSui(Request $request)
    {
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        $nueca   = ConfiguracionEmpresa::get('nueca', '');
        $numacro = ConfiguracionEmpresa::get('numacro', '');
        $numSitio = ConfiguracionEmpresa::get('numero_sitio_destino', '');

        $query = DetalleRecibo::query()
            ->join('recibos', 'recibos.id', '=', 'detalle_recibo.recibo_id')
            ->join('recicladores', 'recicladores.id', '=', 'recibos.reciclador_id')
            ->join('materiales', 'materiales.id', '=', 'detalle_recibo.material_id')
            ->leftJoin('vehiculos', 'vehiculos.id', '=', 'recicladores.vehiculo_id')
            ->where('recibos.anulado', false)
            ->select([
                'recibos.fecha',
                'recicladores.tipo_documento_id',
                'recicladores.documento',
                'vehiculos.placa',
                'materiales.codigo_sui',
                'detalle_recibo.cantidad_entrante_ton',
                'detalle_recibo.rechazo_ton',
                'recicladores.tipo_sitio_destino_id',
                'recicladores.numero_sitio_destino',
            ])
            ->orderBy('recibos.fecha')
            ->orderBy('recibos.numero');

        if ($desde) {
            $query->where('recibos.fecha', '>=', $desde);
        }
        if ($hasta) {
            $query->where('recibos.fecha', '<=', $hasta);
        }

        $filename = 'SUI_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query, $nueca, $numacro, $numSitio) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'NUECA', 'NUMACRO', 'FECHA',
                'TIPO_IDENTIFICACION', 'NUMERO_IDENTIFICACION', 'PLACA_VEHICULO',
                'TIPO_MATERIAL_ENTRANTE', 'CANTIDAD_MATERIAL_ENTRANTE_TON',
                'RECHAZO_TON', 'TIPO_SITIO_DESTINO', 'NUMERO_SITIO_DESTINO',
            ], ';');

            $query->chunk(500, function ($rows) use ($out, $nueca, $numacro, $numSitio) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $nueca,
                        $numacro,
                        $row->fecha,
                        $row->tipo_documento_id ?? 1,
                        $row->documento,
                        $row->placa ?? '999',
                        $row->codigo_sui,
                        number_format($row->cantidad_entrante_ton, 6, '.', ''),
                        number_format($row->rechazo_ton, 6, '.', ''),
                        $row->tipo_sitio_destino_id ?? 1,
                        $row->numero_sitio_destino ?? $numSitio,
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportarCsv(Request $request)
    {
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        $query = DetalleRecibo::query()
            ->join('recibos', 'recibos.id', '=', 'detalle_recibo.recibo_id')
            ->join('recicladores', 'recicladores.id', '=', 'recibos.reciclador_id')
            ->join('materiales', 'materiales.id', '=', 'detalle_recibo.material_id')
            ->join('familias_material', 'familias_material.id', '=', 'materiales.familia_id')
            ->where('recibos.anulado', false)
            ->select([
                'recibos.numero as numero_recibo',
                'recibos.fecha',
                'recibos.semana',
                'recicladores.nombre as reciclador',
                'recicladores.documento',
                'familias_material.nombre as familia',
                'materiales.nombre as material',
                'detalle_recibo.kg_bruto',
                'detalle_recibo.kg_rechazado',
                'detalle_recibo.kg_neto',
                'detalle_recibo.precio_kg',
                'detalle_recibo.subtotal',
                'detalle_recibo.cantidad_entrante_ton',
                'detalle_recibo.rechazo_ton',
            ])
            ->orderBy('recibos.fecha')
            ->orderBy('recibos.numero');

        if ($desde) {
            $query->where('recibos.fecha', '>=', $desde);
        }
        if ($hasta) {
            $query->where('recibos.fecha', '<=', $hasta);
        }

        $filename = 'Registro_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM para Excel
            fputcsv($out, [
                'RECIBO', 'FECHA', 'SEMANA', 'RECICLADOR', 'DOCUMENTO',
                'FAMILIA', 'MATERIAL',
                'KG_BRUTO', 'KG_RECHAZADO', 'KG_NETO',
                'PRECIO_KG', 'SUBTOTAL',
                'CANT_ENTRANTE_TON', 'RECHAZO_TON',
            ], ';');

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->numero_recibo,
                        $row->fecha,
                        $row->semana,
                        $row->reciclador,
                        $row->documento,
                        $row->familia,
                        $row->material,
                        $row->kg_bruto,
                        $row->kg_rechazado,
                        $row->kg_neto,
                        $row->precio_kg,
                        $row->subtotal,
                        $row->cantidad_entrante_ton,
                        $row->rechazo_ton,
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
