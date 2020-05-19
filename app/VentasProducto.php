<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VentasProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'cotizacione_id',
        'producto_id',
        'combo_id',
        'venta_id',
        'precio_venta',
        'cantidad',
        'fecha',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cotizacione()
    {
        return $this->belongsTo('App\Cotizacione');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function combo()
    {
        return $this->belongsTo('App\Combo');
    }
    
    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }
}
