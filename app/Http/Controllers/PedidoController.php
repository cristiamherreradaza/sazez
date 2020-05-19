<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Producto;
use App\Pedido;

class PedidoController extends Controller
{
    public function nuevo()
    {
        $pedido = new Pedido();
        $pedido->almacene_id = Auth::user()->almacen_id;
        $pedido->encargado_id = Auth::user()->id;
        //$pedido->numero = 12;
        $pedido->fecha = date('Y-m-d');
        $pedido->save();

        //$pedido = Pedido::where('numero', 12)->get();
        //dd($pedido->id);

        return redirect('Pedido/pedido_productos/'.$pedido->id);
    }   

    public function pedido_productos($id)
    {
        $pedido = Pedido::find($id);
        return view('Pedido.nuevo')->with(compact('pedido'));
    }
}
