<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qr extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'producto_id',
        'numero',
        'estado',
        'deleted_at',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }

}
