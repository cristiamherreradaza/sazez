<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacione extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'almacene_id',
        'combo_id',
        'cliente_id',
        'numero',
        'fecha',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function almacene()
    {
        return $this->belongsTo('App\Almacene');
    }
    
    public function combo()
    {
        return $this->belongsTo('App\Combo');
    }
    
    public function cliente()
    {
        return $this->belongsTo('App\User', 'cliente_id');
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
