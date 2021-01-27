<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meta extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'almacene_id',
        'mes',
        'meta',
        'alcance',
        'gestion',
        'fecha',
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
}
