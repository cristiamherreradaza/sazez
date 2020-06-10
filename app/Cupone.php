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
    	'cliente_id',
    	'almacene_id',
        'descuento',
        'monto_total',
        'codigo',
        'estado',
        'fecha_inicio',
        'fecha_final',
        'deleted_at',
    ];
}
