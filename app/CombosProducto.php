<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CombosProducto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'combo_id',
        'producto_id',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function combo()
    {
        return $this->belongsTo('App\Combo');
    }

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }
}
