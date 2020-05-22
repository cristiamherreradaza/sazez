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
        $pedidos = DB::table('pedidos')
                ->where('pedidos.id', '=', $id)
                ->join('almacenes', 'pedidos.almacene_solicitante_id', '=', 'almacenes.id')
                ->select('pedidos.*', 'almacenes.nombre')
                ->get();
        // dd($pedidos[0]->id);
        // $entrega = Pedido::find($id);

        $productos = DB::table('pedidos_productos')
                ->where('pedidos_productos.pedido_id', '=', $id)
                ->join('productos', 'pedidos_productos.producto_id', '=', 'productos.id')
                ->select('pedidos_productos.*', 'productos.codigo', 'productos.nombre_venta')
                ->get();
        // dd($productos);
        return view('Entrega.entrega')->with(compact('pedidos', 'entregas', 'productos'));
    }   

    public function store(Request $request)
    {
        // $todo = $request->all();
        // dd($todo);
        $pedido_id = $request->input("pedido_id");
        $almacene_id = $request->input("almacene_id");
        $pedido = DB::table('pedidos_productos')
                ->where('pedido_id', '=', $pedido_id)
                ->get();
        foreach ($pedido as $valor) {
            $dato = 'cantidad_'.$valor->id;
            $cantidad = $request->input($dato);

            //AQUI SACAMOS EL MATERIAL SOLICITADO DEL ALMACEN CENTRAL
            $salida = new Movimiento();
            $salida->user_id = Auth::user()->id;
            $salida->producto_id = $valor->producto_id;
            $salida->almacene_id = 1;
            $salida->pedido_id = $pedido_id;
            $salida->salida = $cantidad;
            $salida->save();
        }

        foreach ($pedido as $valor1) {
            $dato1 = 'cantidad_'.$valor1->id;
            $cantidad1 = $request->input($dato1);

            //AQUI INGRESAMOS EL MATERIAL AL ALMACEN QUE LO SOLICITO
            $ingreso = new Movimiento();
            $ingreso->user_id = Auth::user()->id;
            $ingreso->producto_id = $valor1->producto_id;
            $ingreso->almacene_id = $almacene_id;
            $ingreso->pedido_id = $pedido_id;
            $ingreso->ingreso = $cantidad1;
            $ingreso->save();
        }
    return redirect('Pedido/listado');
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

    public function ajax_importar(Request $request)
    {
        // $file = $request->file('file');
        // Excel::import(new MovimientosImport, $file);
        $validation = Validator::make($request->all(), [
            'select_file' => 'required|mimes:xlsx|max:2048'
        ]);
        if($validation->passes())
        {
            $file = $request->file('select_file');
            Excel::import(new MovimientosImport, $file);
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
}
