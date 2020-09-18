<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Almacene;

class AlmacenController extends Controller
{
    public function listado()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('almacen.listado')->with(compact('almacenes'));                            
    }

    public function guardar(Request $request)
    {
        //dd($request->all());
        $almacen = new Almacene();
        $almacen->user_id = Auth::user()->id;
        $almacen->nombre = $request->nombre_almacen;
        $almacen->direccion = $request->direccion_almacen;
        $almacen->telefonos = $request->telefonos_almacen;
        $almacen->mayorista = $request->mayorista;
        $almacen->save();
        return redirect('Almacen/listado');
    }

    public function actualizar(Request $request)
    {
        $almacen = Almacene::find($request->id);
        $almacen->user_id = Auth::user()->id;
        $almacen->nombre = $request->nombre;
        $almacen->direccion = $request->direccion;
        $almacen->telefonos = $request->telefonos;
        $almacen->mayorista = $request->mayorista;
        $almacen->save();
        return redirect('Almacen/listado');
    }

    public function eliminar(Request $request)
    {
        $almacen = Almacene::find($request->id);
        $almacen->delete();
        return redirect('Almacen/listado');
    }
}
