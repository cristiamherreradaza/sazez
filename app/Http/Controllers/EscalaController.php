<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Escala;

class EscalaController extends Controller
{
    public function listado()
    {
        $escalas = Escala::where('deleted_at', NULL)
                        ->get();
        return view('escala.listado')->with(compact('escalas'));
    }

    public function guardar(Request $request)
    {
        $escala = new Escala();
        $escala->user_id = Auth::user()->id;
        $escala->nombre = $request->nombre_escala;
        $escala->save();
        return redirect('Escala/listado');
    }

    public function actualizar(Request $request)
    {
        $escala = Escala::find($request->id);
        $escala->user_id = Auth::user()->id;
        $escala->nombre = $request->nombre;
        $escala->save();
        return redirect('Escala/listado');
    }

    public function eliminar(Request $request)
    {
        $escala = Escala::find($request->id);
        $escala->delete();
        return redirect('Escala/listado');
    }
}
