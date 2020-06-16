<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Precio extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
    	'producto_id',
    	'escala_id',
        'precio',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function escala()
    {
        return $this->belongsTo('App\Escala');
    }

}
