<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Movimiento;
use App\Almacene;
use App\Producto;

class MovimientoController extends Controller
{
    public function ingreso()
    {
        $almacenes = Almacene::get();
        return view('movimiento.ingreso')->with(compact('almacenes'));
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('movimiento.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function guarda(Request $request)
    {
        $llaves = array_keys($request->precio);
        foreach ($llaves as $key => $ll) 
        {
            // Creación de Movimiento
            $ingreso = new Movimiento();
            $ingreso->user_id = Auth::user()->id;
            $ingreso->producto_id = $ll;
            $ingreso->almacene_id = $request->almacen;
            $ingreso->ingreso = $request->subtotal[$ll];
            $ingreso->save();
        }
        return redirect('Producto/listado');
    }
}
