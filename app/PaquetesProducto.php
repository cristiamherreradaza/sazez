<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaquetesProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'paquete_id',
        'producto_id',
        'precio_venta',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function paquete()
    {
        return $this->belongsTo('App\Paquete');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }
}
