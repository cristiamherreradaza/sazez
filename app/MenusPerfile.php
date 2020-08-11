<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenusPerfile extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'perfil_id',
        'menu_id',
        'estado',
        'deleted_at',
    ];

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id');
    }

    public function perfil()
    {
        return $this->belongsTo('App\Perfile', 'perfil_id');
    }
}
