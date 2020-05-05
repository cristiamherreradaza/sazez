<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
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
