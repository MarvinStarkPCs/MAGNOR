<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    public $timestamps = false;
    protected $fillable = ['placa', 'tipo', 'activo'];
}
