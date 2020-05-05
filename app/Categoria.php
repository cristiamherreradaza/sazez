<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'estado',
        'deleted_at',
    ];

    public function producto()
    {
        return $this->hasOne('App\Producto');
    }
}
