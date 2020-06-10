<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuponesCobrado extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'cupone_id',
    	'cobrador_id',
    	'almacene_id',
        'fecha',
        'estado',
        'deleted_at',
    ];
}
