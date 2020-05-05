<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
        'nombre',
        'estado',
        'deleted_at',
    ];

    public function producto()
    {
        return $this->hasOne('App\Producto');
    }
}
