<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Almacene;

class AlmacenController extends Controller
{
    public function listado()
    {
        $sw=0;
        return view('almacen.listado')->with(compact('sw'));
    }
}
