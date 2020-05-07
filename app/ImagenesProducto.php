<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImagenesProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'producto_id',
        'imagen',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }
}
