<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Compra;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    /**
     * Open Venta PDF in browser for printing
     */
    public function print($id)
    {
        $venta = Venta::with(['cliente', 'detalles.material'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.factura', compact('venta'));

        return $pdf->stream('factura-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Download Venta PDF file
     */
    public function download($id)
    {
        $venta = Venta::with(['cliente', 'detalles.material'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.factura', compact('venta'));

        return $pdf->download('factura-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Open Compra PDF in browser for printing
     */
    public function printCompra($id)
    {
        $compra = Compra::with(['proveedor', 'detalles.material'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.compra', compact('compra'));

        return $pdf->stream('factura-compra-' . str_pad($compra->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Download Compra PDF file
     */
    public function downloadCompra($id)
    {
        $compra = Compra::with(['proveedor', 'detalles.material'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.compra', compact('compra'));

        return $pdf->download('factura-compra-' . str_pad($compra->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Generar factura de venta (alias para print)
     */
    public function generarFactura($id)
    {
        return $this->print($id);
    }

    /**
     * Generar factura de compra (alias para printCompra)
     */
    public function generarFacturaCompra($id)
    {
        return $this->printCompra($id);
    }
}
