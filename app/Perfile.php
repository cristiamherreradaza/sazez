<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfile extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'deleted_at',
    ];

    public function menusperfiles()
    {
        return $this->hasMany('App\MenusPerfile');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
