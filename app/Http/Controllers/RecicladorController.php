<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Reciclador;
use App\Models\RutaReciclador;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecicladorController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Reciclador::with(['municipio', 'vehiculo'])
            ->when($request->search, fn($q, $s) =>
                $q->where('nombre', 'like', "%$s%")
                  ->orWhere('documento', 'like', "%$s%")
            )
            ->when($request->filled('activo'), fn($q) =>
                $q->where('activo', $request->boolean('activo'))
            )
            ->orderBy('nombre');

        return Inertia::render('Recicladores/Index', [
            'recicladores' => $query->paginate(20)->withQueryString(),
            'filtros'      => $request->only('search', 'activo'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Recicladores/Form', [
            'municipios' => Municipio::orderBy('nombre')->get(),
            'vehiculos'  => Vehiculo::where('activo', true)->orderBy('placa')->get(),
            'dias'       => $this->dias(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'documento' => 'required|string|max:20|unique:recicladores,documento',
            'telefono'  => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:250',
            'municipio_id' => 'nullable|exists:municipios,id',
            'vehiculo_id'  => 'nullable|exists:vehiculos,id',
            'activo'    => 'boolean',
            'rutas'     => 'nullable|array',
            'rutas.*.dia_id'      => 'required|integer|between:1,7',
            'rutas.*.descripcion' => 'nullable|string',
        ]);

        $reciclador = Reciclador::create($data);

        if (!empty($data['rutas'])) {
            foreach ($data['rutas'] as $ruta) {
                if (!empty($ruta['descripcion'])) {
                    RutaReciclador::create([
                        'reciclador_id' => $reciclador->id,
                        'dia_id'        => $ruta['dia_id'],
                        'descripcion'   => $ruta['descripcion'],
                    ]);
                }
            }
        }

        return redirect()->route('recicladores.index')->with('success', 'Reciclador registrado correctamente.');
    }

    public function edit(Reciclador $reciclador): Response
    {
        return Inertia::render('Recicladores/Form', [
            'reciclador' => $reciclador->load('rutas'),
            'municipios' => Municipio::orderBy('nombre')->get(),
            'vehiculos'  => Vehiculo::where('activo', true)->orderBy('placa')->get(),
            'dias'       => $this->dias(),
        ]);
    }

    public function update(Request $request, Reciclador $reciclador): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:150',
            'documento' => "required|string|max:20|unique:recicladores,documento,{$reciclador->id}",
            'telefono'  => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:250',
            'municipio_id' => 'nullable|exists:municipios,id',
            'vehiculo_id'  => 'nullable|exists:vehiculos,id',
            'activo'    => 'boolean',
            'rutas'     => 'nullable|array',
            'rutas.*.dia_id'      => 'required|integer|between:1,7',
            'rutas.*.descripcion' => 'nullable|string',
        ]);

        $reciclador->update($data);

        $reciclador->rutas()->delete();
        foreach (($data['rutas'] ?? []) as $ruta) {
            if (!empty($ruta['descripcion'])) {
                RutaReciclador::create([
                    'reciclador_id' => $reciclador->id,
                    'dia_id'        => $ruta['dia_id'],
                    'descripcion'   => $ruta['descripcion'],
                ]);
            }
        }

        return redirect()->route('recicladores.index')->with('success', 'Reciclador actualizado.');
    }

    public function destroy(Reciclador $reciclador): RedirectResponse
    {
        $reciclador->update(['activo' => false]);
        return redirect()->route('recicladores.index')->with('success', 'Reciclador desactivado.');
    }

    public function buscar(Request $request)
    {
        $term = $request->string('q');
        return Reciclador::activos()
            ->where(fn($q) => $q->where('nombre', 'like', "%$term%")->orWhere('documento', 'like', "%$term%"))
            ->orderBy('nombre')
            ->limit(15)
            ->get(['id', 'nombre', 'documento', 'telefono']);
    }

    private function dias(): array
    {
        return [
            ['id' => 1, 'nombre' => 'Lunes'],
            ['id' => 2, 'nombre' => 'Martes'],
            ['id' => 3, 'nombre' => 'Miércoles'],
            ['id' => 4, 'nombre' => 'Jueves'],
            ['id' => 5, 'nombre' => 'Viernes'],
            ['id' => 6, 'nombre' => 'Sábado'],
            ['id' => 7, 'nombre' => 'Domingo'],
        ];
    }
}
