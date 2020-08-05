<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimiento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'producto_id',
        'almacene_id',
        'pedido_id',
        'proveedor_id',
        'venta_id',
        'escala_id',
        'precio_compra',
        'precio_venta',
        'ingreso',
        'salida',
        'fecha',
        'numero',
        'estado',
        'devuelto',
        'descripcion',
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

    public function pedido()
    {
        return $this->belongsTo('App\Pedido');
    }

    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function escala()
    {
        return $this->belongsTo('App\Escala');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedore', 'proveedor_id');
    }
}