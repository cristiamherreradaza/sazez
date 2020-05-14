<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
    	'nombre',
    	'fecha_inicio',
    	'fecha_final',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function combos_productos()
    {
        return $this->hasMany('App\CombosProducto');
    }

    public function cotizaciones()
    {
        return $this->hasMany('App\Cotizaciones');
    }

    public function cotizaciones_productos()
    {
        return $this->hasMany('App\CotizacionesProducto');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    public function ventas_productos()
    {
        return $this->hasMany('App\VentasProducto');
    }
}
