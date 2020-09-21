<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'user_id',
    	'nombre',
        'estado',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
