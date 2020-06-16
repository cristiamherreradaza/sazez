<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriasProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'categoria_id',
        'producto_id',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
}
