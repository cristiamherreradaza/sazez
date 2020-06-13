<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacene extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
    	'nombre',
    	'direccion',
    	'telefono',
        'estado',
        'deleted_at',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }

    public function cotizaciones()
    {
        return $this->hasMany('App\Cotizaciones');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    public function cupones()
    {
        return $this->hasMany('App\Cupone');
    }
}
