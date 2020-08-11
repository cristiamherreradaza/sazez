<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'almacen_id',
        'perfil_id',
        'name',
        'ci',
        'rol',
        'email',
        'password',
        'celulares',
        'nit',
        'razon_social',
        'provider',
        'provider_id',
        'image',
        'estado',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function almacen()
    {
        return $this->belongsTo('App\Almacene', 'almacen_id');
    }

    public function perfil()
    {
        return $this->belongsTo('App\Perfile', 'perfil_id');
}

    public function combos()
    {
        return $this->hasMany('App\Combo');
    }

    public function grupos()
    {
        return $this->hasMany('App\Grupo');
    }

    public function combos_productos()
    {
        return $this->hasMany('App\CombosProducto');
    }

    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }

    public function pedidos_productos()
    {
        return $this->hasMany('App\PedidosProducto');
    }

    //comprobar para dos casos
    public function cotizaciones()
    {
        return $this->hasMany('App\Cotizacione');
    }

    public function cotizaciones_productos()
    {
        return $this->hasMany('App\CotizacionesProducto');
    }

    //comprobar para dos casos
    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    public function ventas_productos()
    {
        return $this->hasMany('App\VentasProducto');
    }

    public function cupones()
    {
        return $this->hasMany('App\Cupone');
    }

    public function menususers()
    {
        return $this->hasMany('App\MenusUser');
    }

    
}
