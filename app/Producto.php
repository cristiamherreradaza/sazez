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
        return $this->hasMany('App\Precio');
    }
}
