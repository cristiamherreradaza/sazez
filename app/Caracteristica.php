<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $fillable = [
        'user_id',
        'producto_id',
        'descripcion',
        'estado',
        'deleted_at',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

}
