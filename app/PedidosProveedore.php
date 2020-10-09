<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidosProveedore extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'almacene_id',
        'proveedore_id',
        'numero',
        'fecha',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function almacene()
    {
        return $this->belongsTo('App\Almacene');
    }
    
    public function proveedore()
    {
        return $this->belongsTo('App\Proveedore');
    }

}
