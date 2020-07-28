<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'marca_id',
        'tipo_id',
        'codigo',
        'nombre',
        'nombre_venta',
        'tipo',
        'modelo',
        'precio_compra',
        'largo',
        'ancho',
        'alto',
        'peso',
        'colores',
        'descripcion',
        'url_referencia',
        'video',
        'dias_garantia',
        'estado',
        'deleted_at',
    ];

    // protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function marca()
    {
        return $this->belongsTo('App\Marca');
    }

    public function tipo()
    {
        return $this->belongsTo('App\Tipo');
    }

    public function caracteristica()
    {
        return $this->hasMany('App\caracteristica');
    }

    public function precio()
    {
        return $this->hasMany('App\Precio', 'producto_id');
    }

    public function categorias()
    {
        return $this->hasMany('App\CategoriasProducto');
    }

    public function imagenes()
    {
        return $this->hasMany('App\ImagenesProducto');
    }

    public function combos_productos()
    {
        return $this->hasMany('App\CombosProducto');
    }

    public function pedidos_productos()
    {
        return $this->hasMany('App\PedidosProducto');
    }

    public function cotizaciones_productos()
    {
        return $this->hasMany('App\CotizacionesProducto');
    }

    public function ventas_productos()
    {
        return $this->hasMany('App\VentasProducto');
    }

    public function cupones()
    {
        return $this->hasMany('App\Cupone');
    }
}
