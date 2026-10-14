<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    public $timestamps = false;
    protected $fillable = ['nombre', 'departamento', 'codigo_dane', 'activo'];
}
