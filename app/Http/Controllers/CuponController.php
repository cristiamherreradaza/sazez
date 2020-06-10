<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Almacene;
use App\Cupone;
use App\Producto;
use App\User;

class CuponController extends Controller
{
    public function listado()
    {
        $cupones = Cupone::get();
        $almacenes = Almacene::get();
        $clientes = User::get();
        return view('cupon.listado')->with(compact('almacenes', 'cupones', 'clientes'));
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('cupon.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function guardar(Request $request)
    {
        //dd($request->tienda);
        $cupon = new Cupone();
        $cupon->user_id = Auth::user()->id;
        $cupon->producto_id = $request->producto_id;
        $cupon->cliente_id = $request->cliente;
        $cupon->almacene_id = $request->tienda;
        $cupon->descuento = $request->producto_descuento;
        $cupon->monto_total = $request->producto_total;
        //generar codigo aleatorio unico
        $cupon->codigo = 'AXAA-BBBB-CCCC';
        $cupon->fecha_inicio = $request->fecha_inicio;
        $cupon->fecha_final = $request->fecha_fin;
        //$cupon->estado = 
        $cupon->save();
        return redirect('Cupon/listado');
    }
}
