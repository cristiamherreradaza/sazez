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

        if($request->precio)
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

            $llaves = array_keys($request->precio);
            foreach ($llaves as $key => $ll) 
            {
                $ingreso                        = new ProductosPedidoProveedore();
                $ingreso->user_id               = Auth::user()->id;
                $ingreso->pedidos_proveedore_id = $ppId;
                $ingreso->producto_id           = $ll;
                $ingreso->caja                  = $request->precio[$ll];
                $ingreso->cantidad                  = $request->cantidad[$ll];
                $ingreso->save();

            }
            // return redirect('Producto/ver_ingreso/'.$numeroi);
        }
    }

}
