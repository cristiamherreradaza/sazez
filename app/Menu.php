<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'direccion',
        'descripcion',
        'icono',
        'padre',
        'orden',
        'estado',
        'deleted_at',
    ];

    public function menususers()
    {
        return $this->hasMany('App\MenusUser');
    }

    public function menusperfiles()
    {
        return $this->hasMany('App\MenusPerfile');
    }
}
