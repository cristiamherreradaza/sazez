<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedore extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombre',
        'direccion',
        'telefonos',
        'estado',
        'deleted_at',
    ];

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }
}
