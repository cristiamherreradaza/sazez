<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\Almacene;

class EnvioController extends Controller
{

    public function nuevo()
    {
        // $pedido = Pedido::find();
        $almacenes = Almacene::get();
        return view('envio.nuevo')->with(compact('almacenes'));
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('pedido.ajaxBuscaProducto')->with(compact('productos'));
    }
}
