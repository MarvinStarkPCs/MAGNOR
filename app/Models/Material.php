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
        'precio_dia_compra',
        'precio_dia_venta',
        'fecha_actualizacion_precio',
        'stock',
    ];

    // Tipos de datos automáticos
    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'precio_dia_compra' => 'decimal:2',
        'precio_dia_venta' => 'decimal:2',
        'stock' => 'decimal:2',
        'fecha_actualizacion_precio' => 'date',
    ];

    // Relación con detalles de compras
    public function detallesCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    // Relación con detalles de ventas
    public function detallesVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
