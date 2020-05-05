<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caracteristica extends Model
{
    use SoftDeletes;

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
