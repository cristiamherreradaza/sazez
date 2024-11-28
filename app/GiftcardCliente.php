<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftcardCliente extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'producto_id',
        'user_id',
        'almacene_id',
        'venta_id',
        'fecha_creacion',
        'cliente_id',
        'fecha_canje',
        'descuento',
        'almacen_canje_id',
        'venta_canje_id',
        'monto_GC',
        'serial',
        'nombre',
        'ci',
        'fecha_inicio',
        'fecha_final',
        'estado',
        'cliente_canje_id',

    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\User', 'cliente_id');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function ventaCanje()
    {
        return $this->belongsTo('App\Venta', 'venta_canje_id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacene', 'almacene_id');
    }

    public function almacenCanje()
    {
        return $this->belongsTo('App\Almacene', 'almacen_canje_id');
    }

    public function clienteCanje()
    {
        return $this->belongsTo('App\User', 'cliente_canje_id');
    }
}
