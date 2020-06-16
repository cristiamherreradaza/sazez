<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tipo;

class TipoController extends Controller
{
    public function listado()
    {
        $tipos = Tipo::get();
        return view('tipo.listado')->with(compact('tipos'));
    }

    public function guardar(Request $request)
    {
        $tipo = new Tipo();
        $tipo->user_id = Auth::user()->id;
        $tipo->nombre = $request->nombre_tipo;
        $tipo->save();
        return redirect('Tipo/listado');
    }

    public function actualizar(Request $request)
    {
        $tipo = Tipo::find($request->id);
        $tipo->user_id = Auth::user()->id;
        $tipo->nombre = $request->nombre;
        $tipo->save();
        return redirect('Tipo/listado');
    }

    public function eliminar($id)
    {
        $tipo = Tipo::find($id);
        $tipo->delete();
        return redirect('Tipo/listado');
    }
}
