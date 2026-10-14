<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    private array $claves = [
        'empresa_nombre', 'empresa_municipio',
        'nueca', 'numacro',
        'numero_sitio_destino', 'tipo_sitio_destino',
        'retefuente_porcentaje', 'porcentaje_rechazo_def',
        'placa_sin_vehiculo',
    ];

    public function edit()
    {
        $config = ConfiguracionEmpresa::whereIn('clave', $this->claves)
            ->get()
            ->keyBy('clave')
            ->map(fn($c) => ['valor' => $c->valor, 'descripcion' => $c->descripcion]);

        return Inertia::render('settings/empresa', ['config' => $config]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'empresa_nombre'        => 'required|string|max:150',
            'empresa_municipio'     => 'required|string|max:100',
            'nueca'                 => 'required|string|max:30',
            'numacro'               => 'required|string|max:30',
            'numero_sitio_destino'  => 'required|string|max:30',
            'tipo_sitio_destino'    => 'required|integer|min:1',
            'retefuente_porcentaje' => 'required|numeric|min:0|max:100',
            'porcentaje_rechazo_def'=> 'required|numeric|min:0|max:100',
            'placa_sin_vehiculo'    => 'required|string|max:10',
        ]);

        foreach ($validated as $clave => $valor) {
            ConfiguracionEmpresa::set($clave, $valor);
        }

        return back()->with('success', 'Configuración guardada.');
    }
}
