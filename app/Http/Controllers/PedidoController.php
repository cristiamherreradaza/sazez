<?php

namespace App\Http\Controllers;

use DataTables;
use App\Almacene;
use App\Producto;
use App\Pedido;
use App\PedidosProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function nuevo()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('pedido.nuevo')->with(compact('almacenes'));
    }   

    public function pedido_productos($id)
    {
        $pedido = Pedido::find($id);
        $almacenes = Almacene::get();
        return view('pedido.nuevo')->with(compact('pedido', 'almacenes'));
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
                return '<button onclick="asigna_materias(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
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
        if($request->item){
            $ultimo_numero = Pedido::max('numero');     // Sacamos el ultimo numero
            if($ultimo_numero){                         // Si Existen valores
                $numero = $ultimo_numero+1;             // Sumar 1 al ultimo numero
            }else{                                      // Si no existen valores en la tabla
                $numero = 1;                            // Creara el primero
            }
    
            $pedido                          = new Pedido();
            $pedido->almacene_solicitante_id = Auth::user()->almacen_id;
            $pedido->solicitante_id          = Auth::user()->id;
            $pedido->almacene_id             = $request->almacen_a_pedir;
            $pedido->numero                  = $numero;
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
        }
        return redirect('Pedido/listado');
    }

    public function editar($id)
    {
        $pedido = Pedido::find($id);
        $almacenes = Almacene::get();
        return view('pedido.editar')->with(compact('pedido', 'almacenes'));
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
        $almacen_id = $request->almacen;
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('pedido.ajaxBuscaProducto')->with(compact('productos', 'almacen_id'));
    }

    public function listado()
    {
        // dd($productos);
        return view('pedido.listado');
    }

    public function ajax_listado()
    {
        $pedidos = DB::table('pedidos')
                        ->whereNull('pedidos.deleted_at')
                        ->leftJoin('almacenes', 'pedidos.almacene_solicitante_id', '=', 'almacenes.id')
                        ->leftJoin('almacenes as origen', 'pedidos.almacene_id', '=', 'origen.id')
                        ->leftJoin('users', 'pedidos.solicitante_id', '=', 'users.id')
                        ->orderBy('pedidos.id', 'desc')
                        ->select(
                            'pedidos.id',
                            'pedidos.numero as numero_pedido',
                            'origen.nombre as almacen_origen',
                            'almacenes.nombre as almacen_destino',
                            'users.name as nombre_usuario', 
                            'pedidos.fecha as fecha', 
                            'pedidos.estado as estado',
                            'pedidos.almacene_solicitante_id as almacen_origen_id',
                            'pedidos.almacene_id as almacen_destino_id'
                        );
        if(Auth::user()->perfil_id != 1){
            //$pedidos->where('pedidos.almacene_id', Auth::user()->almacen->id);
            $pedidos->where(function ($query) {
                $query->where('pedidos.almacene_id', Auth::user()->almacen->id)
                    ->orWhere('pedidos.almacene_solicitante_id', Auth::user()->almacen->id);
            });
        }
        return Datatables::of($pedidos)->addColumn('action', function ($pedidos) {
                    // Si es el usuario tiene perfil de administrador, muestra todo 4 botones
                    if(Auth::user()->perfil_id == 1)
                    {
                        if($pedidos->estado == 'Entregado'){
                            return '<button type="button" class="btn btn-info" title="Ver pedido" onclick="ver_pedido(' . $pedidos->id . ')"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>';
                        }else{
                            return '<button type="button" class="btn btn-info" title="Ver pedido" onclick="ver_pedido(' . $pedidos->id . ')"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-dark" title="Entregar pedido" onclick="entrega(' .  $pedidos->id . ')"><i class="fas fa-reply"></i></button>
                                    <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>
                                    <button type="button" class="btn btn-secondary" title="Entregar pedido por Excel"  onclick="entrega_excel(' .  $pedidos->id . ')"><i class="fas fa-shipping-fast"></i></button>';
                        }
                    }
                    // Si es usuario no tiene perfil de administrador y pertenece al almacen origen mostrar ver y descargar
                    elseif($pedidos->almacen_origen_id == Auth::user()->almacen->id)
                    {
                        return '<button type="button" class="btn btn-info" title="Ver pedido" onclick="ver_pedido(' . $pedidos->id . ')"><i class="fas fa-eye"></i></button>
                                <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>';
                    }
                    // Si el usuario no tiene perfil de administrador y pertenece al almacen destino mostrar todo 4 botones
                    elseif($pedidos->almacen_destino_id == Auth::user()->almacen->id)
                    {
                        if($pedidos->estado == 'Entregado'){
                            return '<button type="button" class="btn btn-info" title="Ver pedido" onclick="ver_pedido(' . $pedidos->id . ')"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>';
                        }else{
                            return '<button type="button" class="btn btn-info" title="Ver pedido" onclick="ver_pedido(' . $pedidos->id . ')"><i class="fas fa-eye"></i></button>
                                    <button type="button" class="btn btn-dark" title="Entregar pedido" onclick="entrega(' .  $pedidos->id . ')"><i class="fas fa-reply"></i></button>
                                    <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>
                                    <button type="button" class="btn btn-secondary" title="Entregar pedido por Excel"  onclick="entrega_excel(' .  $pedidos->id . ')"><i class="fas fa-shipping-fast"></i></button>';
                        }
                    }
                })
                ->make(true); 
    }

    public function adicionaProducto(Request $request)
    {
        $pedido = Pedido::find($request->pedido_id);
        
        if($request->producto_id){
            // Buscaremos si ya existe ese producto en ese pedido
            $producto_lista = PedidosProducto::where('pedido_id', $request->pedido_id)
                                            ->where('producto_id', $request->producto_id)
                                            ->first();
            //dd($producto_lista);
            if(!$producto_lista){    // En caso de no encontrarlo se creara los registros a ese pedido/producto
                $producto = new PedidosProducto();
                $producto->user_id = Auth::user()->id;
                $producto->pedido_id = $pedido->id;
                $producto->producto_id = $request->producto_id;
                $producto->cantidad = $request->producto_cantidad;
                $producto->save();
            }
        }
        return redirect("Entrega/ver_pedido/$pedido->numero");
    }

    public function ajaxBuscaProductos(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        $almacen_id = $request->almacen_solicitado;
        return view('envio.ajaxBuscaProducto')->with(compact('productos', 'almacen_id'));
    }

    public function eliminaProducto($id)
    {
        $producto = PedidosProducto::find($id);
        $pedido = Pedido::find($producto->pedido_id);
        $producto->delete();
        return redirect("Entrega/ver_pedido/$pedido->numero");
    }

    public function eliminaPedido($id)
    {
        $pedido = Pedido::find($id);
        $productos_pedido = PedidosProducto::where('pedido_id', $pedido->id)
                                            ->get();
        foreach($productos_pedido as $producto){
            $producto->delete();
        }
        $pedido->delete();
        return redirect('Pedido/listado');
    }
}
