<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Producto;
use App\ImagenesProducto;

class TiendaController extends Controller
{
    public function inicio()
    {
        $categorias = Categoria::get();
        $productos = Producto::take(8)->get();
        //dd($productos);
        return view('tienda.inicio')->with(compact('categorias', 'productos'));
    }

    public function ver($id)
    {
        $producto = Producto::find($id);
        
        //dd($producto);
        return view('tienda.ver')->with(compact('producto'));
    }
}
