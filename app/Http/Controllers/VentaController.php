<?php

namespace App\Http\Controllers;

use App\User;
use App\Producto;
use App\Cotizacione;
use Illuminate\Http\Request;
use App\CotizacionesProducto;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function nuevo()
    {
        $almacen_id = Auth::user()->almacen_id;
        $clientes = User::where('rol', 'Cliente')
                    ->get();
        // dd($almacen_id);
        return view('venta.nuevo')->with(compact(
            'almacen_id',
            'clientes'
        ));
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $arrayProductos = [];
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();

        foreach ($productos as $key => $p) {
            $arrayProductos[] = [
                'id'      => $p->id,
                'codigo'  => $p->codigo,
                'nombre'  => $p->nombre,
                'marca'   => $p->marca->nombre,
                'tipo'    => $p->tipo->nombre,
                'modelo'  => $p->modelo,
                'colores' => $p->colores
            ];
        }
        // dd($arrayProductos);
        return response()->json([
            'arrayProductos' => $arrayProductos,
            'pedido_id'      => 1
        ]);


        // return view('venta.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function adicionaItem(Request $request)
    {
        // dd($request->all());
        if($request->cotizacion_id == null)
        {
            $cotizacion              = new Cotizacione();
            $cotizacion->user_id     = Auth::user()->id;
            $cotizacion->almacene_id = Auth::user()->almacen_id;
            $cotizacion->cliente_id  = $request->cliente_id;
            // $cotizacion->cliente_id  = $request->cliente_id;
            $cotizacion->fecha       = $request->fecha;
            $cotizacion->save();
            $cotizacion_id = $cotizacion->id;
        }else{
            $cotizacion_id = $request->cotizacione_id;
        }

        $productosCotizacion                 = new CotizacionesProducto();
        $productosCotizacion->user_id        = Auth::user()->id;
        $productosCotizacion->cotizacione_id = $cotizacion_id;
        $productosCotizacion->producto_id    = $request->producto_id;
        $productosCotizacion->save();

        $productosCotizacion = CotizacionesProducto::where('cotizacione_id', $cotizacion_id)->get();
        // dd($productosCotizacion);
        return view('venta.ajaxProductosCotizacion')->with(compact('productosCotizacion'));

    }

}