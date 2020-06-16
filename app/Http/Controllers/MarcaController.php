<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Marca;

class MarcaController extends Controller
{
    public function listado()
    {
        $marcas = Marca::get();
        return view('marca.listado')->with(compact('marcas'));
    }

    public function guardar(Request $request)
    {
        $marca = new Marca();
        $marca->user_id = Auth::user()->id;
        $marca->nombre = $request->nombre_marca;
        $marca->save();
        return redirect('Marca/listado');
    }

    public function actualizar(Request $request)
    {
        $marca = Marca::find($request->id);
        $marca->user_id = Auth::user()->id;
        $marca->nombre = $request->nombre;
        $marca->save();
        return redirect('Marca/listado');
    }

    public function eliminar(Request $request)
    {
        $marca = Marca::find($request->id);
        $marca->delete();
        return redirect('Marca/listado');
    }
}
