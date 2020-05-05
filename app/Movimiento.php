<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'producto_id',
        'almacene_id',
        'precio_compra',
        'precio_venta',
        'ingreso',
        'salida',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacene');
    }

}
