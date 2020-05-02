<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = [
    	'user_id',
        'nombre',
        'estado',
        'borrado',
    ];

    public function producto()
    {
        return $this->hasOne('App\Producto');
    }
}
