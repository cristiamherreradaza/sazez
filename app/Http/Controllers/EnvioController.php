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
            //AQUI SACAMOS EL MATERIAL SOLICITADO DEL ALMACEN CENTRAL
            $salida = new Movimiento();
            $salida->user_id = Auth::user()->id;
            $salida->producto_id = $ll;
            $salida->almacene_id = 1;
            $salida->salida = $request->item[$ll];
            $salida->fecha = $hoy;
            $salida->numero = $numero;
            $salida->estado = 'Envio';
            $salida->save();

            //AQUI INGRESAMOS EL MATERIAL AL ALMACEN QUE LO SOLICITO
            $ingreso = new Movimiento();
            $ingreso->user_id = Auth::user()->id;
            $ingreso->producto_id = $ll;
            $ingreso->almacene_id = $request->almacen_a_pedir;
            $ingreso->ingreso = $request->item[$ll];
            $ingreso->fecha = $hoy;
            $ingreso->numero = $numero;
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
        $productos = DB::table('movimientos')
                ->where('movimientos.estado', '=', 'Envio')
                ->where('movimientos.ingreso', '>', 0)
                ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                ->select('movimientos.id', 'almacenes.nombre', 'users.name', 'movimientos.fecha', 'movimientos.estado'
                );

         return Datatables::of($productos)
                ->addColumn('action', function ($productos) {
                    return '<button onclick="ver_pedido(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
                })
                ->make(true); 
        
    }

    public function ver_pedido($id)
    {
        $movimientos = Movimiento::find($id);
        $numero = $movimientos->numero;
        $datos = DB::table('movimientos')
                ->where('movimientos.id', '=', $movimientos->id)
                ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                ->select('almacenes.nombre', 'users.name', 'movimientos.numero', 'movimientos.fecha')
                ->get();
        // dd($datos);
        // $entrega = Pedido::find($id);

        $productos = DB::table('movimientos')
                ->where('movimientos.numero', '=', $numero)
                ->where('movimientos.ingreso', '>', 0)
                ->join('productos', 'movimientos.producto_id', '=', 'productos.id')
                ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->join('tipos', 'productos.tipo_id', '=', 'tipos.id')
                ->select('movimientos.*', 'productos.codigo', 'productos.nombre', 'marcas.nombre as nombre_marca', 'tipos.nombre as nombre_tipo', 'productos.modelo', 'productos.colores')
                ->get();
        // dd($productos);
        return view('envio.ver_pedido')->with(compact('datos', 'productos'));
    }
}
