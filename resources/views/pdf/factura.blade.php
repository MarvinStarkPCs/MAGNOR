<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $venta->id }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
            padding: 0;
            margin: 0;
        }

        .page-wrapper {
            width: 100%;
            height: 14.85cm;
            max-height: 14.85cm;
            padding: 8mm 10mm;
            margin: 0;
            overflow: hidden;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #15803d;
            padding-bottom: 12px;
        }

        .header .logo {
            max-width: 120px;
            height: auto;
            margin: 0 auto 8px;
            display: block;
        }

        .header p {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .header h2 {
            font-size: 18px;
            color: #15803d;
            font-weight: bold;
            margin-top: 8px;
        }

        .invoice-info {
            margin-bottom: 6px;
            overflow: hidden;
        }

        .invoice-info .left {
            float: left;
            width: 50%;
        }

        .invoice-info .right {
            float: right;
            width: 45%;
        }

        .info-box {
            background-color: #f9fafb;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .info-box h3 {
            font-size: 9px;
            margin-bottom: 4px;
            color: #15803d;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
            font-weight: bold;
        }

        .info-box p {
            margin: 2px 0;
            font-size: 7px;
        }

        .info-box strong {
            font-weight: bold;
            font-size: 7px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        table thead {
            background-color: #15803d;
            color: white;
        }

        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 7px;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 7px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table td strong {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            float: right;
            width: 180px;
            margin-top: 4px;
        }

        .totals table {
            margin-bottom: 0;
        }

        .totals td {
            padding: 3px 4px;
            font-size: 7px;
        }

        .totals .grand-total {
            background-color: #15803d;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .observations {
            clear: both;
            margin-top: 12px;
            padding: 8px;
            background-color: #f9fafb;
            border-radius: 4px;
        }

        .observations h3 {
            font-size: 9px;
            margin-bottom: 4px;
            color: #15803d;
            font-weight: bold;
        }

        .observations p {
            font-size: 12px;
        }

        .footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #15803d;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
    <div class="header">
        @if(file_exists(public_path('images/logo_factura.png')))
            <img src="{{ public_path('images/logo_factura.png') }}" alt="MAGNOR" class="logo">
        @else
            <h1 style="font-size: 24px; color: #146e39; margin: 10px 0;">MAGNOR</h1>
        @endif
        <p>Sistema de Gestión de Chatarrería | Soluciones en Reciclaje y Materiales</p>
        <h2>FACTURA DE VENTA</h2>
    </div>

    <div class="invoice-info">
        <div class="left">
            <div class="info-box">
                <h3>Información de la Venta</h3>
                <p><span class="label">No. Factura:</span> #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><span class="label">Fecha:</span> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</p>
                <p><span class="label">Emisión:</span> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="right">
            @if($venta->cliente)
            <div class="info-box">
                <h3>Cliente</h3>
                <p><span class="label">Nombre:</span> {{ $venta->cliente->nombre }}</p>
                @if($venta->cliente->documento)
                <p><span class="label">Documento:</span> {{ $venta->cliente->documento }}</p>
                @endif
                @if($venta->cliente->telefono)
                <p><span class="label">Teléfono:</span> {{ $venta->cliente->telefono }}</p>
                @endif
                @if($venta->cliente->direccion)
                <p><span class="label">Dirección:</span> {{ $venta->cliente->direccion }}</p>
                @endif
            </div>
            @else
            <div class="info-box">
                <h3>Cliente</h3>
                <p style="color: #999; font-style: italic;">Cliente General</p>
            </div>
            @endif
        </div>
    </div>

    <div style="clear: both;"></div>

    <h3 style="margin-bottom: 8px; margin-top: 12px; color: #146e39; font-size: 12px; border-bottom: 1px solid #146e39; padding-bottom: 3px;">Detalle de Materiales</h3>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">Material</th>
                <th style="width: 15%;" class="text-center">Cantidad</th>
                <th style="width: 20%;" class="text-right">Precio Unitario</th>
                <th style="width: 25%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $index => $detalle)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $detalle->material->nombre }}</strong>
                </td>
                <td class="text-center">
                    {{ number_format($detalle->cantidad, 2) }}
                    {{ match($detalle->material->unidad_medida) {
                        'kg' => 'kg',
                        'unidad' => 'unid.',
                        'tonelada' => 'ton.',
                        'metro' => 'm',
                        'litro' => 'L',
                        default => $detalle->material->unidad_medida,
                    } }}
                </td>
                <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td class="text-right"><strong>${{ number_format($detalle->subtotal, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right">${{ number_format($venta->total * 0.85, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IVA (15%):</strong></td>
                <td class="text-right">${{ number_format($venta->total * 0.15, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>${{ number_format($venta->total, 2) }} COP</strong></td>
            </tr>
        </table>
    </div>

    @if($venta->observaciones)
    <div class="observations">
        <h3>Observaciones</h3>
        <p>{{ $venta->observaciones }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>MAGNOR - Sistema de Gestión de Chatarrería</p>
    </div>
    </div>
</body>
</html>
