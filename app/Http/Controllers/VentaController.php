<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function nuevo()
    {
        $almacen_id = Auth::user()->almacen_id;
        return view('venta.nuevo')->with(compact('almacen_id'));
    }
}