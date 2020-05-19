<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'almacene_id',
        'cotizacione_id',
        'cliente_id',
        'combo_id',
        'numero',
        'fecha',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacene');
    }

    public function cotizacione()
    {
        return $this->belongsTo('App\Cotizacione');
    }

    public function cliente()
    {
        return $this->belongsTo('App\User', 'cliente_id');
    }

    public function combo()
    {
        return $this->belongsTo('App\Combo');
    }

    public function ventas_productos()
    {
        return $this->hasMany('App\VentasProducto');
    }
}
