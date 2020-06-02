<?php

namespace App\Http\Controllers;

use App\User;
use App\Venta;
use App\Almacene;
use App\Producto;
use App\Movimiento;
use DataTables;
use App\Cotizacione;
use App\VentasProducto;
use Illuminate\Http\Request;
use App\CotizacionesProducto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function nuevo()
    {
        // dd($cantidadTotal->total);
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
        if($request->session()->has('cotizacion_id'))
        {
            $cotizacion_id = $request->session()->get('cotizacion_id');
        }else{
            $cotizacion              = new Cotizacione();
            $cotizacion->user_id     = Auth::user()->id;
            $cotizacion->almacene_id = Auth::user()->almacen_id;
            $cotizacion->cliente_id  = $request->cliente_id;
            $cotizacion->fecha       = $request->fecha;
            $cotizacion->save();
            $cotizacion_id = $cotizacion->id;
        }

        $request->session()->put('cotizacion_id', $cotizacion_id);

        $productosCotizacion                 = new CotizacionesProducto();
        $productosCotizacion->user_id        = Auth::user()->id;
        $productosCotizacion->cotizacione_id = $cotizacion_id;
        $productosCotizacion->producto_id    = $request->producto_id;
        $productosCotizacion->save();

        // borramos datos de la session
        // $request->session()->forget('key');
        // $request->session()->flush();

        $productosCotizacion = CotizacionesProducto::where('cotizacione_id', $cotizacion_id)->get();
        return view('venta.ajaxProductosCotizacion')->with(compact('productosCotizacion'));

    }

    public function tienda()
    {
        $almacenes = Almacene::get();
        $clientes = User::where('rol', 'Cliente')
                    ->get();
        return view('venta.tienda')->with(compact('almacenes', 'clientes'));
    }

    public function ajaxBuscaProductoTienda(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('venta.ajaxBuscaProductoTienda')->with(compact('productos'));
    }

    public function guardaVenta(Request $request)
    {
        // dd($request->all());
        $venta              = new Venta();
        $venta->user_id     = Auth::user()->id;
        $venta->almacene_id = Auth::user()->almacen_id;
        $venta->cliente_id  = $request->cliente_id;
        $venta->fecha       = $request->fecha;
        $venta->total       = $request->totalCompra;
        $venta->save();
        $venta_id = $venta->id;

        $llaves = array_keys($request->precio);
        foreach ($llaves as $key => $ll) {
            $productos                 = new VentasProducto();
            $productos->user_id        = Auth::user()->id;
            $productos->producto_id    = $ll;
            $productos->venta_id       = $venta_id;
            $productos->precio_venta   = $request->precio_venta[$ll];
            $productos->precio_cobrado = $request->precio[$ll];
            $productos->cantidad       = $request->cantidad[$ll];
            $productos->fecha          = $request->fecha;
            $productos->save();

            $movimiento               = new Movimiento();
            $movimiento->user_id      = Auth::user()->id;
            $movimiento->almacene_id  = Auth::user()->almacen_id;
            $movimiento->venta_id     = $venta_id;
            $movimiento->producto_id  = $ll;
            $movimiento->precio_venta = $request->precio[$ll];
            $movimiento->salida       = $request->cantidad[$ll];
            $movimiento->estado       = 'Venta';
            $movimiento->save();
        }
        return redirect('Venta/listado');

    }

    public function listado()
    {
        return view('venta.listado');
    }

    public function ajax_listado()
    {
        $almacen = Auth::user()->almacen_id;
        $ventas = DB::table('ventas')
            ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
            ->leftJoin('users', 'ventas.cliente_id', '=', 'users.id')
            ->select(
                'ventas.id', 
                'almacenes.nombre as almacene', 
                'users.name as user', 
                'ventas.total',
                'ventas.fecha'
            );

        return Datatables::of($ventas)
            ->addColumn('action', function ($ventas) {
                return '<button onclick="edita_producto(' . $ventas->id . ')" class="btn btn-warning"><i class="fas fa-edit"></i></button> <button onclick="asigna_materias(' . $ventas->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
            })
            ->make(true);    
    }

}