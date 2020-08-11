<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenusUser extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'menu_id',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id');
    }
}
