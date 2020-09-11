<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

protected $fillable = [
        'almacene_id',
        'nombre',
        'direccion',
        'actividad',
        'leyenda_consumidor',
        'telefono',
        'fax',
        'email',
        'telefono_fijo',
        'nit',
        'deleted_at',
    ];

    public function almacen()
    {
        return $this->belongsTo('App\Almacene', 'almacene_id');
    }
}
