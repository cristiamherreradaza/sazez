<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cupone extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
        'producto_id',
        'combo_id',
    	'cliente_id',
    	'almacene_id',
        'descuento',
        'monto_total',
        'masivo',
        'codigo',
        'estado',
        'fecha_inicio',
        'fecha_final',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'cliente_id');
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
}
