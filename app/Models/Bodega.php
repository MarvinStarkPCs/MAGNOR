<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bodega extends Model
{
    protected $fillable = ['nombre', 'direccion', 'activa'];

    protected $casts = ['activa' => 'boolean'];

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'bodega_id');
    }

    public function recibos(): HasMany
    {
        return $this->hasMany(Recibo::class, 'bodega_id');
    }
}
