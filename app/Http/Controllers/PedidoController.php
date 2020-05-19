<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Almacene;
use App\Pedido;
use App\PedidosProducto;
use App\Producto;

class PedidoController extends Controller
{
    public function nuevo()
    {
        $length = 6;
        //$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $charset = "0123456789";
        $numero="";
        for($i=0;$i<$length;$i++){
            $rand = rand() % strlen($charset);
            $numero .= substr($charset, $rand, 1);
        }

        $pedido = new Pedido();
        $pedido->almacene_solicitante_id = Auth::user()->almacen_id;
        $pedido->solicitante_id = Auth::user()->id;
        $pedido->almacene_id = 1;
        $pedido->numero = $numero;
        $pedido->fecha = date('Y-m-d');
        $pedido->save();

        //$pedido = Pedido::where('numero', 12)->get();
        //dd($pedido->id);

        return redirect('Pedido/pedido_productos/'.$pedido->id);
    }   

    public function pedido_productos($id)
    {
        $pedido = Pedido::find($id);
        $almacenes = Almacene::get();
        return view('Pedido.nuevo')->with(compact('pedido', 'almacenes'));
    }

    public function ajax_listado_producto()
    {
        $lista_productos = Producto::select('id', 'nombre', 'nombre_venta', 'marca_id');
        return Datatables::of($lista_productos)
            ->addColumn('action', function ($lista_productos) {
                return '<button onclick="adicionar_producto_pedido('.$lista_productos->id.')" class="btn btn-info"><i class="fas fa-plus"></i></a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
    }

    public function agregar_pedido_producto(Request $request)
    {
        $pedidos_productos = new PedidosProducto();
        $pedidos_productos->user_id = Auth::user()->id;
        $pedidos_productos->pedido_id = $request->pedido_id;
        $pedidos_productos->producto_id = $request->producto_id;
        $pedidos_productos->cantidad = 0;
        $pedidos_productos->save();
    }

    public function lista_pedido_productos($id)
    {
        $productos_pedido = PedidosProducto::where('pedido_id', $id)->get();
        return view('pedido.lista_pedido_productos')->with(compact('productos_pedido'));
    }

    public function elimina_producto($pedido_id, $producto_id)
    {
        $pedidos_productos = PedidosProducto::where('pedido_id', $pedido_id)
                                            ->where('producto_id', $producto_id)
                                            ->first();
        $pedidos_productos->delete();
    }

    public function actualiza_cantidad(Request $request)
    {
        $pedido_producto = PedidosProducto::find($request->id);
        $pedido_producto->cantidad = $request->cantidad;
        $pedido_producto->save();
    }

    public function guarda(Request $request)
    {
        $pedido = Pedido::find($request->id_pedido);
        $pedido->almacene_solicitante_id = Auth::user()->almacen_id;
        $pedido->solicitante_id = Auth::user()->id;
        $pedido->almacene_id = $request->almacen_a_pedir;
        $pedido->numero = $request->numero_pedido;
        $pedido->fecha = $request->fecha;
        $pedido->save();
        return redirect('Pedido/listado');
    }

    public function eliminar($id)
    {
        $pedido = Pedido::find($id);
        $pedido->delete();
        PedidosProducto::where('pedido_id', $id)->delete();
        return redirect('Pedido/listado');
    }

    public function listado()
    {
        $pedidos = Pedido::get();
        $almacenes = Almacene::get();
        return view('pedido.listado')->with(compact('pedidos', 'almacenes'));
    }

}
