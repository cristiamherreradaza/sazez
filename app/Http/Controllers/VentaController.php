<?php

namespace App\Http\Controllers;

use App\Pago;
use App\User;
use App\Combo;
use App\Grupo;
use App\Venta;
use App\Precio;
use App\Tipo;
use App\Marca;
use DataTables;
use App\Empresa;
use App\Factura;
use App\Almacene;
use App\Producto;
use App\Movimiento;
use App\Parametros;
use App\Cotizacione;
use CodigoControlV7;
use App\CombosProducto;
use App\Configuracione;
use App\VentasProducto;
use Illuminate\Http\Request;
use App\CotizacionesProducto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $tipos = Tipo::get();
        $marcas = Marca::get();

        $arrayPromociones = [];
        $almacenes = Almacene::get();
        $grupos = Grupo::all();
        $clientes = User::where('rol', 'Cliente')
                    ->orWhere('rol', 'Mayorista')
                    ->get();

        $promociones = Combo::where('fecha_inicio', '<=', $hoy)
        ->where('fecha_final', '>=', $hoy)
        ->get();

        foreach ($promociones as $key => $p) {
            $totalPromocion = 0;
            $promocionProducto = CombosProducto::where('combo_id', $p->id)->get();
            foreach ($promocionProducto as $pp) {
                $totalPromocion += $pp->precio*$pp->cantidad;
            }
            $arrayPromociones[$key]['id']       = $p->id;
            $arrayPromociones[$key]['nombre']   = $p->nombre;
            $arrayPromociones[$key]['total']    = $totalPromocion;
        }
        // dd($arrayPromociones);

        return view('venta.tienda')->with(compact('almacenes', 'clientes', 'grupos', 'arrayPromociones', 'marcas', 'tipos'));
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
        // dd($request->all());
        if($request->tipo != null){
            $tipo = $request->tipo;
        }else{
            $tipo = '%';
        }

        if($request->marca != null){
            $marca = $request->marca;
        }else{
            $marca = '%';
        }

        // dd($tipo);

        $almacen_id = Auth::user()->almacen_id;
        $productos = Movimiento::select(
                            'productos.id',
                            // 'tipos.id',
                            'productos.codigo as codigo',
                            'productos.nombre as nombre',
                            'marcas.nombre as marca',
                            'tipos.nombre as tipo',
                            'productos.modelo as modelo',
                            'productos.colores as colores'
                        )
                    ->where('movimientos.almacene_id', $almacen_id)
                    ->where('tipos.id', 'like',  $tipo)
                    ->where('marcas.id', 'like',  $marca)
                    ->where('productos.nombre', 'like', "%$request->termino%")
                    ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
                    ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
                    ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
                    ->groupBy('productos.id')
                    ->limit(10)
                    ->get();
                    
                    // ->orWhere('productos.codigo', 'like', "%$request->termino%")
                   
                    // ->get();

        // $productos->get();

        // dd($productos);
        return view('venta.ajaxBuscaProductoTienda')->with(compact('productos'));
    }

    public function guardaVenta(Request $request)
    {
        $facturaId = null;
        // preguntamos si el pago es al contado o credito
        if ($request->pagoContado != "on")
        {
            $pagoCredito = 'Si';
            $saldoVenta = $request->cambioVenta;
        }else{
            $pagoCredito = 'No';
            $saldoVenta = 0;
        }
        $errorVenta = 0;
        $mensajeError = "";

        $ultimoParametro = Parametros::where('almacene_id', Auth::user()->almacen_id)
            ->latest()
            ->first();

        // preguntamos si la venta ya tiene una factura creada
        if ($ultimoParametro != null && $ultimoParametro->estado == 'Activo') {
            // procesamos para el nit del cliente
            $buscaNitCliente = User::where('nit', $request->nit_cliente)->first();

            // verificamos si es publico general para guardar un nuevo cliente
            if ($buscaNitCliente == null) {
                // creamos un correo temporal
                $correoTemporal = date("YmdHis") . '@notiene.com';

                $cliente               = new User();
                $cliente->name         = $request->razon_social_cliente;
                $cliente->rol          = 'Cliente';
                $cliente->email        = $correoTemporal;
                $cliente->password     = Hash::make('123456789');
                $cliente->nit          = $request->nit_cliente;
                $cliente->razon_social = $request->razon_social_cliente;
                $cliente->save();
                $clienteId = $cliente->id;
            } else {
                // modificamos el nit y razon social del cliente
                $cliente               = User::find($buscaNitCliente->id);
                $cliente->nit          = $request->nit_cliente;
                $cliente->razon_social = $request->razon_social_cliente;
                $cliente->save();
                $clienteId = $cliente->id;
            }
            // fin del registro del cliente
        }else{
            $clienteId = $request->cliente_id;
        }
        
        // creamos la venta
        $venta              = new Venta();
        $venta->user_id     = Auth::user()->id;
        $venta->almacene_id = Auth::user()->almacen_id;
        $venta->cliente_id  = $clienteId;
        $venta->fecha       = $request->fecha;
        $venta->saldo       = $saldoVenta;
        $venta->credito     = $pagoCredito;
        $venta->total       = $request->totalCompra;
        $venta->save();
        $venta_id = $venta->id;

        $fechaHoraVenta = date("Y-m-d H:i:s");
        // guardamos los productos de la promcion
        if($request->has('promoId'))
        {
            $llavesPromos = array_keys($request->promoId);
            foreach ($llavesPromos as $key => $llpr) {
                $productosPromocion = CombosProducto::where('combo_id', $llpr)->get();
                foreach ($productosPromocion as $ppr) {

                    $precioProductoCombo = $ppr->precio;
                    $cantidadProductosPromo = $request->cantidadPromo[$llpr] * $ppr->cantidad;

                    // vemos la cantida de stock en el almacen
                    $cantidadTotalProducto = Movimiento::select(DB::raw('SUM(ingreso) - SUM(salida) as total'))
                        ->where('producto_id', $ppr->producto_id)
                        ->where('almacene_id', Auth::user()->almacen_id)
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
                        $productosPr->tipo_id        = $ppr->producto->tipo_id;
                        $productosPr->combo_id       = $ppr->combo_id;
                        $productosPr->factura_id     = $facturaId;
                        $productosPr->precio_venta   = $precioProductoCombo;
                        $productosPr->precio_cobrado = $precioProductoCombo;
                        $productosPr->cantidad       = $cantidadProductosPromo;
                        $productosPr->fecha          = $request->fecha;
                        $productosPr->fecha_garantia = Carbon::now()->addDay($ppr->producto->dias_garantia);
                        $productosPr->save();

                        // guardamos lo movimientos de la promocion
                        $movimientoPromocion               = new Movimiento();
                        $movimientoPromocion->user_id      = Auth::user()->id;
                        $movimientoPromocion->almacene_id  = Auth::user()->almacen_id;
                        $movimientoPromocion->venta_id     = $venta_id;
                        $movimientoPromocion->tipo_id      = $ppr->producto->tipo_id;
                        $movimientoPromocion->producto_id  = $ppr->producto_id;
                        $movimientoPromocion->precio_venta = $precioProductoCombo;
                        $movimientoPromocion->salida       = $cantidadProductosPromo;
                        $movimientoPromocion->estado       = 'Venta';
                        $movimientoPromocion->fecha        = $fechaHoraVenta;
                        $movimientoPromocion->dispositivo  = session('dispositivo');
                        $movimientoPromocion->save();

                        // pregutamos si se desea enviar los productos al mayorista
                        if($request->envioMayorista == "Si"){
                            $datosMayorista = User::find($request->cliente_id);
                            // echo "si enviar";
                            // enviamos productos al manyorista
                            $movimientoPromocion                    = new Movimiento();
                            $movimientoPromocion->user_id           = Auth::user()->id;
                            $movimientoPromocion->almacene_id       = $datosMayorista->almacen_id;
                            $movimientoPromocion->almacen_origen_id = Auth::user()->almacen_id;
                            $movimientoPromocion->venta_id          = $venta_id;
                            $movimientoPromocion->tipo_id           = $ppr->producto->tipo_id;
                            $movimientoPromocion->producto_id       = $ppr->producto_id;
                            $movimientoPromocion->precio_venta      = $precioProductoCombo;
                            $movimientoPromocion->ingreso           = $cantidadProductosPromo;
                            $movimientoPromocion->estado            = 'Transferencia Mayorista';
                            $movimientoPromocion->fecha             = $fechaHoraVenta;
                            $movimientoPromocion->dispositivo       = session('dispositivo');
                            $movimientoPromocion->save();
                        }
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
                    ->where('almacene_id', Auth::user()->almacen_id)
                    ->first();
                $totalVerificar = $cantidadTotalProducto->total - $request->cantidad[$ll];

                // obtenemos datos del producto
                $datosProductoUnidad = Producto::find($ll);

                // preguntamos si tiene productos para la venta
                if ($totalVerificar < 0) {
                    $errorVenta = 1;
                    $mensajeError = 'No tienes suficientes productos para tu venta';
                } else {

                    // guardamos los productos a vender
                    $productos                 = new VentasProducto();
                    $productos->user_id        = Auth::user()->id;
                    $productos->producto_id    = $ll;
                    $productos->venta_id       = $venta_id;
                    $productos->factura_id     = $facturaId;
                    $productos->tipo_id        = $datosProductoUnidad->tipo_id;
                    $productos->precio_venta   = $request->precio_venta[$ll];
                    $productos->precio_cobrado = $request->precio[$ll];
                    $productos->cantidad       = $request->cantidad[$ll];
                    $productos->fecha          = $request->fecha;
                    $productos->fecha_garantia = Carbon::now()->addDay($datosProductoUnidad->dias_garantia);
                    $productos->save();

                    // guardamos lo movimientos de la venta
                    $movimiento               = new Movimiento();
                    $movimiento->user_id      = Auth::user()->id;
                    $movimiento->almacene_id  = Auth::user()->almacen_id;
                    $movimiento->venta_id     = $venta_id;
                    $movimiento->producto_id  = $ll;
                    $movimiento->tipo_id      = $datosProductoUnidad->tipo_id;
                    $movimiento->precio_venta = $request->precio[$ll];
                    $movimiento->salida       = $request->cantidad[$ll];
                    $movimiento->estado       = 'Venta';
                    $movimiento->fecha        = $fechaHoraVenta;
                    $movimiento->dispositivo  = session('dispositivo');
                    $movimiento->save();

                     // pregutamos si se desea enviar los productos al mayorista
                    if($request->envioMayorista == "Si"){
                        $datosMayorista = User::find($request->cliente_id);
                        // echo "si enviar";
                        // enviamos productos al manyorista
                        $movimiento                    = new Movimiento();
                        $movimiento->user_id           = Auth::user()->id;
                        $movimiento->almacene_id       = $datosMayorista->almacen_id;
                        $movimiento->venta_id          = $venta_id;
                        $movimiento->tipo_id           = $datosProductoUnidad->tipo_id;
                        $movimiento->almacen_origen_id = Auth::user()->almacen_id;
                        $movimiento->producto_id       = $ll;
                        $movimiento->precio_venta      = $request->precio[$ll];
                        $movimiento->ingreso           = $request->cantidad[$ll];
                        $movimiento->estado            = 'Transferencia Mayorista';
                        $movimiento->fecha             = $fechaHoraVenta;
                        $movimiento->dispositivo       = session('dispositivo');
                        $movimiento->save();
                    }
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

                $datosProductosMayor = Producto::find($llm);

                $cantidadParaVerificar = $cantidaMayor * $cantidadEscala;

                // verificamos la cantidad de productos en el almancen
                $cantidadTotalProducto = Movimiento::select(DB::raw('SUM(ingreso) - SUM(salida) as total'))
                    ->where('producto_id', $llm)
                    ->where('almacene_id', Auth::user()->almacen_id)
                    ->first();
                $totalVerificar = $cantidadTotalProducto->total - $cantidadParaVerificar;

                if ($totalVerificar < 0) {
                    $errorVenta = 1;
                    $mensajeError = 'No tienes suficientes productos para tu venta al por mayor';
                } else {
                    $productosMayor                       = new VentasProducto();
                    $productosMayor->user_id              = Auth::user()->id;
                    $productosMayor->producto_id          = $llm;
                    $productosMayor->venta_id             = $venta_id;
                    $productosMayor->escala_id            = $request->escala_id_m[$llm];
                    $productosMayor->factura_id           = $facturaId;
                    $productosMayor->tipo_id              = $datosProductosMayor->tipo_id;
                    $productosMayor->precio_venta_mayor   = $request->precio_venta_m[$llm];
                    $productosMayor->precio_cobrado_mayor = $request->precio_m[$llm];
                    $productosMayor->cantidad             = $request->cantidad_m[$llm];
                    $productosMayor->fecha                = $request->fecha;
                    $productosMayor->fecha_garantia       = Carbon::now()->addDay($datosProductosMayor->dias_garantia);
                    $productosMayor->save();

                    $cantidadVendida = $cantidaMayor * $cantidadEscala;

                    $movimientoMayor               = new Movimiento();
                    $movimientoMayor->user_id      = Auth::user()->id;
                    $movimientoMayor->almacene_id  = Auth::user()->almacen_id;
                    $movimientoMayor->venta_id     = $venta_id;
                    $movimientoMayor->escala_id    = $request->escala_id_m[$llm];
                    $movimientoMayor->producto_id  = $llm;
                    $movimientoMayor->tipo_id      = $datosProductosMayor->tipo_id;
                    $movimientoMayor->precio_venta = $request->precio_m[$llm];
                    $movimientoMayor->salida       = $cantidadVendida;
                    $movimientoMayor->estado       = 'Venta';
                    $movimientoMayor->fecha        = $fechaHoraVenta;
                    $movimientoMayor->dispositivo  = session('dispositivo');
                    $movimientoMayor->save();

                    // pregutamos si se desea enviar los productos al mayorista
                    if($request->envioMayorista == "Si"){
                        $datosMayorista = User::find($request->cliente_id);
                        // echo "si enviar";
                        // enviamos productos al manyorista
                        $movimientoMayor                    = new Movimiento();
                        $movimientoMayor->user_id           = Auth::user()->id;
                        $movimientoMayor->almacene_id       = $datosMayorista->almacen_id;
                        $movimientoMayor->venta_id          = $venta_id;
                        $movimientoMayor->escala_id         = $request->escala_id_m[$llm];
                        $movimientoMayor->producto_id       = $llm;
                        $movimientoMayor->tipo_id           = $datosProductosMayor->tipo_id;
                        $movimientoMayor->almacen_origen_id = Auth::user()->almacen_id;
                        $movimientoMayor->precio_venta      = $request->precio_m[$llm];
                        $movimientoMayor->ingreso           = $cantidadVendida;
                        $movimientoMayor->estado            = 'Tranferencia Mayorista';
                        $movimientoMayor->fecha             = $fechaHoraVenta;
                        $movimientoMayor->dispositivo       = session('dispositivo');
                        $movimientoMayor->save();
                    }

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

            // eliminamos los datos relacionados de la venta
            Movimiento::where('venta_id', $venta_id)->delete();
            VentasProducto::where('venta_id', $venta_id)->delete();
            Pago::where('venta_id', $venta_id)->delete();
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
                        'ventas.cliente_id',
                        'ventas.id',
                        'almacenes.nombre as almacene',
                        'usuario.name as nombre_usuario',
                        'users.name as user',
                        'ventas.total',
                        'ventas.saldo',
                        'ventas.fecha'
                    )
                    ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('users', 'ventas.cliente_id', '=', 'users.id')
                    ->leftJoin('users as usuario', 'ventas.user_id', '=', 'usuario.id');
        if(Auth::user()->perfil_id != 1){
            $ventas->where('ventas.user_id', Auth::user()->id);
            $ventas->where('ventas.almacene_id', $almacen);
        }
        return Datatables::of($ventas)
            ->addColumn('action', function ($ventas) {
                if($ventas->saldo > 0){
                    return '<button onclick="muestra(' . $ventas->id . ')" class="btn btn-info" title="Ver detalle"><i class="fas fa-eye"></i></button>
                    <button onclick="imprimir(' .$ventas->id. ')" class="btn btn-primary" title="Imprimir garantia"><i class="fas fa-print"></i> </button>
                    <button onclick="pagos(' .$ventas->id. ')" class="btn btn-success" title="Pagos Venta"><i class="fas fa-donate"></i> </button>
                    <button onclick="deuda_total(' .$ventas->cliente_id. ')" class="btn btn-dark" title="Deudas"><i class="fas fa-donate"></i> </button>';
                }else{
                    return '<button onclick="muestra(' . $ventas->id . ')" class="btn btn-info" title="Ver detalle"><i class="fas fa-eye"></i></button>
                    <button onclick="imprimir(' .$ventas->id. ')" class="btn btn-primary" title="Imprimir garantia"><i class="fas fa-print"></i> </button>
                    <button onclick="pagos(' .$ventas->id. ')" class="btn btn-success" title="Pagos Venta"><i class="fas fa-donate"></i> </button>';
                }

            })
            ->make(true);
    }

    public function muestra(Request $request, $ventaId)
    {
        // dd($ventaId);
        $datosVenta = Venta::where('id', $ventaId)->first();
        // dd($datosVenta);
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
    	Pago::where('venta_id', $request->ventaId)->delete();

        // Anulamos la factura
    	$eliminaFactura = Factura::where('venta_id', $request->ventaId)->first();
    	$eliminaFactura->estado = "Anulado";
    	$eliminaFactura->save();

    	return redirect('Venta/listado');
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
        $nuevoMovimiento->fecha        = date("Y-m-d H:i:s");
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
        $nuevoMovimientoSalida->fecha        = date("Y-m-d H:i:s");
        $nuevoMovimientoSalida->estado       = $consultaMovimiento->estado;
        $nuevoMovimientoSalida->save();

        return response()->json([
            'ventaId' => $request->ventaId
        ]);

        // return redirect("Venta/muestra/$request->ventaId");
        // $productoVenta = VentasProducto::where('venta_id', $request->productoId);
    }

    public function ajaxBuscaNitCliente(Request $request)
    {
        $encontrado = 'No';
        $cliente    = array();
        $buscaNit   = User::where('nit', $request->nitCliente)->first();

        if($buscaNit != null)
        {
            $encontrado = 'Si';
            $cliente['id']=$buscaNit->id;
            $cliente['nombre']=$buscaNit->name;
            $cliente['almacene_id']=$buscaNit->almacen_id;
            $cliente['nit']=$buscaNit->nit;
            $cliente['razon_social']=$buscaNit->razon_social;

        }else{
            $encontrado = 'No';
        }

        return response()->json([
            'encontrado'   => $encontrado,
            'datosCliente' => json_encode($cliente),
        ]);
    }

    public function imprimeFactura($ventaId = null)
    {
        $datosVenta = Venta::where('id', $ventaId)->first();
        $datosEmpresa = Empresa::where('almacene_id', $datosVenta->almacene_id)->first();
        $productosVenta = VentasProducto::where('venta_id', $ventaId)->get();

        // dd($datosVenta->cliente->nit);
        // verficamos si tiene datos para facturar
        $ultimoParametro = Parametros::where('almacene_id', Auth::user()->almacen_id)
            ->latest()
            ->first();

        // preguntamos si la venta ya tiene una factura creada
        if ($datosVenta->factura_id == null) {
            if($ultimoParametro != null && $ultimoParametro->estado == 'Activo')
            {
                // tramemos los parametros de la facturacion
                $parametrosFactura = Parametros::where('estado', 'Activo')
                                    ->where('almacene_id', Auth::user()->almacen_id)
                                    ->first();

                // obtenemos el ultimo numero de factura
                $ultimoNumeroFactura = Factura::latest()
                                        ->where('almacene_id', Auth::user()->almacen_id)
                                        ->first();

                if($ultimoNumeroFactura == null){
                    $nuevoNumeroFactura = $parametrosFactura->numero_factura;
                }else{
                    $nuevoNumeroFactura = $ultimoNumeroFactura->numero_factura+1;
                }

                $fechaParaCodigo = str_replace("-", "", $datosVenta->fecha);

                // generamos el codigo de control
                $facturador          = new CodigoControlV7();
                $numero_autorizacion = $parametrosFactura->numero_autorizacion;
                $numero_factura      = $nuevoNumeroFactura;
                $nit_cliente         = $datosVenta->cliente->nit;
                $fecha_compra        = $fechaParaCodigo;
                $monto_compra        = round($datosVenta->total, 0, PHP_ROUND_HALF_UP);
                $clave               = $parametrosFactura->llave_dosificacion;
                $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);

                // creamos la factura
                $nuevaFactura                      = new Factura();
                $nuevaFactura->user_id             = Auth::user()->id;
                $nuevaFactura->almacene_id         = Auth::user()->almacen_id;
                $nuevaFactura->cliente_id          = $datosVenta->cliente->id;
                $nuevaFactura->numero_autorizacion = $parametrosFactura->numero_autorizacion;
                $nuevaFactura->numero_factura      = $nuevoNumeroFactura;
                $nuevaFactura->nit_cliente         = $datosVenta->cliente->nit;
                $nuevaFactura->venta_id            = $datosVenta->id;
                $nuevaFactura->fecha_compra        = $datosVenta->fecha;
                $nuevaFactura->fecha_limite        = $parametrosFactura->fecha_limite;
                $nuevaFactura->monto_compra        = round($datosVenta->total, 0, PHP_ROUND_HALF_UP);
                $nuevaFactura->clave               = $parametrosFactura->llave_dosificacion;
                $nuevaFactura->codigo_control      = $codigoControl;
                $nuevaFactura->save();
                $facturaId = $nuevaFactura->id;

                $datosFactura = Factura::where("id", $nuevaFactura->id)->first();
                // modificamos la venta para la factura
                $venta = Venta::find($datosVenta->id);
                $venta->factura_id = $facturaId;
                $venta->save();
                
            }
        } else {
            $datosFactura = Factura::where("id", $datosVenta->factura_id)->first();
        }

        // dd($datosVenta);

        return view('venta.imprimeFactura')->with(compact('datosVenta', 'productosVenta', 'datosFactura', 'datosEmpresa'));
    }

    public function infoDispositivo()
    {
        $dispositivo = session('dispositivo');
        dd($dispositivo);
    }

    public function ventasQr()
    {
        return view('venta.ventasQr');
    }

}