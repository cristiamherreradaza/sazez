<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'almacene_id',
        'cliente_id',
        'venta_id',
        'numero_autorizacion',
        'numero_factura',
        'nit_cliente',
        'fecha_compra',
        'fecha_limite',
        'monto_compra',
        'clave',
        'codigo_control',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacene', 'almacene_id');
    }

    public function venta()
    {
        return $this->belongsTo('App\Venta');
    }

    public function cliente()
    {
        return $this->belongsTo('App\User', 'cliente_id');
    }
}
