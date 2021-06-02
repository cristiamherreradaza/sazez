<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuponesCliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'cupone_id',
        'producto_id',
        'combo_id',
    	'cliente_id',
    	'almacene_id',
        'fecha_creacion',
        'fecha_cobro',
        'descuento',
        'monto_total',
        'codigo',
        'estado',
        'fecha_inicio',
        'fecha_final',
        'deleted_at',
    ];

    public function cupon()
    {
        return $this->belongsTo('App\Cupone', 'cupone_id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacene', 'almacene_id');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function combo()
    {
        return $this->belongsTo('App\Combo', 'combo_id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\User', 'cliente_id');
    }
}
