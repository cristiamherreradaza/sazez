<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Pedido;
use App\Almacene;
use App\Producto;
use App\Movimiento;
use Illuminate\Support\Facades\Auth;
use DB;

class EnvioController extends Controller
{

    public function nuevo()
    {
        // $pedido = Pedido::find();
        $almacenes = Almacene::whereNull('estado')->get();
        return view('envio.nuevo')->with(compact('almacenes'));
    }

    public function ajaxBuscaProductos(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        
        if($request->almacen_origen){
            $almacen_id = $request->almacen_origen;
        }else{
            $almacen_id = Auth::user()->almacen_id;
        }
        //dd($almacen_id);
        return view('envio.ajaxBuscaProductos')->with(compact('productos', 'almacen_id'));
    }

    // Funcion que guarda y procesa el envio de productos desde la interfaz Envio/nuevo
    public function guarda(Request $request)
    {
        if($request->item){
            //Preguntaremos si almacen_origen es null
            if($request->almacen_origen){
                $almacen_origen = $request->almacen_origen;
            }else{
                $almacen_origen = Auth::user()->almacen_id;
            }
            
            //arraykeys guarda ids de prod
            $hoy = date("Y-m-d H:i:s");
            $num = DB::select("SELECT MAX(numero) as nro
                                    FROM movimientos");
            if (!empty($num)) {
                $numero = $num[0]->nro + 1;
            } else {
                $numero = 1;
            }

            $llaves = array_keys($request->item);
            foreach ($llaves as $key => $ll) 
            {
                // Sacamos el stock existente en almacen X del producto X
                $ingreso = Movimiento::where('producto_id', $ll)
                                    ->where('almacene_id', $almacen_origen)
                                    ->where('ingreso', '>', 0)
                                    ->sum('ingreso');
                $salida = Movimiento::where('producto_id', $ll)
                                    ->where('almacene_id', $almacen_origen)
                                    ->where('salida', '>', 0)
                                    ->sum('salida');
                $cantidad_disponible = $ingreso - $salida;
                // Si la cantidad solicitada, no supera a la existente en almacen X del producto X 
                if($cantidad_disponible >= $request->item[$ll]){
                    // Buscamos al producto
                    $item = Producto::find($ll);
                    //AQUI SACAMOS EL MATERIAL SOLICITADO DEL ALMACEN ORIGEN
                    $salida = new Movimiento();
                    $salida->user_id = Auth::user()->id;
                    $salida->producto_id = $ll;
                    $salida->tipo_id = $item->tipo_id;
                    $salida->almacene_id = $almacen_origen;
                    $salida->salida = $request->item[$ll];
                    $salida->fecha = $hoy;
                    $salida->numero = $numero;
                    $salida->estado = 'Envio';
                    $salida->save();

                    //AQUI INGRESAMOS EL MATERIAL AL ALMACEN QUE LO SOLICITO
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $ll;
                    $ingreso->tipo_id = $item->tipo_id;
                    $ingreso->almacen_origen_id = $almacen_origen;
                    $ingreso->almacene_id = $request->almacen_a_pedir;
                    $ingreso->ingreso = $request->item[$ll];
                    $ingreso->fecha = $hoy;
                    $ingreso->numero = $numero;
                    $ingreso->estado = 'Envio';
                    $ingreso->save();
                }
            }
        }
        return redirect('Envio/ver_pedido/'.$numero);
    }

    public function eliminar($id)
    {
        $pedido = Pedido::find($id);
        $pedido->delete();
        PedidosProducto::where('pedido_id', $id)->delete();
        return redirect('Envio/listado');
    }

    public function listado()
    {
        // dd($productos);
        return view('envio.listado');
    }

    public function ajax_listados()
    {        
        $productos = Movimiento::where('movimientos.estado', '=', 'Envio')
                ->where('movimientos.ingreso', '>', 0)
                ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                ->groupBy('movimientos.numero')
                ->select(
                    'movimientos.numero', 
                    'almacenes.nombre', 
                    'users.name', 
                    'movimientos.fecha', 
                    'movimientos.estado'
                )
                ->orderBy('movimientos.id', 'desc');
        if(Auth::user()->perfil_id != 1){
            //$pedidos->where('pedidos.almacene_id', Auth::user()->almacen->id);
            $productos->where(function ($query) {
                $query->where('movimientos.almacene_id', Auth::user()->almacen->id)
                    ->orWhere('movimientos.almacen_origen_id', Auth::user()->almacen->id);
            });
        }
        return Datatables::of($productos)
                ->addColumn('action', function ($productos) {
                    return '<button onclick="ver_pedido(' . $productos->numero . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
                })
                ->make(true); 
    }

    public function adicionaProducto(Request $request)
    {
        if($request->producto_id){
            // Buscaremos si ya existe ese producto en ese envio
            $producto_lista = Movimiento::where('numero', $request->numero_pedido)
                                        ->where('producto_id', $request->producto_id)
                                        ->where('estado', 'Envio')
                                        ->first();
            if(!$producto_lista){    // En caso de no encontrarlo, verificamos que tenga suficiente stock en el almacen que enviara
                // Sacamos el stock existente en almacen X del producto X
                $ingreso = Movimiento::where('producto_id', $request->producto_id)
                                    ->where('almacene_id', $request->almacen_origen)
                                    ->where('ingreso', '>', 0)
                                    ->sum('ingreso');
                $salida = Movimiento::where('producto_id', $request->producto_id)
                                    ->where('almacene_id', $request->almacen_origen)
                                    ->where('salida', '>', 0)
                                    ->sum('salida');
                $cantidad_disponible = $ingreso - $salida;
                // Si la cantidad solicitada, no supera a la existente en almacen X del producto X 
                if($cantidad_disponible >= $request->producto_cantidad){
                    // Buscamos al producto
                    $item = Producto::find($request->producto_id);
                    //AQUI SACAMOS EL MATERIAL SOLICITADO DEL ALMACEN ORIGEN
                    $salida = new Movimiento();
                    $salida->user_id = Auth::user()->id;
                    $salida->producto_id = $request->producto_id;
                    $salida->tipo_id = $item->tipo_id;
                    $salida->almacene_id = $request->almacen_origen;
                    $salida->salida = $request->producto_cantidad;
                    $salida->fecha = date('Y-m-d H:i:s');
                    $salida->numero = $request->numero_pedido;
                    $salida->estado = 'Envio';
                    $salida->save();

                    //AQUI INGRESAMOS EL MATERIAL AL ALMACEN QUE LO SOLICITO
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $request->producto_id;
                    $ingreso->tipo_id = $item->tipo_id;
                    $ingreso->almacen_origen_id = $request->almacen_origen;
                    $ingreso->almacene_id = $request->almacen_destino;
                    $ingreso->ingreso = $request->producto_cantidad;
                    $ingreso->fecha = date('Y-m-d H:i:s');
                    $ingreso->numero = $request->numero_pedido;
                    $ingreso->estado = 'Envio';
                    $ingreso->save();
                }
            }
        }
        return redirect("Envio/ver_pedido/$request->numero_pedido");
    }

    public function ver_pedido($id)
    {
        // $datos = DB::table('movimientos')
        //         ->where('movimientos.numero', '=', $id)
        //         ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
        //         ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
        //         ->distinct()->select('movimientos.numero', 'almacenes.nombre', 'users.name', 'movimientos.fecha')
        //         ->get();
        $datos = Movimiento::where('numero', $id)
                            ->where('ingreso', '>', 0)
                            ->first();
        //dd($datos);
        // dd($datos->almacen_origen->nombre);
        // $productos = Movimiento::where('movimientos.numero', '=', $id)
        //         ->where('movimientos.ingreso', '>', 0)
        //         ->join('productos', 'movimientos.producto_id', '=', 'productos.id')
        //         ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
        //         ->join('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //         ->select('movimientos.*', 'productos.codigo', 'productos.nombre', 'marcas.nombre as nombre_marca', 'tipos.nombre as nombre_tipo', 'productos.modelo', 'productos.colores')
        //         ->get();
        $productos = Movimiento::where('numero', $id)
                                ->where('ingreso', '>', 0)
                                ->get();
        return view('envio.ver_pedido')->with(compact('datos', 'productos'));
    }

    public function eliminaProducto($id)
    {
        $datosMovimiento = Movimiento::find($id);
        $id_producto = $datosMovimiento->producto_id;
        $numero_envio = $datosMovimiento->numero;
        $registros = Movimiento::where('producto_id', $id_producto)
                                ->where('numero', $numero_envio)
                                ->where('estado', 'Envio')
                                ->get();
        foreach($registros as $registro){
            $registro->delete();
        }
        return redirect("Envio/ver_pedido/$numero_envio");
    }

    public function eliminaEnvio($id)
    {
        $registros_envio = Movimiento::where('estado', 'Envio')
                                    ->where('numero', $id)
                                    ->get();
        foreach($registros_envio as $registro){
            $registro->delete();
        }
        return redirect('Envio/listado');
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        $almacen_id = $request->almacen_origen;
        return view('envio.ajaxBuscaProducto')->with(compact('productos', 'almacen_id'));
    }

    public function vista_previa_envio($id)
    {
        $productos_envio = Movimiento::where('estado', 'Envio')
                                    ->where('numero', $id)
                                    ->where('ingreso', '>', 0)
                                    ->get();
        $cantidad_producto = Movimiento::where('estado', 'Envio')
                                        ->where('numero', $id)
                                        ->where('ingreso', '>', 0)
                                        ->count();
        $detalle = Movimiento::where('estado', 'Envio')
                            ->where('numero', $id)
                            ->where('ingreso', '>', 0)
                            ->first();
        $complemento = 20 - $cantidad_producto;
        //dd($complemento);
        return view('envio.vista_previa_envio')->with(compact('productos_envio', 'detalle', 'cantidad_producto', 'complemento'));
    }

}
