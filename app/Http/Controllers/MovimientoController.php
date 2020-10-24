<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Movimiento;
use App\Almacene;
use App\Producto;
use App\Proveedore;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosExport;
use App\Imports\IngresoImport;
use Validator;

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
        $almacen_id = $request->almacen;
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('movimiento.ajaxBuscaProducto')->with(compact('productos', 'almacen_id'));
    }

    public function guarda(Request $request)
    {
        if($request->incluye_almacen == 'Si'){

            // dd($request->all());
            // Crear 3 registros
            if($request->producto_id)        // Si existen items
            {
                // Numero maximo de Envio
                $num = DB::select("SELECT MAX(numero) as nro
                                FROM movimientos");
                if (!empty($num)) {
                    $numero = $num[0]->nro + 1;
                } else {
                    $numero = 1;
                }

                // Numero maximo de Ingreso
                $num_ingreso = DB::select("SELECT MAX(numero_ingreso) as nroi
                FROM movimientos");
                if (!empty($num_ingreso)) {
                    $numeroi = $num_ingreso[0]->nroi + 1;
                } else {
                    $numeroi = 1;
                }

                // Numero maximo de Ingreso-Envio
                $maximo_ingreso_envio = Movimiento::max('numero_ingreso_envio');
                if ($maximo_ingreso_envio) {
                    $numero_ingreso_envio = $maximo_ingreso_envio + 1;
                } else {
                    $numero_ingreso_envio = 1;
                }

                $fecha = date("Y-m-d H:i:s");
                $llaves = array_keys($request->producto_id);     // Sacamos los items
                foreach ($llaves as $key => $ll) 
                {
                    $cantidaMayor = $request->cantidad[$ll];
                    $cantidadEscala = $request->cantidad_escala_m[$ll];
                    $cantidadIngresada = $cantidaMayor * $cantidadEscala;

                    // Buscamos al producto mediante
                    $producto = Producto::find($ll);
                    // Creación de Movimiento - Ingresa a Almacen Central
                    $ingreso                       = new Movimiento();
                    $ingreso->user_id              = Auth::user()->id;
                    $ingreso->producto_id          = $ll;
                    $ingreso->tipo_id              = $producto->tipo_id;
                    $ingreso->almacene_id          = 1;                           // Siempre sera 1?
                    $ingreso->proveedor_id         = $request->proveedor;
                    $ingreso->escala_id            = $request->escala_id_m[$ll];
                    $ingreso->ingreso              = $cantidadIngresada;
                    $ingreso->estado               = 'Ingreso';                   //Ingreso
                    $ingreso->numero_ingreso       = $numeroi;                    //Ingreso
                    $ingreso->numero_ingreso_envio = $numero_ingreso_envio;
                    $ingreso->fecha                = $fecha;
                    $ingreso->dispositivo          = session('dispositivo');
                    $ingreso->save();
                    // Creación de Movimiento - Sale de Almacen Central
                    $ingreso                       = new Movimiento();
                    $ingreso->user_id              = Auth::user()->id;
                    $ingreso->producto_id          = $ll;
                    $ingreso->tipo_id              = $producto->tipo_id;
                    $ingreso->almacene_id          = 1;                           // Siempre sera 1?
                    $ingreso->escala_id            = $request->escala_id_m[$ll];
                    $ingreso->salida               = $cantidadIngresada;
                    $ingreso->estado               = 'Envio';                     //Ingreso/Envio/Salida
                    $ingreso->numero               = $numero;
                    $ingreso->numero_ingreso_envio = $numero_ingreso_envio;
                    $ingreso->fecha                = $fecha;
                    $ingreso->dispositivo          = session('dispositivo');
                    $ingreso->save();
                    // Creación de Movimiento - Ingresa a la Sucursal
                    $ingreso                       = new Movimiento();
                    $ingreso->user_id              = Auth::user()->id;
                    $ingreso->producto_id          = $ll;
                    $ingreso->tipo_id              = $producto->tipo_id;
                    $ingreso->almacen_origen_id    = 1;                           // Siempre sera 1?
                    $ingreso->escala_id            = $request->escala_id_m[$ll];
                    $ingreso->almacene_id          = $request->almacen;
                    $ingreso->ingreso              = $cantidadIngresada;
                    $ingreso->estado               = 'Envio';                     //Ingreso/Envio
                    $ingreso->numero               = $numero;
                    $ingreso->numero_ingreso_envio = $numero_ingreso_envio;
                    $ingreso->fecha                = $fecha;
                    $ingreso->dispositivo          = session('dispositivo');
                    $ingreso->save();
                }
                // Redireccionar a detalle de ingreso/envio
                return redirect('Producto/ver_ingreso/'.$numeroi);
            }
        }else{
            // dd($request->all());
            // Crear 1 registro
            if($request->producto_id)
            {
                $num_ingreso = DB::select("SELECT MAX(numero_ingreso) as nroi
                FROM movimientos");
                if (!empty($num_ingreso)) {
                    $numeroi = $num_ingreso[0]->nroi + 1;
                } else {
                    $numeroi = 1;
                }

                $fecha = date("Y-m-d H:i:s");
                $llaves = array_keys($request->producto_id);

                foreach ($llaves as $key => $ll) 
                {

                    $cantidaMayor = $request->cantidad[$ll];
                    $cantidadEscala = $request->cantidad_escala_m[$ll];
                    $cantidadIngresada = $cantidaMayor * $cantidadEscala;

                    // Buscamos al producto mediante
                    $producto = Producto::find($ll);
                    // Creación de Movimiento
                    $ingreso                 = new Movimiento();
                    $ingreso->user_id        = Auth::user()->id;
                    $ingreso->producto_id    = $ll;
                    $ingreso->tipo_id        = $producto->tipo_id;
                    $ingreso->almacene_id    = $request->almacen;
                    $ingreso->proveedor_id   = $request->proveedor;
                    $ingreso->escala_id      = $request->escala_id_m[$ll];
                    $ingreso->ingreso        = $cantidadIngresada;
                    $ingreso->estado         = 'Ingreso';
                    $ingreso->numero_ingreso = $numeroi;                    //Ingreso
                    $ingreso->fecha          = $fecha;
                    $ingreso->dispositivo    = session('dispositivo');
                    $ingreso->save();
                }
                return redirect('Producto/ver_ingreso/'.$numeroi);
            }
        }
        //Generar su reporte de envio
        return redirect('Producto/listadoIngresos');
    }

    public function ajaxMuestraTotalesAlmacen(Request $request)
    {
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
        // Buscamos al producto
        $producto = Producto::find($request->id_producto_a_reportar);
        // Procesamos reporte de producto
        $producto_reportado = new Movimiento();
        $producto_reportado->user_id = Auth::user()->id;
        $producto_reportado->producto_id = $request->id_producto_a_reportar;
        $producto_reportado->tipo_id = $producto->tipo_id;
        $producto_reportado->almacene_id = Auth::user()->almacen->id;
        $producto_reportado->salida = $request->cantidad_producto_a_reportar;
        $producto_reportado->fecha = date('Y-m-d H:i:s');
        $producto_reportado->estado = 'Defectuoso';
        $producto_reportado->descripcion = $request->descripcion_producto_a_reportar;
        $producto_reportado->dispositivo  = session('dispositivo');
        $producto_reportado->save();
        return redirect('Producto/listado');
    }

    public function habilitar(Request $request)
    {
        // Buscamos al producto
        $producto = Producto::find($request->id_producto_a_habilitar);
        // Procesamos reporte de producto
        $producto_habilitado = new Movimiento();
        $producto_habilitado->user_id = Auth::user()->id;
        $producto_habilitado->producto_id = $request->id_producto_a_habilitar;
        $producto_habilitado->tipo_id = $producto->tipo_id;
        $producto_habilitado->almacene_id = Auth::user()->almacen->id;
        $producto_habilitado->ingreso = $request->cantidad_producto_a_habilitar;
        $producto_habilitado->fecha = date('Y-m-d H:i:s');
        $producto_habilitado->estado = 'Reacondicionado';
        $producto_habilitado->descripcion = $request->descripcion_producto_a_habilitar;
        $producto_habilitado->dispositivo  = session('dispositivo');
        $producto_habilitado->save();
        return redirect('Producto/listado');
    }

    public function ingreso_excel()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $proveedores = Proveedore::get();
        return view('movimiento.ingreso_excel')->with(compact('almacenes', 'proveedores'));
    }

    public function exportar_formato_ingreso($id)
    {
        //dd($id);
        $almacen = Almacene::find($id);
        $sucursal = Almacene::find($id);
        session(['sucursal' => $sucursal]);
        $date = strtotime(date('Y-m-d H:i:s'));
        return Excel::download(new ProductosExport($almacen), "Listado_productos_$date.xlsx");
        session()->forget('sucursal');
    }

    public function importar_formato_ingreso(Request $request)
    {
        //dd($request->proveedor);
        $proveedor = Proveedore::find($request->proveedor);
        if($proveedor){
            $proveedor = $proveedor->id;
        }else{
            $proveedor = NULL;
        }
        $incluye_distribucion = $request->incluye_almacen;
        if(!$incluye_distribucion){
            $incluye_distribucion = 'No';
        }
        
        $maximo = Movimiento::max('numero');
        if ($maximo) {
            $numero = $maximo + 1;
        } else {
            $numero = 1;
        }
        $maximo_ingreso = Movimiento::max('numero_ingreso');
        if ($maximo_ingreso) {
            $numero_ingreso = $maximo_ingreso + 1;
        } else {
            $numero_ingreso = 1;
        }
        $maximo_ingreso_envio = Movimiento::max('numero_ingreso_envio');
        if ($maximo_ingreso_envio) {
            $numero_ingreso_envio = $maximo_ingreso_envio + 1;
        } else {
            $numero_ingreso_envio = 1;
        }

        $sw=0;

        $validation = Validator::make($request->all(), [
            'select_file' => 'required|mimes:xlsx|max:2048'
        ]);
        if($validation->passes())
        {
            // Creamos variables de sesión para pasar al import
            session(['proveedor' => $proveedor]);
            session(['incluye_distribucion' => $incluye_distribucion]);
            session(['numero' => $numero]);
            session(['numero_ingreso' => $numero_ingreso]);
            session(['numero_ingreso_envio' => $numero_ingreso_envio]);
            $file = $request->file('select_file');
            Excel::import(new IngresoImport, $file); 
            // Eliminarmos variables de sesión
            session()->forget('proveedor');
            session()->forget('incluye_distribucion');
            session()->forget('numero');
            session()->forget('numero_ingreso');
            session()->forget('numero_ingreso_envio');
            
            $sw=1;

            return response()->json([
                'message' => 'Importacion realizada con exito',
                //'numero' => $pedido->id,
                'numero' => $numero_ingreso,
                'sw' => $sw
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
