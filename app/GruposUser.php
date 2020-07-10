<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GruposUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'grupo_id',
        'estado',
    ];

    public function grupo()
    {
        return $this->belongsTo('App\Grupo');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
