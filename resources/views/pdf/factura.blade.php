<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $venta->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 30px 0;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 30px 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #146e39;
            padding-bottom: 15px;
        }
        .logo {
            max-width: 220px;
            height: auto;
            margin: 0 auto 10px;
            display: block;
        }
        .company-info {
            font-size: 10px;
            color: #666;
            margin-bottom: 15px;
        }
        .invoice-header-row {
            display: table;
            width: 100%;
            margin-top: 12px;
        }
        .invoice-header-left {
            display: table-cell;
            width: 50%;
            text-align: left;
            vertical-align: middle;
        }
        .invoice-header-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #146e39;
            margin-bottom: 5px;
        }
        .invoice-details {
            font-size: 10px;
            line-height: 1.5;
        }
        .invoice-details strong {
            color: #146e39;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #146e39;
            margin: 12px 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #146e39;
        }
        .info-block {
            margin-bottom: 12px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #146e39;
        }
        .info-block p {
            margin: 3px 0;
        }
        .info-block strong {
            color: #146e39;
            min-width: 100px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table thead {
            background-color: #146e39;
            color: white;
        }
        table th {
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        table tbody tr {
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table td {
            padding: 7px 6px;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            margin-top: 15px;
            float: right;
            width: 250px;
        }
        .totals table {
            margin: 0;
        }
        .totals table td {
            padding: 6px;
            border: none;
            font-size: 10px;
        }
        .totals table tr.total-row {
            background-color: #146e39;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        .totals table tr.subtotal-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #146e39;
            text-align: center;
            font-size: 9px;
            color: #666;
            clear: both;
        }
        .notes {
            margin-top: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #f78921;
            clear: both;
        }
        .notes p {
            margin: 3px 0;
            font-size: 10px;
            color: #666;
        }
        .notes strong {
            color: #f78921;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('images/logo_factura.png') }}" alt="MAGNOR" class="logo">
            <div class="company-info">
                Sistema de Gestión de Chatarrería | Soluciones en Reciclaje y Materiales
            </div>
            <div class="invoice-header-row">
                <div class="invoice-header-left">
                    <div class="invoice-title">FACTURA DE VENTA</div>
                </div>
                <div class="invoice-header-right">
                    <div class="invoice-details">
                        <strong>No. Factura:</strong> #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}<br>
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}<br>
                        <strong>Emisión:</strong> {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="section-title">Información del Cliente</div>
        <div class="info-block">
            @if($venta->cliente)
                <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? 'N/A' }}</p>
                <p><strong>ID Cliente:</strong> #{{ $venta->cliente->id }}</p>
            @else
                <p><strong>Cliente:</strong> Cliente General</p>
            @endif
        </div>

        <!-- Items Table -->
        <div class="section-title">Detalle de la Venta</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 40%">Material</th>
                    <th style="width: 15%" class="text-right">Cantidad</th>
                    <th style="width: 15%" class="text-right">Precio Unit.</th>
                    <th style="width: 15%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venta->detalles ?? [] as $index => $detalle)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detalle->material->nombre ?? 'Material' }}</td>
                    <td class="text-right">{{ number_format($detalle->cantidad, 2) }} kg</td>
                    <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-right">${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay detalles disponibles</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr class="subtotal-row">
                    <td>Subtotal:</td>
                    <td class="text-right">${{ number_format($venta->total * 0.85, 2) }}</td>
                </tr>
                <tr class="subtotal-row">
                    <td>IVA (15%):</td>
                    <td class="text-right">${{ number_format($venta->total * 0.15, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td class="text-right">${{ number_format($venta->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Observaciones -->
        @if($venta->observaciones)
        <div class="notes">
            <p><strong>Observaciones:</strong></p>
            <p>{{ $venta->observaciones }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por su preferencia</p>
            <p>MAGNOR - Sistema de Gestión de Chatarrería</p>
            <p>Este documento es una representación impresa de una factura electrónica</p>
        </div>
    </div>
</body>
</html>
