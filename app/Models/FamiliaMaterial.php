<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamiliaMaterial extends Model
{
    public $timestamps = false;
    protected $table = 'familias_material';
    protected $fillable = ['nombre', 'codigo_sui', 'activo'];

    public function materiales(): HasMany
    {
        return $this->hasMany(Material::class, 'familia_id');
    }
}
