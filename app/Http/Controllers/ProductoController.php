<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function nuevo()
    {
    	$marcas = Marca::where('borrado', NULL)->get();
    	$turnos = Turno::where('borrado', NULL)->get();
    }
}
