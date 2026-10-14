<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }

    .header { background-color: #14532d; color: #fff; padding: 16px 24px; }
    .header h1 { font-size: 18px; font-weight: bold; }
    .header p { font-size: 11px; color: #86efac; margin-top: 2px; }
    .header-right { float: right; text-align: right; }
    .header-right .num { font-size: 26px; font-weight: bold; }
    .header-right .fecha { font-size: 11px; color: #86efac; }
    .clearfix::after { content: ''; display: table; clear: both; }

    @if($recibo->anulado)
    .anulado-banner { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 8px 16px; font-size: 11px; margin: 10px 0; }
    @endif

    .section { padding: 12px 24px; border-bottom: 1px solid #e5e7eb; }
    .section-label { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 4px; }
    .grid2 { width: 100%; }
    .grid2 td { width: 50%; vertical-align: top; padding-right: 12px; }

    table.items { width: 100%; border-collapse: collapse; }
    table.items thead tr { background: #f9fafb; border-bottom: 2px solid #e5e7eb; }
    table.items thead th { padding: 7px 10px; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; }
    table.items thead th.right { text-align: right; }
    table.items tbody tr { border-bottom: 1px solid #f3f4f6; }
    table.items tbody td { padding: 7px 10px; vertical-align: top; }
    table.items tbody td.right { text-align: right; }
    table.items tfoot tr { background: #f3f4f6; border-top: 2px solid #d1d5db; }
    table.items tfoot td { padding: 8px 10px; font-weight: bold; }
    table.items tfoot td.right { text-align: right; }

    .mat-nombre { font-weight: 600; color: #111827; }
    .mat-familia { font-size: 9px; color: #9ca3af; }

    .total-label { font-size: 11px; color: #374151; }
    .total-value { font-size: 16px; font-weight: bold; color: #15803d; }

    .obs { padding: 10px 24px; font-size: 10px; color: #4b5563; border-top: 1px solid #e5e7eb; }
    .obs-label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #9ca3af; margin-bottom: 3px; }

    .footer { margin-top: 24px; padding: 0 24px; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }

    .firma-section { margin-top: 32px; padding: 0 24px; }
    .firma-grid { width: 100%; }
    .firma-grid td { width: 50%; text-align: center; padding: 0 20px; }
    .firma-line { border-top: 1px solid #374151; margin-bottom: 4px; margin-top: 36px; }
    .firma-label { font-size: 9px; color: #6b7280; }
    .firma-nombre { font-size: 10px; font-weight: 600; }
</style>
</head>
<body>

<div class="header clearfix">
    <div class="header-right">
        <div class="num">#{{ str_pad($recibo->numero, 5, '0', STR_PAD_LEFT) }}</div>
        <div class="fecha">{{ \Carbon\Carbon::parse($recibo->fecha)->format('d/m/Y') }}</div>
    </div>
    <h1>{{ $empresa['nombre'] ?? 'ARECUPERAR' }}</h1>
    <p>Recibo de compra de materiales reciclables</p>
    @if($empresa['municipio'] ?? null)
    <p style="margin-top:2px;">{{ $empresa['municipio'] }}</p>
    @endif
</div>

@if($recibo->anulado)
<div class="anulado-banner">
    <strong>RECIBO ANULADO</strong>
    @if($recibo->anulado_motivo) — Motivo: {{ $recibo->anulado_motivo }} @endif
</div>
@endif

<div class="section">
    <table class="grid2">
        <tr>
            <td>
                <div class="section-label">Reciclador</div>
                <div style="font-weight:600; font-size:12px;">{{ $recibo->reciclador->nombre }}</div>
                <div style="color:#6b7280;">C.C. {{ $recibo->reciclador->documento }}</div>
                @if($recibo->reciclador->telefono)
                <div style="color:#6b7280; margin-top:2px;">Tel: {{ $recibo->reciclador->telefono }}</div>
                @endif
            </td>
            <td>
                <div class="section-label">Municipio</div>
                <div>{{ $recibo->reciclador->municipio->nombre ?? '—' }}</div>
                @if($recibo->reciclador->direccion)
                <div style="color:#6b7280; margin-top:2px;">{{ $recibo->reciclador->direccion }}</div>
                @endif
            </td>
        </tr>
    </table>
</div>

<table class="items">
    <thead>
        <tr>
            <th style="text-align:left; padding-left:24px;">Material</th>
            <th class="right">KG Bruto</th>
            <th class="right">KG Rechazo</th>
            <th class="right">KG Neto</th>
            <th class="right">$/KG</th>
            <th class="right" style="padding-right:24px;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recibo->detalles as $d)
        <tr>
            <td style="padding-left:24px;">
                <div class="mat-nombre">{{ $d->material->nombre }}</div>
                <div class="mat-familia">{{ $d->material->familia->nombre }}</div>
            </td>
            <td class="right">{{ number_format($d->kg_bruto, 2) }}</td>
            <td class="right">{{ number_format($d->kg_rechazado, 2) }}</td>
            <td class="right">{{ number_format($d->kg_neto, 2) }}</td>
            <td class="right">${{ number_format($d->precio_kg, 0, ',', '.') }}</td>
            <td class="right" style="padding-right:24px;">${{ number_format($d->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td style="padding-left:24px;" class="total-label">TOTAL A PAGAR</td>
            <td class="right">{{ number_format($recibo->detalles->sum(fn($d) => $d->kg_bruto), 2) }}</td>
            <td class="right">—</td>
            <td class="right">{{ number_format($recibo->detalles->sum(fn($d) => $d->kg_neto), 2) }}</td>
            <td></td>
            <td class="right total-value" style="padding-right:24px;">${{ number_format($recibo->total, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

@if($recibo->observaciones)
<div class="obs">
    <div class="obs-label">Observaciones</div>
    {{ $recibo->observaciones }}
</div>
@endif

<div class="firma-section">
    <table class="firma-grid">
        <tr>
            <td>
                <div class="firma-line"></div>
                <div class="firma-nombre">{{ $recibo->reciclador->nombre }}</div>
                <div class="firma-label">C.C. {{ $recibo->reciclador->documento }} — Reciclador</div>
            </td>
            <td>
                <div class="firma-line"></div>
                <div class="firma-nombre">{{ $empresa['nombre'] ?? 'ARECUPERAR' }}</div>
                <div class="firma-label">Empresa recuperadora — Pagador</div>
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    Generado el {{ now()->format('d/m/Y H:i') }} — Documento de compra de materiales reciclables
    @if($empresa['nueca'] ?? null) — NUECA: {{ $empresa['nueca'] }} @endif
</div>

</body>
</html>
