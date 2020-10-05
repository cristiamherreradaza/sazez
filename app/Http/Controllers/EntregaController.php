<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Producto;
use App\Pedido;
use App\Almacene;
use App\PedidosProducto;
use App\Movimiento;
use DB;
use App\Exports\PedidosProductosExport;
use App\Imports\MovimientosImport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

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
            return redirect('Entrega/ver_pedido/'.$pedido->numero);
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
                        $ingreso->save();
                    }
                }
            }
            //ACTUALIZAMOS EL PEDIDO A ENTREGADO
            if($efectuado == 'Si'){
                $pedido->estado = 'Entregado';
                $pedido->save();
                return redirect('Entrega/ver_pedido/'.$pedido->numero);
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

    // No usado
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
        // Excel::import(new MovimientosImport, $file);
        $validation = Validator::make($request->all(), [
            'select_file' => 'required|mimes:xlsx|max:2048'
        ]);
        if($validation->passes())
        {
            session(['numero' => $numero]);
            $file = $request->file('select_file');
            Excel::import(new MovimientosImport, $file);
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
            return view('entrega.ver_pedido_entregado')->with(compact('pedido', 'pedido_productos', 'movimientos'));
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
}
