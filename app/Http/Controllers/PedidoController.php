<?php

namespace App\Http\Controllers;

use App\Pedido;
use DataTables;
use App\Almacene;
use App\Producto;
use App\PedidosProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function nuevo()
    {
        $almacenes = Almacene::get();
        return view('Pedido.nuevo')->with(compact('almacenes'));
    }   

    public function pedido_productos($id)
    {
        $pedido = Pedido::find($id);
        $almacenes = Almacene::get();
        return view('Pedido.nuevo')->with(compact('pedido', 'almacenes'));
    }

    public function ajax_listado_producto()
    {
        $productos = DB::table('productos')
            ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select(
                'productos.id',
                'productos.codigo',
                'productos.nombre as nombre',
                'productos.nombre_venta',
                'tipos.nombre as tipo',
                'marcas.nombre as marca',
                'productos.colores'
            );

        return Datatables::of($productos)
            ->addColumn('action', function ($productos) {
                return '<button onclick="edita_producto(' . $productos->id . ')" class="btn btn-warning"><i class="fas fa-edit"></i></button> <button onclick="asigna_materias(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
            })
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
        $pedido                          = new Pedido();
        $pedido->almacene_solicitante_id = Auth::user()->almacen_id;;
        $pedido->solicitante_id          = Auth::user()->id;
        $pedido->almacene_id             = $request->almacen_a_pedir;
        $pedido->fecha                   = $request->fecha_pedido;
        $pedido->save();
        $pedido_id = $pedido->id;

        //arraykeys guarda ids de prod
        $llaves = array_keys($request->item);
        foreach ($llaves as $key => $ll) 
        {
            $productosPedido              = new PedidosProducto();
            $productosPedido->pedido_id   = $pedido_id;
            $productosPedido->user_id     = Auth::user()->id;
            $productosPedido->producto_id = $ll;
            $productosPedido->cantidad    = $request->item[$ll];
            $productosPedido->save();
        }
        // dd($request->all());
        return redirect('Pedido/listado');
    }

    public function eliminar($id)
    {
        $pedido = Pedido::find($id);
        $pedido->delete();
        PedidosProducto::where('pedido_id', $id)->delete();
        return redirect('Pedido/listado');
    }


    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")->limit(8)->get();
        return view('pedido.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function listado()
    {
        // dd($productos);
        return view('pedido.listado');
    }

    public function ajax_listado()
    {
        // $lista_personal = Producto::all();
        $pedidos = DB::table('pedidos')
            ->leftJoin('almacenes', 'pedidos.almacene_solicitante_id', '=', 'almacenes.id')
            ->select(
                'pedidos.id',
                'pedidos.numero', 
                'almacenes.nombre', 
                'pedidos.solicitante_id', 
                'pedidos.fecha', 
                'pedidos.estado'
            );

         return Datatables::of($pedidos)
                ->addColumn('action', function ($pedidos) {
                    if ($pedidos->estado == 'Entregado') {
                        return '<button onclick="ver_pedido(' . $pedidos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
                    } else {
                        return '<button type="button" class="btn btn-warning" title="Editar pedido"  onclick="editar(' . $pedidos->id . ')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar pedido"  onclick="eliminar(' .  $pedidos->id . ')"><i class="fas fa-trash"></i></button>
                                <button type="button" class="btn btn-dark" title="Entregar pedido"  onclick="entrega(' .  $pedidos->id . ')"><i class="fas fa-reply"></i></button>
                                <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>
                                <button type="button" class="btn btn-secondary" title="Entregar pedido por Excel"  onclick="entrega_excel(' .  $pedidos->id . ')"><i class="fas fa-shipping-fast"></i></button>';
                    }
                    
                })
                ->make(true); 
        
    }

}
