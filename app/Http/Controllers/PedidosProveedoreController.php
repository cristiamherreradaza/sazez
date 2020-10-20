<?php

namespace App\Http\Controllers;

use App\Almacene;
use App\Producto;
use App\Proveedore;
use App\PedidosProveedore;
use Illuminate\Http\Request;
use App\ProductosPedidoProveedore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidosProveedoreController extends Controller
{
    public function nuevo()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $proveedores = Proveedore::get();
        return view('pedidos_proveedores.nuevo')->with(compact('almacenes', 'proveedores'));
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $almacen_id = $request->almacen;
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('pedidos_proveedores.ajaxBuscaProducto')->with(compact('productos', 'almacen_id'));
    }

    public function guarda(Request $request)
    {
        // dd($request->all());
        $fecha = date("Y-m-d H:i:s");

        if($request->cantidad)
        {

            $num_ingreso = DB::select("SELECT MAX(numero) as nroi
            FROM pedidos_proveedores");
            if (!empty($num_ingreso)) {
                $numeroi = $num_ingreso[0]->nroi + 1;
            } else {
                $numeroi = 1;
            }

            $pp                = new PedidosProveedore();
            $pp->user_id       = Auth::user()->id;
            $pp->almacene_id   = Auth::user()->almacen->id;
            $pp->numero        = $numeroi;
            $pp->proveedore_id = $request->proveedor;
            $pp->fecha         = $fecha;
            $pp->save();
            $ppId = $pp->id;

            $llaves = array_keys($request->cantidad);
            foreach ($llaves as $key => $ll) 
            {
                $ingreso                        = new ProductosPedidoProveedore();
                $ingreso->user_id               = Auth::user()->id;
                $ingreso->pedidos_proveedore_id = $ppId;
                $ingreso->producto_id           = $ll;
                $ingreso->escala_id             = $request->escala_id_m[$ll];
                $ingreso->caja                  = $request->precio[$ll];
                $ingreso->cantidad              = $request->cantidad[$ll];
                $ingreso->save();

            }
            return redirect('PedidosProveedore/verPedido/'.$ppId);
        }
    }

    public function verPedido(Request $request, $idPedido)
    {
        $datosPedido = PedidosProveedore::where('id', $idPedido)->first();
        $productosPedido = ProductosPedidoProveedore::where('pedidos_proveedore_id', $idPedido)->get();
        return view('pedidos_proveedores.verPedido')->with(compact('datosPedido', 'productosPedido'));
    }

    public function listado()
    {
        return view('pedidos_proveedores.listado');
    }

    public function ajaxListado()
    {
        $ingresos = Movimiento::where('movimientos.estado', '=', 'Ingreso')
                            ->where('movimientos.ingreso', '>', 0)
                            ->whereNotNull('numero_ingreso')
                            ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                            ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                            //->distinct()
                            ->select(
                                'movimientos.numero_ingreso',
                                'almacenes.nombre',
                                'users.name',
                                'movimientos.fecha',
                                'movimientos.estado'
                            )
                            ->groupBy('movimientos.numero_ingreso')
                            ->orderBy('movimientos.id', 'desc');
        if(Auth::user()->perfil_id != 1){
            $ingresos->where('movimientos.almacene_id', Auth::user()->almacen->id);
        }
        return Datatables::of($ingresos)
                ->addColumn('action', function ($ingresos) {
                    return '<button onclick="ver_pedido(' . $ingresos->numero_ingreso . ')" class="btn btn-info" title="Ver detalle"><i class="fas fa-eye"></i></button>';
                })
                ->make(true); 
    }

}
