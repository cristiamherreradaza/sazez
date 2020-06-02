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
        $almacenes = Almacene::get();
        return view('envio.nuevo')->with(compact('almacenes'));
    }

    public function ajaxBuscaProductos(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('envio.ajaxBuscaProductos')->with(compact('productos'));
    }

    public function guarda(Request $request)
    {
        //arraykeys guarda ids de prod
        $llaves = array_keys($request->item);
        foreach ($llaves as $key => $ll) 
        {
            //AQUI SACAMOS EL MATERIAL SOLICITADO DEL ALMACEN CENTRAL
            $salida = new Movimiento();
            $salida->user_id = Auth::user()->id;
            $salida->producto_id = $ll;
            $salida->almacene_id = 1;
            $salida->salida = $request->item[$ll];
            $salida->estado = 'Envio';
            $salida->save();

            //AQUI INGRESAMOS EL MATERIAL AL ALMACEN QUE LO SOLICITO
            $ingreso = new Movimiento();
            $ingreso->user_id = Auth::user()->id;
            $ingreso->producto_id = $ll;
            $ingreso->almacene_id = $request->almacen_a_pedir;
            $ingreso->ingreso = $request->item[$ll];
            $ingreso->estado = 'Envio';
            $ingreso->save();

        }
        return redirect('Envio/listado');
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
                    return '<button type="button" class="btn btn-warning" title="Editar pedido"  onclick="editar(' . $pedidos->id . ')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar pedido"  onclick="eliminar(' .  $pedidos->id . ')"><i class="fas fa-trash"></i></button>
                                <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel(' .  $pedidos->id . ')"><i class="fas fa-file-excel"></i></button>
                                <button onclick="ver_pedido(' . $pedidos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
                })
                ->make(true); 
        
    }
}
