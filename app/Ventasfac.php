<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ventasfac extends Model
{
    use SoftDeletes;
    protected $table = 'ventasfac';

    protected $fillable = [
        'user_id',
        'almacen_id',
        'factura_id',
        'nombre',
        'nit',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'fecha',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacene');
    }

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }

}
