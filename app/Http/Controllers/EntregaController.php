<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Pedido;
use DataTables;
use App\Almacene;
use App\Producto;
use App\Movimiento;
use App\PedidosProducto;
use Illuminate\Http\Request;
use App\Imports\EnviosImport;
use App\Imports\MovimientosImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PedidosProductosExport;

class EntregaController extends Controller
{
     public function entrega($id)
    {
        // $pedidos = DB::table('pedidos')
        //         ->where('pedidos.id', '=', $id)
        //         ->join('almacenes', 'pedidos.almacene_solicitante_id', '=', 'almacenes.id')
        //         ->select('pedidos.*', 'almacenes.nombre')
        //         ->get();
        // dd($pedidos[0]->id);
        // $entrega = Pedido::find($id);
        $pedido = Pedido::find($id);
        if($pedido->estado == 'Entregado'){
            //dd('entregado');
            return redirect('Pedido/listado');
        }else{
            //dd('no entregado');
            $pedido_productos = PedidosProducto::where('pedido_id', $pedido->id)
                                                ->get();
            return view('entrega.entrega')->with(compact('pedido', 'pedido_productos'));
        }
        // $productos = DB::table('pedidos_productos')
        //         ->where('pedidos_productos.pedido_id', '=', $id)
        //         ->join('productos', 'pedidos_productos.producto_id', '=', 'productos.id')
        //         ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
        //         ->join('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //         ->select('pedidos_productos.*', 'productos.codigo', 'productos.nombre', 'marcas.nombre as nombre_marca', 'tipos.nombre as nombre_tipo', 'productos.modelo', 'productos.colores')
        //         ->get();

        
        // dd($productos);
        
    }   

    public function store(Request $request)
    {
        $pedido = Pedido::find($request->pedido_id);
        if($pedido->estado == 'Entregado'){
            return redirect('Entrega/ver_pedido/'.$pedido->id);
        }else{
            $efectuado = 'No';
            $maximo = Movimiento::max('numero');
            if ($maximo) {
                $numero = $maximo + 1;
            } else {
                $numero = 1;
            }
            // Del pedido X, sacamos a los productos
            $pedido_productos = PedidosProducto::where('pedido_id', $pedido->id)->get();
            foreach($pedido_productos as $producto){
                $cantidad_x = 'cantidad_'.$producto->producto_id;
                // Si la cantidad del producto X, es mayor a 0
                if($request->$cantidad_x > 0){
                    // Sacamos el stock existente en almacen X del producto X
                    $ingreso = Movimiento::where('producto_id', $producto->producto_id)
                                        ->where('almacene_id', $pedido->almacene_id)
                                        ->where('ingreso', '>', 0)
                                        ->sum('ingreso');
                    $salida = Movimiento::where('producto_id', $producto->producto_id)
                                        ->where('almacene_id', $pedido->almacene_id)
                                        ->where('salida', '>', 0)
                                        ->sum('salida');
                    $cantidad_disponible = $ingreso - $salida;
                    // Si la cantidad solicitada, no supera a la existente en almacen X del producto X 
                    if($cantidad_disponible >= $request->$cantidad_x){
                        // Se efectua el envio, por tanto cambia la variable $efectuado al valor 'Si'
                        $efectuado = 'Si';
                        // Buscamos al producto
                        $item = Producto::find($producto->producto_id);
                        //AQUI SACAMOS EL MATERIAL SOLICITADO DEL ALMACEN CENTRAL
                        $salida = new Movimiento();
                        $salida->user_id = Auth::user()->id;
                        $salida->producto_id = $producto->producto_id;
                        $salida->tipo_id = $item->tipo_id;
                        $salida->almacene_id = $pedido->almacene_id;
                        $salida->pedido_id = $pedido->id;
                        $salida->salida = $request->$cantidad_x;
                        $salida->fecha = date("Y-m-d H:i:s");
                        $salida->numero = $numero;
                        $salida->estado = 'Envio';
                        $salida->dispositivo  = session('dispositivo');
                        $salida->save();
        
                        //AQUI INGRESAMOS EL MATERIAL AL ALMACEN QUE LO SOLICITO
                        $ingreso = new Movimiento();
                        $ingreso->user_id = Auth::user()->id;
                        $ingreso->producto_id = $producto->producto_id;
                        $ingreso->tipo_id = $item->tipo_id;
                        $ingreso->almacen_origen_id = $pedido->almacene_id;
                        $ingreso->almacene_id = $pedido->almacene_solicitante_id;
                        $ingreso->pedido_id = $pedido->id;
                        $ingreso->ingreso = $request->$cantidad_x;
                        $ingreso->fecha = date("Y-m-d H:i:s");
                        $ingreso->numero = $numero;
                        $ingreso->estado = 'Envio';
                        $ingreso->dispositivo  = session('dispositivo');
                        $ingreso->save();
                    }
                }
            }
            //ACTUALIZAMOS EL PEDIDO A ENTREGADO
            if($efectuado == 'Si'){
                $pedido->estado = 'Entregado';
                $pedido->save();
                return redirect('Entrega/ver_pedido/'.$pedido->id);
            }
            return redirect('Pedido/listado');
        }
    }

    public function excel($id)
    {
        return Excel::download(new PedidosProductosExport($id), date('Y-m-d').'-Pedidos.xlsx');
    }

    public function envio()
    {
        $almacenes = Almacene::get();
        return view('envio.envio')->with(compact('almacenes'));
    }  

    // funcion para importar los envios
    public function ajax_importar(Request $request)
    {
        $num = DB::select("SELECT MAX(numero) as nro
                                FROM movimientos");
        if (!empty($num)) {
            $numero = $num[0]->nro + 1;
        } else {
            $numero = 1;
        }
        // $file = $request->file('file');
        // Excel::import(new EnviosImport, $file);
        $validation = Validator::make($request->all(), [
            'select_file' => 'required|mimes:xlsx|max:2048'
        ]);
        if($validation->passes())
        {
            session(['numero' => $numero]);
            $file = $request->file('select_file');
            Excel::import(new EnviosImport, $file);
            session()->forget('numero');
            return response()->json([
                'message' => 'Importacion realizada con exito',
                'sw' => 1
            ]);
        }
        else
        {
            switch ($validation->errors()->first()) {
                case "The select file field is required.":
                    $mensaje = "Es necesario agregar un archivo Excel.";
                    break;
                case "The select file must be a file of type: xlsx.":
                    $mensaje = "El archivo debe ser de tipo: Excel.";
                    break;
                default:
                    $mensaje = "Fallo al importar el archivo seleccionado.";
                    break;
            }
            return response()->json([
                //0
                'message' => $mensaje,
                'sw' => 0
            ]);
        }
    }

    public function importar_envio(Request $request)
    {
        $pedido = Pedido::find($request->pedido_id);
        // $num = DB::select("SELECT MAX(numero) as nro
        //                         FROM movimientos");
        //dd('hola');
        //dd($pedido->estado);
        if($pedido->estado != 'Entregado'){
            //dd('es null');
            $maximo = Movimiento::max('numero');
            if ($maximo) {
                $numero = $maximo + 1;
            } else {
                $numero = 1;
            }
            //dd($numero);

            $sw=0;
            //$pedido = $request->all('pedido_id');
            //$pedido_id = $pedido['pedido_id'];
            // dd($pedido_id);
            // Excel::import(new MovimientosImport, $file);
            $validation = Validator::make($request->all(), [
                'select_file' => 'required|mimes:xlsx|max:2048'
            ]);
            if($validation->passes())
            {
                // Creamos variables de sesión para pasar al import
                session(['pedido' => $pedido]);
                session(['numero' => $numero]);
                $file = $request->file('select_file');
                Excel::import(new MovimientosImport, $file);
                // Eliminarmos variables de sesión
                session()->forget('pedido');
                session()->forget('numero');
                // Verificamos si hubo algun envio
                $pedido = Pedido::find($request->pedido_id);
                if($pedido->estado == 'Entregado'){
                    $sw=1;
                }
                //ACTUALIZAMOS EL PEDIDO A ENTREGADO
                // $pedidos = Pedido::find($pedido_id);
                // $pedidos->estado = 'Entregado';
                // $pedidos->save();
                if($sw==1){
                    return response()->json([
                        'message' => 'Importacion realizada con exito',
                        'numero' => $pedido->id,
                        'sw' => $sw
                    ]);
                }else{
                    return response()->json([
                        'message' => 'Alguna cantidad solicitada supera al stock actual',
                        'numero' => $pedido->id,
                        'sw' => $sw
                    ]);
                }
            }
            else
            {
                switch ($validation->errors()->first()) {
                    case "The select file field is required.":
                        $mensaje = "Es necesario agregar un archivo Excel.";
                        break;
                    case "The select file must be a file of type: xlsx.":
                        $mensaje = "El archivo debe ser de tipo: Excel.";
                        break;
                    default:
                        $mensaje = "Fallo al importar el archivo seleccionado.";
                        break;
                }
                return response()->json([
                    //0
                    'message' => $mensaje,
                    'sw' => 0
                ]);
            }
        }else{
            //dd($pedido->estado);
            return response()->json([
                //0
                'message' => 'El Pedido ya fue entregado',
                'sw' => 0
            ]);
        }
        
    }

    public function ver_pedido($id)
    {
        $pedido = Pedido::find($id);
        $pedido_productos = PedidosProducto::where('pedido_id', $pedido->id)
                                            ->get();
        if($pedido->estado == 'Entregado')
        {
            $movimientos = Movimiento::where('pedido_id', $pedido->id)->get();
            $envio = Movimiento::where('pedido_id', $pedido->id)->where('ingreso', '>', 0)->first();
            if(!$envio){
                $pedido->estado = NULL;
                $pedido->save();
                return redirect('Pedido/listado');
            }else{
                return view('entrega.ver_pedido_entregado')->with(compact('pedido', 'pedido_productos', 'movimientos', 'envio'));
            }
        }
        else
        {
            return view('entrega.ver_pedido')->with(compact('pedido', 'pedido_productos'));
        }
        /*
        $pedidos = DB::table('pedidos')
                ->where('pedidos.id', '=', $id)
                ->join('almacenes', 'pedidos.almacene_solicitante_id', '=', 'almacenes.id')
                ->select('pedidos.*', 'almacenes.nombre')
                ->get();
        // dd($pedidos[0]->id);
        // $entrega = Pedido::find($id);

        $productos = DB::table('movimientos')
                ->where('movimientos.pedido_id', '=', $id)
                ->where('movimientos.almacene_id', '=', $pedidos[0]->almacene_solicitante_id)
                ->where('movimientos.ingreso', '>', 0)
                ->join('productos', 'movimientos.producto_id', '=', 'productos.id')
                ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->join('tipos', 'productos.tipo_id', '=', 'tipos.id')
                ->select('movimientos.*', 'productos.codigo', 'productos.nombre', 'marcas.nombre as nombre_marca', 'tipos.nombre as nombre_tipo', 'productos.modelo', 'productos.colores')
                ->get();
        // dd($productos);
        return view('entrega.ver_pedido')->with(compact('pedidos', 'entregas', 'productos'));
        */
    }

    public function vista_previa_entrega($id)
    {
        $pedido = Pedido::find($id);
        $productos_pedido = PedidosProducto::where('pedido_id', $pedido->id)
                                            ->get();
        $entregas = Movimiento::where('pedido_id', $pedido->id)
                            ->whereNull('almacen_origen_id')
                            ->get();
        $cantidad_producto = PedidosProducto::where('pedido_id', $pedido->id)
                                        ->count();
        $complemento = 20 - $cantidad_producto;
        // dd($complemento);
        //dd($entregas);

        // $productos_envio = Movimiento::where('estado', 'Envio')
        //                             ->where('numero', $id)
        //                             ->where('ingreso', '>', 0)
        //                             ->get();
        // $cantidad_producto = Movimiento::where('estado', 'Envio')
        //                                 ->where('numero', $id)
        //                                 ->where('ingreso', '>', 0)
        //                                 ->count();
        // $detalle = Movimiento::where('estado', 'Envio')
        //                     ->where('numero', $id)
        //                     ->where('ingreso', '>', 0)
        //                     ->first();

        return view('entrega.vista_previa_entrega')->with(compact('pedido', 'cantidad_producto', 'productos_pedido', 'entregas', 'complemento'));   
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
        return redirect("Entrega/ver_pedido/$pedido->id");
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

    public function eliminaEntrega($id)
    {
        $pedidos_producto = PedidosProducto::find($id);
        $producto_id = $pedidos_producto->producto_id;
        $productos = Movimiento::where('producto_id', $producto_id)
                                ->where('pedido_id', $pedidos_producto->pedido_id)
                                ->get();
        foreach($productos as $producto){
            $producto->delete();
        }
        return redirect("Entrega/ver_pedido/$pedidos_producto->pedido_id");
    }

    public function eliminaEnvio($id)
    {
        $registro = Movimiento::where('numero', $id)->first();
        $pedido = Pedido::find($registro->pedido_id);
        $productos_pedido = Movimiento::where('numero', $id)
                                    ->where('pedido_id', $pedido->id)
                                    ->get();
        foreach($productos_pedido as $producto){
            $producto->delete();
        }
        $pedido->estado = NULL;
        $pedido->save();
        return redirect('Pedido/listado');
    }

    public function modificar(Request $request)
    {
        //dd($request->id);
        $pedidos_producto = PedidosProducto::find($request->id);
        $pedido = Pedido::find($pedidos_producto->pedido_id);
        $item = Producto::find($pedidos_producto->producto_id);
        // Tenemos que verificar que no exceda el stock actual, la cantidad solicitada del producto X en el almacen X
        $ingreso = Movimiento::where('producto_id', $item->id)
                            ->where('almacene_id', $pedido->almacene_id)
                            ->where('ingreso', '>', 0)
                            ->sum('ingreso');
        $salida = Movimiento::where('producto_id', $item->id)
                            ->where('almacene_id', $pedido->almacene_id)
                            ->where('salida', '>', 0)
                            ->sum('salida');
        $cantidad_disponible = $ingreso - $salida;
        if($cantidad_disponible >= $request->cantidad_enviar)
        {
            // Eliminamos registros anteriores
            $productos = Movimiento::where('producto_id', $item->id)
                                ->where('pedido_id', $pedidos_producto->pedido_id)
                                ->get();
            foreach($productos as $producto){
                $producto->delete();
            }
            // Creamos nuevos registros Salida del producto X del almacen X
            $salida = new Movimiento();
            $salida->user_id = Auth::user()->id;
            $salida->producto_id = $item->id;
            $salida->tipo_id = $item->tipo_id;
            $salida->almacene_id = $pedido->almacene_id;
            $salida->pedido_id = $pedido->id;
            $salida->salida = $request->cantidad_enviar;
            $salida->fecha = date("Y-m-d H:i:s");
            $salida->numero = $request->numero_envio;
            $salida->estado = 'Envio';
            $salida->dispositivo  = session('dispositivo');
            $salida->save();

            // Creamos nuevos registros Entrada del producto X al almacen Y
            $ingreso = new Movimiento();
            $ingreso->user_id = Auth::user()->id;
            $ingreso->producto_id = $item->id;
            $ingreso->tipo_id = $item->tipo_id;
            $ingreso->almacen_origen_id = $pedido->almacene_id;
            $ingreso->almacene_id = $pedido->almacene_solicitante_id;
            $ingreso->pedido_id = $pedido->id;
            $ingreso->ingreso = $request->cantidad_enviar;
            $ingreso->fecha = date("Y-m-d H:i:s");
            $ingreso->numero = $request->numero_envio;
            $ingreso->estado = 'Envio';
            $ingreso->dispositivo  = session('dispositivo');
            $ingreso->save();
        }
        return redirect("Entrega/ver_pedido/$pedido->id");
    }
}
