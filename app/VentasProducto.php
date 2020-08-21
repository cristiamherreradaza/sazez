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
        'cupon_id',
        'escala_id',
        'venta_id',
        'precio_venta',
        'cantidad',
        'fecha',
        'fecha_garantia',
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

    public function escala()
    {
        return $this->belongsTo('App\Escala');
    }
    
    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function cupon()
    {
        return $this->belongsTo('App\Cupone', 'cupon_id');
    }
}
