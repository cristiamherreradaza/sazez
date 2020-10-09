<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductosPedidoProveedore extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'pedidos_proveedore_id',
        'producto_id',
        'caja',
        'cantidad',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function pedidosproveedore()
    {
        return $this->belongsTo('App\PedidosProveedore');
    }
    
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

}
