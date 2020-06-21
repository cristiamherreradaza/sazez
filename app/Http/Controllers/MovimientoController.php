<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Movimiento;
use App\Almacene;
use App\Producto;

class MovimientoController extends Controller
{
    public function registraDatos()
    {
        // dd($productos[0]->id);
        for ($i=0; $i < 1000 ; $i++) { 
            $productos = DB::select('select id from productos order by rand() limit 1', [1]);
            $almacenes = DB::select('select id from almacenes order by rand() limit 1', [1]);
            $movimientos = new Movimiento();
            $is = rand(1,2);
            if($is == 1){
                $movimientos->ingreso = rand(1,100);
                $movimientos->salida = 0;
            }else{
                $movimientos->ingreso = 0;
                $movimientos->salida = rand(1,100);
            }
            # code...
            $movimientos->user_id = 1;
            $movimientos->producto_id = $productos[0]->id;
            $movimientos->almacene_id = $almacenes[0]->id;
            $movimientos->precio_compra = rand(15, 1000);
            $movimientos->precio_venta = rand(15, 1000);
            $movimientos->save();
            echo 'insertando '.$i."<br />"; 
        }
    }

    public function ingreso()
    {
        $almacenes = Almacene::get();
        return view('movimiento.ingreso')->with(compact('almacenes'));
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('movimiento.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function guarda(Request $request)
    {
        $fecha = date("Y-m-d H:i:s");
        $llaves = array_keys($request->precio);
        foreach ($llaves as $key => $ll) 
        {
            // Creación de Movimiento
            $ingreso = new Movimiento();
            $ingreso->user_id = Auth::user()->id;
            $ingreso->producto_id = $ll;
            $ingreso->almacene_id = $request->almacen;
            $ingreso->ingreso = $request->subtotal[$ll];
            $ingreso->fecha = $fecha;
            $ingreso->save();
        }
        return redirect('Producto/listado');
    }

    public function ajaxMuestraTotalesAlmacen(Request $request)
    {
        // echo "desde movimientos";
        $producto_id = $request->producto_id;
        $datosProducto = Producto::find($producto_id);
        $cantidadTotal = Movimiento::select(
            DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as total'),
            'almacenes.nombre as almacen'
        )
        ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
        ->where('movimientos.producto_id', $producto_id)
        ->groupBy('movimientos.almacene_id')
        ->get();
        return view('movimiento.ajaxMuestraTotalesAlmacen')->with(compact('cantidadTotal', 'datosProducto'));
        // dd($cantidadTotal);
    }
}
