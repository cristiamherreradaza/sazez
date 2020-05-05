<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
