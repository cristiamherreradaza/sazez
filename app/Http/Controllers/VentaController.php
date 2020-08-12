<?php

namespace App\Http\Controllers;

use App\Pago;
use App\User;
use App\Combo;
use App\Grupo;
use App\Venta;
use App\Precio;
use DataTables;
use App\Almacene;
use App\Producto;
use App\Movimiento;
use App\Cotizacione;
use App\CombosProducto;
use App\Configuracione;
use App\VentasProducto;
use Illuminate\Http\Request;
use App\CotizacionesProducto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function nuevo()
    {
        // dd($productos);
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
        $hoy = date('Y-m-d');
        $arrayPromociones = [];
        $almacenes = Almacene::get();
        $grupos = Grupo::all();
        $clientes = User::where('rol', 'Cliente')
                    ->get();

        $promociones = Combo::where('fecha_inicio', '<=', $hoy) 
        ->where('fecha_final', '>=', $hoy)
        ->get();

        foreach ($promociones as $key => $p) {
            $totalPromocion = 0;
            $promocionProducto = CombosProducto::where('combo_id', $p->id)->get();
            foreach ($promocionProducto as $pp) {
                $totalPromocion += $pp->precio;
            }
            $arrayPromociones[$key]['id']=$p->id;
            $arrayPromociones[$key]['nombre']=$p->nombre;
            $arrayPromociones[$key]['total']=$totalPromocion;
        }
        // dd($arrayPromociones);

        return view('venta.tienda')->with(compact('almacenes', 'clientes', 'grupos', 'arrayPromociones'));
    }

    public function mayorista()
    {
        $almacenes = Almacene::get();
        $clientes = User::where('rol', 'Cliente')
                    ->get();
        return view('venta.mayorista')->with(compact('almacenes', 'clientes'));
    }

    public function ajaxBuscaProductoTienda(Request $request)
    {
        $almacen_id = Auth::user()->almacen_id;
        $productos = Movimiento::select(
                            'productos.id',
                            'productos.codigo as codigo',
                            'productos.nombre as nombre',
                            'marcas.nombre as marca',
                            'tipos.nombre as tipo',
                            'productos.modelo as modelo',
                            'productos.colores as colores'
                        )
                    ->where('movimientos.almacene_id', $almacen_id)
                    ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
                    ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
                    ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
                    ->where('productos.nombre', 'like', "%$request->termino%")
                    ->orWhere('productos.codigo', 'like', "%$request->termino%")
                    ->groupBy('productos.id')
                    ->limit(8)
                    ->get();
        // dd($productos);
        return view('venta.ajaxBuscaProductoTienda')->with(compact('productos'));
    }

    public function guardaVenta(Request $request)
    {
        if ($request->pagoContado != "on") 
        {
            $pagoCredito = 'Si';
            $saldoVenta = $request->cambioVenta;
        }else{
            $pagoCredito = 'No';
            $saldoVenta = 0;
        }
        // dd($request->all());
        $errorVenta = 0;
        $mensajeError = "";
        //cremaos la venta
        $venta              = new Venta();
        $venta->user_id     = Auth::user()->id;
        $venta->almacene_id = Auth::user()->almacen_id;
        $venta->cliente_id  = $request->cliente_id;
        $venta->fecha       = $request->fecha;
        $venta->saldo       = $saldoVenta;
        $venta->credito     = $pagoCredito;
        $venta->total       = $request->totalCompra;
        $venta->save();
        $venta_id = $venta->id;

        // guardamos los datos de la promocion
        if($request->has('promoId'))
        {
            $llavesPromos = array_keys($request->promoId);
            foreach ($llavesPromos as $key => $llpr) {
                $productosPromocion = CombosProducto::where('combo_id', $llpr)->get();
                foreach ($productosPromocion as $ppr) {

                    if($ppr->cantidad > 1 ){
                        $precioProductoCombo = intval($ppr->precio/$ppr->cantidad);
                    }else{
                        $precioProductoCombo = $ppr->precio;
                    }

                    $cantidadProductosPromo = $request->cantidadPromo[$llpr] * $ppr->cantidad;

                    // vemos la cantida de stock en el almacen
                    $cantidadTotalProducto = Movimiento::select(DB::raw('SUM(ingreso) - SUM(salida) as total'))
                        ->where('producto_id', $ppr->producto_id)
                        ->where('almacene_id', auth()->user()->almacen_id)
                        ->first();
                    $totalVerificar = $cantidadTotalProducto->total - $cantidadProductosPromo;

                    if ($totalVerificar < 0) {
                        $errorVenta = 1;
                        $mensajeError = 'No tienes suficientes productos para tu promocion';
                    }else{
                        // guardamos los productos de la promocion
                        $productosPr                 = new VentasProducto();
                        $productosPr->user_id        = Auth::user()->id;
                        $productosPr->producto_id    = $ppr->producto_id;
                        $productosPr->venta_id       = $venta_id;
                        $productosPr->combo_id       = $ppr->combo_id;
                        $productosPr->precio_venta   = $precioProductoCombo;
                        $productosPr->precio_cobrado = $precioProductoCombo;
                        $productosPr->cantidad       = $cantidadProductosPromo;
                        $productosPr->fecha          = $request->fecha;
                        $productosPr->save();

                        // guardamos lo movimientos de la promocion
                        $movimientoPromocion               = new Movimiento();
                        $movimientoPromocion->user_id      = Auth::user()->id;
                        $movimientoPromocion->almacene_id  = Auth::user()->almacen_id;
                        $movimientoPromocion->venta_id     = $venta_id;
                        $movimientoPromocion->producto_id  = $ppr->producto_id;
                        $movimientoPromocion->precio_venta = $precioProductoCombo;
                        $movimientoPromocion->salida       = $cantidadProductosPromo;
                        $movimientoPromocion->estado       = 'Venta';
                        $movimientoPromocion->save();
                    }

                }
            }
        }

        // guardamos todos los items de la venta por unidad
        if ($request->has('precio')) {
            $llaves = array_keys($request->precio);
            foreach ($llaves as $key => $ll) {

                // verificamos la cantidad de productos en el almancen
                $cantidadTotalProducto = Movimiento::select(DB::raw('SUM(ingreso) - SUM(salida) as total'))
                    ->where('producto_id', $ll)
                    ->where('almacene_id', auth()->user()->almacen_id)
                    ->first();
                $totalVerificar = $cantidadTotalProducto->total - $request->cantidad[$ll];

                if ($totalVerificar < 0) {
                    $errorVenta = 1;
                    $mensajeError = 'No tienes suficientes productos para tu venta';
                } else {

                    // guardamos los productos a vender
                    $productos                 = new VentasProducto();
                    $productos->user_id        = Auth::user()->id;
                    $productos->producto_id    = $ll;
                    $productos->venta_id       = $venta_id;
                    $productos->precio_venta   = $request->precio_venta[$ll];
                    $productos->precio_cobrado = $request->precio[$ll];
                    $productos->cantidad       = $request->cantidad[$ll];
                    $productos->fecha          = $request->fecha;
                    $productos->save();

                    // guardamos lo movimientos de la venta
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
            }
        }

        // guardamos todos los items de la venta por mayor
        if($request->has('precio_m')){
            $llavesMayor = array_keys($request->precio_m);
            foreach ($llavesMayor as $key => $llm) {

                $cantidadVendida = 0;
                $cantidaMayor = $request->cantidad_m[$llm];
                $cantidadEscala = $request->cantidad_escala_m[$llm];

                // verificamos la cantidad de productos en el almancen
                $cantidadTotalProducto = Movimiento::select(DB::raw('SUM(ingreso) - SUM(salida) as total'))
                    ->where('producto_id', $llm)
                    ->where('almacene_id', auth()->user()->almacen_id)
                    ->first();
                $totalVerificar = $cantidadTotalProducto->total - $request->cantidad_m[$ll];

                if ($totalVerificar < 0) {
                    $errorVenta = 1;
                    $mensajeError = 'No tienes suficientes productos para tu venta al por mayor';
                } else {
                    $productosMayor                       = new VentasProducto();
                    $productosMayor->user_id              = Auth::user()->id;
                    $productosMayor->producto_id          = $llm;
                    $productosMayor->venta_id             = $venta_id;
                    $productosMayor->escala_id            = $request->escala_id_m[$llm];
                    $productosMayor->precio_venta_mayor   = $request->precio_venta_m[$llm];
                    $productosMayor->precio_cobrado_mayor = $request->precio_m[$llm];
                    $productosMayor->cantidad             = $request->cantidad_m[$llm];
                    $productosMayor->fecha                = $request->fecha;
                    $productosMayor->save();

                    $cantidadVendida = $cantidaMayor * $cantidadEscala;

                    $movimientoMayor               = new Movimiento();
                    $movimientoMayor->user_id      = Auth::user()->id;
                    $movimientoMayor->almacene_id  = Auth::user()->almacen_id;
                    $movimientoMayor->venta_id     = $venta_id;
                    $movimientoMayor->escala_id    = $request->escala_id_m[$llm];
                    $movimientoMayor->producto_id  = $llm;
                    $movimientoMayor->precio_venta = $request->precio_m[$llm];
                    $movimientoMayor->salida       = $cantidadVendida;
                    $movimientoMayor->estado       = 'Venta';
                    $movimientoMayor->save();
                }
            }

        }

        if ($request->pagoContado != "on") 
        {
            $pagoARegistrar = $request->efectivo;
        }else{
            $pagoARegistrar = $request->totalCompra;
        }

        // guardamos el pago
        $pago             = new Pago();
        $pago->user_id    = Auth::user()->id;
        $pago->cliente_id = $request->cliente_id;
        $pago->venta_id   = $venta_id;
        $pago->fecha      = $request->fecha;
        $pago->importe    = $pagoARegistrar;
        $pago->save();

        if ($errorVenta == 1) {
                    // elimnamos la venta
            $venta = Venta::find($venta_id);
            $venta->delete();

            // eliminamos los datos de la venta
            Movimiento::where('venta_id', $venta_id)->delete();
            VentasProducto::where('venta_id', $venta_id)->delete();
        }

        return response()->json([
            'errorVenta' => $errorVenta,
            'mensajeError'  => $mensajeError,
            'ventaId'  => $venta_id
        ]);
    }

    public function listado()
    {
        return view('venta.listado');
    }

    public function ajax_listado()
    {
        $almacen = Auth::user()->almacen_id;
        $ventas = Venta::select(
                'ventas.id', 
                'almacenes.nombre as almacene', 
                'users.name as user', 
                'ventas.total',
                'ventas.saldo',
                'ventas.fecha'
            )
            ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
            ->leftJoin('users', 'ventas.cliente_id', '=', 'users.id')
            ->where('ventas.almacene_id', $almacen);

        return Datatables::of($ventas)
            ->addColumn('action', function ($ventas) {
                return '<button onclick="muestra(' . $ventas->id . ')" class="btn btn-info" title="Ver detalle"><i class="fas fa-eye"></i></button>
                        <button onclick="imprimir(' .$ventas->id. ')" class="btn btn-primary" title="Imprimir garantia"><i class="fas fa-print"></i> </button>
                        <button onclick="pagos(' .$ventas->id. ')" class="btn btn-success" title="Imprimir garantia"><i class="fas fa-donate"></i> </button>';
            })
            ->make(true);    
    }

    public function muestra(Request $request, $ventaId)
    {
        $datosVenta = Venta::find($ventaId);
        $productosVenta = VentasProducto::where('venta_id', $ventaId)->get();
        $opcionesEliminaVenta = Configuracione::where('descripcion', 'comboEliminaVenta')->get();
        $opcionesCambiaProductoVenta = Configuracione::where('descripcion', 'comboCambiaProductoVenta')->get();
        $cambiados = Movimiento::withTrashed()
                        ->where('venta_id', $ventaId)
                        ->where('estado', 'Devuelto')
                        ->get();
        // dd($datosVenta);
        return view('venta.muestra')->with(compact('datosVenta', 'productosVenta', 'opcionesEliminaVenta', 'opcionesCambiaProductoVenta', 'cambiados'));
    }

    public function imprimir($venta_id)
    {
        $venta = Venta::find($venta_id);
        $productos_venta = VentasProducto::where('venta_id', $venta_id)->get();
        return view('venta.imprime')->with(compact('venta', 'productos_venta'));
    }

    public function elimina(Request $request)
    {
        $venta = Venta::find($request->ventaId);
        $venta->descripcion = $request->opcion_elimina;
        $venta->save();

        // elimnamos la venta
        $venta = Venta::find($request->ventaId);
        $venta->delete();

        // eliminamos los datos de la venta
        Movimiento::where('venta_id', $request->ventaId)->delete();
        VentasProducto::where('venta_id', $request->ventaId)->delete();

        return redirect('Venta/listado');
        // dd($request->all());
    }

    public function ajaxCambiaProducto(Request $request)
    {

        $ventaProducto = VentasProducto::find($request->ventaProductoId);

        if ($ventaProducto->precio_venta_mayor > 0){
            $cantidadPaquete = $ventaProducto->escala->minimo;
        }else{
            $cantidadPaquete = 1;
        }
        $cantidadMultiplicada = $cantidadPaquete*$request->cantidad;

        $consultaMovimiento = Movimiento::where('venta_id', $request->ventaId) 
                                ->where('producto_id', $request->productoId)
                                ->where('escala_id', $ventaProducto->escala_id)
                                ->first();

        $cantidadSalida = $consultaMovimiento->salida;

        $nuevaCantidad = $cantidadSalida-$cantidadMultiplicada;
        $movimientoId = $consultaMovimiento->id;

        $editaMovimiento = Movimiento::find($movimientoId);
        $editaMovimiento->salida      = $nuevaCantidad;
        $editaMovimiento->devuelto    = 'Si';
        $editaMovimiento->save();

        // registramos la devolucion
        $nuevoMovimiento               = new Movimiento();
        $nuevoMovimiento->user_id      = $consultaMovimiento->user_id;
        $nuevoMovimiento->producto_id  = $consultaMovimiento->producto_id;
        $nuevoMovimiento->almacene_id  = $consultaMovimiento->almacene_id;
        $nuevoMovimiento->venta_id     = $consultaMovimiento->venta_id;
        $nuevoMovimiento->precio_venta = $consultaMovimiento->precio_venta;
        $nuevoMovimiento->descripcion  = $request->opcionCambia;
        $nuevoMovimiento->ingreso      = $cantidadMultiplicada;
        $nuevoMovimiento->deleted_at   = date("Y-m-d H:i:s");
        $nuevoMovimiento->estado       = "Devuelto";
        $nuevoMovimiento->save();

        // registramos la salida
        $nuevoMovimientoSalida               = new Movimiento();
        $nuevoMovimientoSalida->user_id      = $consultaMovimiento->user_id;
        $nuevoMovimientoSalida->producto_id  = $consultaMovimiento->producto_id;
        $nuevoMovimientoSalida->almacene_id  = $consultaMovimiento->almacene_id;
        $nuevoMovimientoSalida->venta_id     = $consultaMovimiento->venta_id;
        $nuevoMovimientoSalida->precio_venta = $consultaMovimiento->precio_venta;
        $nuevoMovimientoSalida->salida       = $cantidadMultiplicada;
        $nuevoMovimientoSalida->estado       = $consultaMovimiento->estado;
        $nuevoMovimientoSalida->save();

        return response()->json([
            'ventaId' => $request->ventaId
        ]);

        // return redirect("Venta/muestra/$request->ventaId");
        // $productoVenta = VentasProducto::where('venta_id', $request->productoId);
    }

    public function muestraPromoCombo(Request $request)
    {

    }

}