<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Movimiento;
use App\Almacene;
use App\Producto;
use App\Proveedore;

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
        $almacenes = Almacene::whereNull('estado')->get();
        $proveedores = Proveedore::get();
        return view('movimiento.ingreso')->with(compact('almacenes', 'proveedores'));
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
        if($request->incluye_almacen == 'Si'){
            // Crear 3 registros
            if($request->precio)        // Si existen items
            {
                $num = DB::select("SELECT MAX(numero) as nro
                                FROM movimientos");
                if (!empty($num)) {
                    $numero = $num[0]->nro + 1;
                } else {
                    $numero = 1;
                }

                $num_ingreso = DB::select("SELECT MAX(numero_ingreso) as nroi
                FROM movimientos");
                if (!empty($num_ingreso)) {
                    $numeroi = $num_ingreso[0]->nroi + 1;
                } else {
                    $numeroi = 1;
                }

                $fecha = date("Y-m-d H:i:s");
                $llaves = array_keys($request->precio);     // Sacamos los items
                foreach ($llaves as $key => $ll) 
                {
                    // Creación de Movimiento - Ingresa a Almacen Central
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $ll;
                    $ingreso->almacene_id =  1;             // Siempre sera 1?
                    $ingreso->proveedor_id = $request->proveedor;
                    $ingreso->ingreso = $request->subtotal[$ll];
                    $ingreso->estado = 'Ingreso';           //Ingreso
                    $ingreso->numero_ingreso = $numeroi;           //Ingreso
                    $ingreso->fecha = $fecha;
                    $ingreso->save();
                    // Creación de Movimiento - Sale de Almacen Central
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $ll;
                    $ingreso->almacene_id = 1;              // Siempre sera 1?
                    $ingreso->salida = $request->subtotal[$ll];
                    $ingreso->estado = 'Envio';           //Ingreso/Envio/Salida
                    //Adicionar  numero correlativo
                    $ingreso->numero = $numero;
                    $ingreso->fecha = $fecha;
                    $ingreso->save();
                    // Creación de Movimiento - Ingresa a la Sucursal
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $ll;
                    $ingreso->almacen_origen_id = 1;        // Siempre sera 1?
                    $ingreso->almacene_id = $request->almacen;
                    $ingreso->ingreso = $request->subtotal[$ll];
                    $ingreso->estado = 'Envio';           //Ingreso/Envio
                    //Adicionar numero correlativo
                    $ingreso->numero = $numero;
                    $ingreso->fecha = $fecha;
                    $ingreso->save();
                }
            }
        }else{
            // Crear 1 registro
            if($request->precio)
            {
                $num_ingreso = DB::select("SELECT MAX(numero_ingreso) as nroi
                FROM movimientos");
                if (!empty($num_ingreso)) {
                    $numeroi = $num_ingreso[0]->nroi + 1;
                } else {
                    $numeroi = 1;
                }

                $fecha = date("Y-m-d H:i:s");
                $llaves = array_keys($request->precio);
                foreach ($llaves as $key => $ll) 
                {
                    // Creación de Movimiento
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $ll;
                    $ingreso->almacene_id = $request->almacen;
                    $ingreso->proveedor_id = $request->proveedor;
                    $ingreso->ingreso = $request->subtotal[$ll];
                    $ingreso->estado = 'Ingreso';
                    $ingreso->numero_ingreso = $numeroi; //Ingreso
                    $ingreso->fecha = $fecha;
                    $ingreso->save();
                }
            }
        }
        //Generar su reporte de envio
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

    public function reportar(Request $request)
    {
        //dd('hola');
        $producto_reportado = new Movimiento();
        $producto_reportado->user_id = Auth::user()->id;
        $producto_reportado->producto_id = $request->id_producto_a_reportar;
        $producto_reportado->almacene_id = Auth::user()->almacen->id;
        $producto_reportado->salida = $request->cantidad_producto_a_reportar;
        $producto_reportado->fecha = date('Y-m-d H:i:s');
        $producto_reportado->estado = 'Defectuoso';
        $producto_reportado->descripcion = $request->descripcion_producto_a_reportar;
        $producto_reportado->save();
        return redirect('Producto/listado');
    }

    public function habilitar(Request $request)
    {
        $producto_habilitado = new Movimiento();
        $producto_habilitado->user_id = Auth::user()->id;
        $producto_habilitado->producto_id = $request->id_producto_a_habilitar;
        $producto_habilitado->almacene_id = Auth::user()->almacen->id;
        $producto_habilitado->ingreso = $request->cantidad_producto_a_habilitar;
        $producto_habilitado->fecha = date('Y-m-d H:i:s');
        $producto_habilitado->estado = 'Reacondicionado';
        $producto_habilitado->descripcion = $request->descripcion_producto_a_habilitar;
        $producto_habilitado->save();
        return redirect('Producto/listado');
    }
}
