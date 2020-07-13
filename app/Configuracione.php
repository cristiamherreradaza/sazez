<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuracione extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'descripcion',
        'valor',
        'estado',
        'deleted_at',
    ];

}
