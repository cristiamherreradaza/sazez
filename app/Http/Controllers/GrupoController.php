<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Grupo;
use App\GruposUser;

class GrupoController extends Controller
{
    public function listado()
    {
        $grupos = Grupo::get();
        return view('grupo.listado')->with(compact('grupos'));
    }

    public function guardar(Request $request)
    {
        $grupo = new Grupo();
        $grupo->user_id = Auth::user()->id;
        $grupo->nombre = $request->nombre_grupo;
        $grupo->descripcion = $request->descripcion_grupo;
        $grupo->save();
        return redirect('Grupo/listado');
    }

    public function actualizar(Request $request)
    {
        $grupo = Grupo::find($request->id);
        $grupo->user_id = Auth::user()->id;
        $grupo->nombre = $request->nombre;
        $grupo->descripcion = $request->descripcion;
        $grupo->save();
        return redirect('Grupo/listado');
    }

    public function eliminar(Request $request)
    {
        $grupo = Grupo::find($request->id);
        $usuarios = GruposUser::where('grupo_id', $grupo->id)->get();
        foreach($usuarios as $usuario){
            $usuario->delete();
        }
        $grupo->delete();
        return redirect('Grupo/listado');
    }
}
