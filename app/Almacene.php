<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacene extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
    	'nombre',
    	'direccion',
    	'telefono',
        'estado',
        'deleted_at',
    ];

    public function usuarios()
    {
        return $this->hasMany('App\User');
    }
}
