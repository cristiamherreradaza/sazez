<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlcancesUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'alcance_max',
        'mes',
        'mes_literal',
        'anio',
        'total_vendido',
        'estado',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
