<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CombosProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'combo_id',
        'producto_id',
        'precio',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function combo()
    {
        return $this->belongsTo('App\Combo');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto', 'producto_id');
    }

    public function precios()
    {
        return $this->hasManyThrough('App\Precio', 'App\Producto');
    }
}
