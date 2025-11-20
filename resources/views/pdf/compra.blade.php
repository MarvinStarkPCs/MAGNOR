<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Compra #{{ $compra->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #d97706;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        .invoice-info {
            margin-bottom: 25px;
            overflow: hidden;
        }

        .invoice-info .left {
            float: left;
            width: 50%;
        }

        .invoice-info .right {
            float: right;
            width: 45%;
            text-align: right;
        }

        .info-box {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .info-box h3 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #d97706;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-box p {
            margin: 4px 0;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #d97706;
            color: white;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            float: right;
            width: 300px;
            margin-top: 10px;
        }

        .totals table {
            margin-bottom: 0;
        }

        .totals td {
            padding: 8px;
        }

        .totals .grand-total {
            background-color: #d97706;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .observations {
            clear: both;
            margin-top: 40px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .observations h3 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #d97706;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #10b981;
            color: white;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>MAGNOR</h1>
        <p>Sistema de Gestión de Chatarrería</p>
    </div>

    <div class="invoice-info">
        <div class="left">
            <div class="info-box">
                <h3>Información de la Compra</h3>
                <p><span class="label">ID de Compra:</span> #{{ $compra->id }}</p>
                <p><span class="label">Fecha:</span> {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</p>
                <p><span class="label">Creado:</span> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="right">
            @if($compra->proveedor)
            <div class="info-box">
                <h3>
                    Proveedor
                    @if($compra->proveedor->es_reciclador)
                        <span class="badge">RECICLADOR</span>
                    @endif
                </h3>
                <p><span class="label">Nombre:</span> {{ $compra->proveedor->nombre }}</p>
                @if($compra->proveedor->documento)
                <p><span class="label">Documento:</span> {{ $compra->proveedor->documento }}</p>
                @endif
                @if($compra->proveedor->telefono)
                <p><span class="label">Teléfono:</span> {{ $compra->proveedor->telefono }}</p>
                @endif
                @if($compra->proveedor->direccion)
                <p><span class="label">Dirección:</span> {{ $compra->proveedor->direccion }}</p>
                @endif
            </div>
            @else
            <div class="info-box">
                <h3>Proveedor</h3>
                <p style="color: #999; font-style: italic;">Sin proveedor asignado</p>
            </div>
            @endif
        </div>
    </div>

    <div style="clear: both;"></div>

    <h3 style="margin-bottom: 10px; color: #d97706;">Detalle de Materiales</h3>

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
            @foreach($compra->detalles as $index => $detalle)
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
                <td class="text-right">${{ number_format($compra->total, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>${{ number_format($compra->total, 2) }} COP</strong></td>
            </tr>
        </table>
    </div>

    @if($compra->observaciones)
    <div class="observations">
        <h3>Observaciones</h3>
        <p>{{ $compra->observaciones }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>MAGNOR - Sistema de Gestión de Chatarrería</p>
    </div>
</body>
</html>
