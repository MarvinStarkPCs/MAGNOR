<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaCaja extends Model
{
    protected $table = 'categorias_caja';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Obtener los movimientos de esta categoría
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class, 'categoria_id');
    }
}
