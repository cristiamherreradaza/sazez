<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Categoria;

class CategoriaController extends Controller
{
    public function listado()
    {
        $categorias = Categoria::where('deleted_at', NULL)
                        ->get();
        return view('categoria.listado')->with(compact('categorias'));
    }

    public function guardar(Request $request)
    {
        $categoria = new Categoria();
        $categoria->user_id = Auth::user()->id;
        $categoria->nombre = $request->nombre_categoria;
        $categoria->save();
        return redirect('Categoria/listado');
    }

    public function actualizar(Request $request)
    {
        $categoria = Categoria::find($request->id);
        $categoria->user_id = Auth::user()->id;
        $categoria->nombre = $request->nombre;
        $categoria->save();
        return redirect('Categoria/listado');
    }

    public function eliminar(Request $request)
    {
        $categoria = Categoria::find($request->id);
        $categoria->delete();
        return redirect('Categoria/listado');
    }
}
