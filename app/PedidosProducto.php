<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidosProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'pedido_id',
        'producto_id',
        'cantidad',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function pedido()
    {
        return $this->belongsTo('App\Pedido');
    }
    
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }
}
