<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'user_id',
        'marca_id',
        'categoria_id',
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
        'estado',
        'borrado',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function marca()
    {
        return $this->belongsTo('App\Marca');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }

    public function caracteristica()
    {
        return $this->hasMany('App\caracteristica');
    }

    public function precio()
    {
        return $this->hasMany('App\Precio');
    }
}
