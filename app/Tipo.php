<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombre',
        'estado',
        'deleted_at',
    ];

    public function producto()
    {
        return $this->hasOne('App\Producto');
    }

    public function movimientos()
    {
        return $this->hasMany('App\Movimiento');
    }

    public function ventas_productos()
    {
        return $this->hasMany('App\VentasProducto');
    }

}
