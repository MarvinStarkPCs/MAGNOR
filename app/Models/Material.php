<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'materiales';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad_medida',
        'precio_compra',
        'precio_venta',
        'stock',
    ];

    // Tipos de datos automáticos
    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'decimal:2',
    ];

    // Relación con compras (si existiera tabla purchases)
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Relación con ventas (si existiera tabla sales)
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
