<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
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
    ];

    public function asignatura()
    {
        return $this->belongsTo('App\Asignatura');
    }

    public function turno()
    {
        return $this->belongsTo('App\Turno');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
