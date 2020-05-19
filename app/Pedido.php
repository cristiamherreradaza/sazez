<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'almacene_solicitante_id',
        'solicitante_id',
        'almacene_id',
        'numero',
        'fecha',
        'estado',
        'deleted_at',
    ];

    public function almacen()
    {
        return $this->belongsTo('App\Almacene');
    }

    public function encargado()
    {
        return $this->belongsTo('App\User');
    }

    public function pedidos_productos()
    {
        return $this->hasMany('App\PedidosProducto');
    }

}
