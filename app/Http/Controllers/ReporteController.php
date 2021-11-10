<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Almacene;
use App\Cupone;
use App\CuponesCobrado;
use App\Movimiento;
use App\Proveedore;
use App\Producto;
use App\Tipo;
use App\User;
use App\VentasProducto;
use App\Venta;
use App\Marca;

class ReporteController extends Controller
{
    public function reporte_tienda()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('reporte.reporte_tienda')->with(compact('almacenes'));
    }

    public function ajax_tienda_listado(Request $request)
    {   
        $envios = DB::table('movimientos')
                        ->select(
                            'movimientos.numero',
                            'origen.nombre as origen',
                            'almacenes.nombre as destino',
                            'movimientos.fecha',
                            DB::raw('SUM(ingreso) as total')
                            )
                        ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                        ->leftJoin('almacenes as origen', 'movimientos.almacen_origen_id', '=', 'origen.id')
                        ->whereDate('movimientos.fecha', '>=', $request->tipo_fecha_ini)
                        ->whereDate('movimientos.fecha', '<=', $request->tipo_fecha_fin)
                        ->whereNull('movimientos.deleted_at')
                        ->whereNotNull('movimientos.almacen_origen_id')
                        ->whereNotNull('movimientos.numero')
                        ->groupBy('movimientos.numero');
        if($request->tipo_id){
            $envios->where('movimientos.almacen_origen_id', $request->tipo_id);
        }
        return Datatables::of($envios)->make(true);
        
        /*
        $almacen_id = $request->tipo_id;
        $fecha_ini = $request->tipo_fecha_ini;
        $fecha_fin = $request->tipo_fecha_fin;
        if ($almacen_id == 0) {
            $productos = DB::select("SELECT alma.nombre as alma_nombre, tipo.nombre as tipo_nombre, prod.nombre as prod_nombre, marc.nombre as marc_nombre, prod.colores, (SUM(movi.ingreso) - SUM(movi.salida)) as stock
                                    FROM movimientos movi, almacenes alma, productos prod, tipos tipo, marcas marc
                                    WHERE movi.almacene_id = alma.id
                                    AND movi.producto_id = prod.id
                                    AND prod.tipo_id = tipo.id
                                    AND prod.marca_id = marc.id
                                    AND fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                                    AND movi.deleted_at IS NULL
                                    GROUP BY movi.producto_id, alma.id");
        } else {
            $productos = DB::select("SELECT alma.nombre as alma_nombre, tipo.nombre as tipo_nombre, prod.nombre as prod_nombre, marc.nombre as marc_nombre, prod.colores, (SUM(movi.ingreso) - SUM(movi.salida)) as stock
                                    FROM movimientos movi, almacenes alma, productos prod, tipos tipo, marcas marc
                                    WHERE movi.almacene_id = '$almacen_id'
                                    AND movi.almacene_id = alma.id
                                    AND movi.producto_id = prod.id
                                    AND prod.tipo_id = tipo.id
                                    AND prod.marca_id = marc.id
                                    AND fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                                    AND movi.deleted_at IS NULL
                                    GROUP BY movi.producto_id, alma.id");
        }
         return Datatables::of($productos)
                ->make(true);
        */
        
    }

    public function ventas()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $usuarios = User::where('rol', 'not like', 'Cliente')->get();
        return view('reporte.ventas')->with(compact('almacenes', 'usuarios'));
    }

    public function ajaxVentasListado(Request $request)
    {
        $ventas = DB::table('ventas')
                    ->whereNull('ventas.deleted_at')
                    // ->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereDate('ventas.fecha', '>=', $request->fecha_inicial)
                    ->whereDate('ventas.fecha', '<=', $request->fecha_final)
                    ->leftJoin('users', 'ventas.user_id', '=', 'users.id')
                    ->leftJoin('users as clientes', 'ventas.cliente_id', '=', 'clientes.id')
                    ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
                    ->select(
                            'ventas.id as nro_venta',
                            'almacenes.nombre as tienda',
                            'users.name as usuario_nombre',
                            'clientes.name as cliente_nombre',
                            'ventas.fecha as fecha',
                            'ventas.total as monto',
                            'ventas.saldo as saldo'
                    );
        
        if($request->almacen_id){
            $ventas->where('ventas.almacene_id', $request->almacen_id);
        }
        if($request->usuario_id != 'todos'){
            $ventas->where('ventas.user_id', $request->usuario_id);
            //leftJoin('users', 'ventas.user_id', '=', $request->usuario_id);
        }
        if($request->deudores){
            if($request->deudores == 'Si'){
                $ventas->where('saldo', '>', 0);
            }else{
                $ventas->where('saldo', 0);
            }
        }
        return Datatables::of($ventas)->make(true);
    }

    public function proveedores()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $proveedores = Proveedore::get();
        return view('reporte.proveedores')->with(compact('almacenes', 'proveedores'));
    }

    public function ajaxProveedoresListado(Request $request)
    {
        $productos = DB::table('movimientos')
                    ->whereNull('movimientos.deleted_at')
                    ->where('movimientos.estado', 'Ingreso')
                    ->whereNotNull('proveedor_id')
                    //->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereDate('movimientos.fecha', '>=', $request->fecha_inicial)
                    ->whereDate('movimientos.fecha', '<=', $request->fecha_final)
                    ->leftJoin('proveedores', 'movimientos.user_id', '=', 'proveedores.id')
                    ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
                    ->select(
                            'movimientos.id as nro_envio',
                            'almacenes.nombre as tienda',
                            'proveedores.nombre as proveedor_nombre',
                            'productos.codigo as codigo',
                            'productos.nombre as producto_nombre',
                            'movimientos.fecha as fecha',
                            'movimientos.ingreso as cantidad'
                    );
        if($request->almacen_id){
            $productos->where('movimientos.almacene_id', $request->almacen_id);
        }
        if($request->proveedor_id){
            $productos->where('movimientos.user_id', $request->proveedor_id);
        }
        return Datatables::of($productos)->make(true);
    }

    public function transferencias()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('reporte.transferencias')->with(compact('almacenes'));
    }

    public function ajaxTransferenciasListado(Request $request)
    {
        $movimientos = DB::table('movimientos')
        ->whereNull('movimientos.deleted_at')
        ->where('movimientos.estado', 'Envio')
        ->where('ingreso', '!=' , 0)
        //->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
        ->whereDate('movimientos.fecha', '>=', $request->fecha_inicial)
        ->whereDate('movimientos.fecha', '<=', $request->fecha_final)
        ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
        ->leftJoin('almacenes as origen', 'movimientos.almacen_origen_id', '=', 'origen.id')
        ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
        ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
        ->select(
                'movimientos.id as nro_transferencia',
                'origen.nombre as tienda_salida',
                'almacenes.nombre as tienda_llegada',
                'users.name as usuario',
                'productos.codigo as codigo',
                'productos.nombre as producto_nombre',
                'movimientos.ingreso as cantidad',
                'movimientos.fecha as fecha'
        );
        if($request->almacen_origen_id){
            $movimientos->where('movimientos.almacen_origen_id', $request->almacen_origen_id);
        }
        if($request->almacen_destino_id){
            $movimientos->where('movimientos.almacene_id', $request->almacen_destino_id);
        }
        return Datatables::of($movimientos)->make(true);
    }

    public function promos()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('reporte.promos')->with(compact('almacenes'));
    }

    public function ajaxPromosListado(Request $request)
    {
        /*$ventas_id = VentasProducto::whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereNotNull('combo_id')
                    ->groupBy('combo_id')
                    ->get();
        // En un array guardaremos el atributo venta_id de los registros capturados
        $array_ventas = array();
        foreach($ventas_id as $row){
            array_push($array_ventas, $row->venta_id);
        }*/

        $ventas = DB::table('ventas_productos')
                    ->whereNull('ventas_productos.deleted_at')
                    // ->whereIn('ventas.id', $array_ventas)
                    ->whereDate('ventas_productos.fecha', '>=', $request->fecha_inicial)
                    ->whereDate('ventas_productos.fecha', '<=', $request->fecha_final)
                    //->where('ventas_productos.combo_id', '=', 'combos.id')
                    //->whereBetween('ventas.fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->leftJoin('ventas', 'ventas_productos.venta_id', '=', 'ventas.id')
                    ->leftJoin('users', 'ventas.user_id', '=', 'users.id')
                    // ->leftJoin('users as clientes', 'ventas.cliente_id', '=', 'clientes.id')
                    // ->join('combos', 'combos.id', '=', 'ventas_productos.combo_id')
                    ->join('combos', 'ventas_productos.combo_id', '=', 'combos.id')
                    ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
                    //->leftJoin('combos', 'ventas.producto_id', '=', 'combos.id')
                    ->select(
                        'ventas.id as nro_venta',
                        //'origen.nombre as tienda_salida', NOMBRE DE COMBO     Como desde ventas puedo sacar el codigo del cupon?
                        //'ventas_productos.combo_id as combo',
                        'combos.nombre as combo',
                        'almacenes.nombre as tienda',
                        'users.name as usuario',
                        'ventas.fecha as fecha',
                        // 'clientes.name as cliente',
                        'ventas.total as total'
                    );
                    // ->groupBy('ventas_productos.combo_id');
        if($request->almacen_id){
            $ventas->where('ventas.almacene_id', $request->almacen_id);
        }
        return Datatables::of($ventas)->make(true);
        
    }

    public function cupones()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $creados_sin_almacen = Cupone::whereNull('almacene_id')->count();
        $creados = Cupone::where('almacene_id', Auth::user()->almacen->id)->count();
        $total = $creados_sin_almacen+$creados;
        $cobrados = CuponesCobrado::where('almacene_id', Auth::user()->almacen->id)->count();;
        $expirados = $total-$cobrados;
        return view('reporte.cupones')->with(compact('almacenes', 'total', 'cobrados', 'expirados'));
    }

    public function ajaxCuponesListado(Request $request)
    {
        $ventas = DB::table('cupones_cobrados')
                    ->whereNull('cupones_cobrados.deleted_at')
                    //->whereBetween('cupones_cobrados.fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereDate('cupones_cobrados.fecha', '>=', $request->fecha_inicial)
                    ->whereDate('cupones_cobrados.fecha', '<=', $request->fecha_final)
                    ->leftJoin('almacenes', 'cupones_cobrados.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('users', 'cupones_cobrados.cobrador_id', '=', 'users.id')
                    ->leftJoin('cupones', 'cupones_cobrados.cupone_id', '=', 'cupones.id')
                    //->leftJoin('ventas_productos', 'cupones_cobrados.cupone_id', '=', 'ventas_productos.cupon_id')
                    ->join('users as clientes', 'cupones.cliente_id', '=', 'clientes.id')
                    ->select(
                        'cupones_cobrados.id as nro_cupon',
                        'cupones.codigo as codigo_cupon',
                        'almacenes.nombre as tienda',
                        'users.name as usuario',
                        'cupones_cobrados.fecha as fecha',
                        'clientes.name as cliente'
                    );
        if($request->almacen_id){
            $ventas->where('cupones_cobrados.almacene_id', $request->almacen_id);
        }
        return Datatables::of($ventas)->make(true);
    }

    public function saldos()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $tipos = Tipo::get();
        return view('reporte.saldos')->with(compact('almacenes', 'tipos'));
    }

    public function ajax_listado_saldos(Request $request)
    {
        // $datosMovimientos = Movimiento::where('movimientos.fecha', '<=', $request->fecha)
        //                     ->select('movimientos.producto_id', 'productos.nombre', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as total'), 'almacene_id')
        //                     ->where('movimientos.almacene_id', $request->almacen_id)
        //                     ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
        //                     ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //                     ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
        //                     ->whereNull('productos.estado')
        //                     // ->where('estado', 'Ingreso')
        //                     // ->orWhere('estado', 'Envio')
        //                     ->groupBy('movimientos.producto_id')
        //                     ->get();
        $fecha = $request->fecha;
        $almacen = Almacene::find($request->almacen_id);
        if($request->tipo_id == "todos"){

            $productos = Producto::whereNull('estado')
                            ->orderBy('marca_id', 'asc')
                            ->get();
        }else{
            $productos = Producto::whereNull('estado')
                            ->where('tipo_id', $request->tipo_id)
                            ->orderBy('marca_id', 'asc')
                            ->get();
        }

        return view('reporte.ajax_listado_saldos')->with(compact('productos', 'fecha', 'almacen'));
        //return view('reporte.ajax_listado_saldos')->with(compact('datosMovimientos'));
    }

    public function saldos_tiendas()
    {
        $tipos = Tipo::orderBy('id', 'asc')->get();
        return view('reporte.saldos_tiendas')->with(compact('tipos'));
    }

    public function ajax_listado_saldos_tiendas(Request $request)
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $query = Producto::orderBy('tipo_id');
            if ($request->tipo_id) {
                $query = $query->where('tipo_id', $request->tipo_id);
            }
            // if($request->continuo){
            //     $query = $query->whereNotNull('estado');
            // }
            $productos = $query->get();
        return view('reporte.ajax_listado_saldos_tiendas')->with(compact('almacenes', 'productos'));
    }

    public function ventas_usuario()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('reporte.ventas_usuario')->with(compact('almacenes'));
    }

    public function ajax_listado_ventas_usuario(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $users = User::where('almacen_id', $request->almacen_id)
                    ->get();
        $fechas = DB::select("
                            SELECT vp.fecha, DAYOFWEEK(vp.fecha) as dia
                            FROM ventas_productos as vp
                            WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
                            GROUP BY vp.fecha
                            ");
        return view('reporte.ajax_listado_ventas_usuario')->with(compact('users', 'fechas'));
    }

    public function ventas_accesorio()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $vendedores = User::where('almacen_id', 1)->get();
        $tipos = Tipo::get();
        $marcas = Marca::get();
        return view('reporte.ventas_accesorio')->with(compact('almacenes', 'tipos', 'vendedores', 'marcas'));
    }

    public function ajax_listado_ventas_accesorio(Request $request)
    {
        // dd($request->all());
        // Ejecutamos la consulta, incluyendo a los usuarios pertenecientes al almacen X
        // $query = VentasProducto::whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
                                // ->orderBy('tipo_id');
        $query = VentasProducto::whereDate('ventas_productos.fecha', '>=', $request->fecha_inicio)
                            ->whereDate('ventas_productos.fecha', '<=', $request->fecha_fin)
                            ->orderBy('ventas_productos.tipo_id');

        if($request->marca_id != 'todos'){
            $query = $query->leftJoin('productos', 'ventas_productos.producto_id', '=', 'productos.id');
            $query = $query->where('productos.marca_id', $request->marca_id);
        }

        if(Auth::user()->perfil_id == 1){

            if ($request->almacen_id == 'todos') {
                // Encontramos a los usuarios pertenecientes al almacen X
                $users = User::where('perfil_id', 3)->get();
                // En un array guardaremos el atributo id de la tabla Users de los registros capturados
                $array_user_id = array();
                foreach($users as $row){
                    array_push($array_user_id, $row->id);
                }
                $query = $query->whereIn('ventas_productos.user_id', $array_user_id);
            }else{
                if($request->usuario_id == 'todos'){
                    $users = User::where('perfil_id', 3)
                            ->where('almacen_id', $request->almacen_id)
                            ->get();
                    // En un array guardaremos el atributo id de la tabla Users de los registros capturados
                    $array_user_id = array();
                    foreach($users as $row){
                        array_push($array_user_id, $row->id);
                    }
                    $query = $query->whereIn('ventas_productos.user_id', $array_user_id);
                }else{
                    $query = $query->where('ventas_productos.user_id', $request->usuario_id);
                }
            }

        }else{
            $query = $query->where('ventas_productos.user_id', Auth::user()->id);
        }
        
        // Si se buscara un tipo_id X
        if ($request->tipo_id) {
            $query = $query->where('ventas_productos.tipo_id', $request->tipo_id);
        }
        // Guardamos el resultado en la variable $ventas
        $ventas = $query->get();
        // dd($ventas);
        return view('reporte.ajax_listado_ventas_accesorio')->with(compact('ventas'));
    }

    public function saldos_mayorista()
    {
        $almacenes = Almacene::whereNotNull('estado')->get();
        $tipos = Tipo::get();
        return view('reporte.saldos_mayorista')->with(compact('almacenes', 'tipos'));
    }

    public function ajax_listado_saldos_mayorista(Request $request)
    {
        $datosMovimientos = Movimiento::where('movimientos.fecha', '<=', $request->fecha)
                            ->select('movimientos.producto_id', 'productos.nombre', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as total'), 'almacene_id')
                            ->where('movimientos.almacene_id', $request->almacen_id)
                            ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
                            ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
                            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
                            ->whereNull('productos.estado')
                            // ->where('estado', 'Ingreso')
                            // ->orWhere('estado', 'Envio')
                            ->groupBy('movimientos.producto_id')
                            ->get();

        return view('reporte.ajax_listado_saldos_mayorista')->with(compact('datosMovimientos'));
    }

    public function saldos_diarios()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $tipos = Tipo::get();
        return view('reporte.saldos_diarios')->with(compact('almacenes', 'tipos'));
    }

    public function ajax_listado_saldos_diarios(Request $request)
    {
        // $datosMovimientos = Movimiento::where('movimientos.fecha', '<=', $request->fecha)
        //                     ->select('movimientos.producto_id', 'productos.nombre', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as total'), 'almacene_id')
        //                     ->where('movimientos.almacene_id', $request->almacen_id)
        //                     ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
        //                     ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //                     ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
        //                     ->whereNull('productos.estado')
        //                     // ->where('estado', 'Ingreso')
        //                     // ->orWhere('estado', 'Envio')
        //                     ->groupBy('movimientos.producto_id')
        //                     ->get();

        $fecha = $request->fecha;
        $almacen = Almacene::find($request->almacen_id);
        $productos = Producto::whereNull('estado')
                                    ->orderBy('marca_id', 'asc')
                                    ->get();

        return view('reporte.ajax_listado_saldos_diarios')->with(compact('productos', 'fecha', 'almacen'));
    }

    public function ajaxMuestraVendedores(Request $request)
    {
        if($request->almacenId){
            $vendedores = User::where('almacen_id', $request->almacenId)
                            ->whereNotIn('rol', ['Cliente', 'Mayorista'])
                            ->get();
        }else{
            $vendedores = User::whereNotIn('rol', ['Cliente', 'Mayorista'])
                            ->get();
        }
        // dd($vendedores);
        return view('reporte.ajax_muestra_vendedores')->with(compact('vendedores'));
    }

    public function auditoria(Request $request)
    {
        $almacenes = Almacene::whereNull('estado')
                    ->get();

        $usuarios = User::whereIn('rol', ['Administrador', 'Tienda'])
                    ->get();

        return view('reporte.auditoria')->with(compact('almacenes', 'usuarios'));
    }

    public function ajaxAuditoria(Request $request)
    {
        // dd($request->all());

        $query = Venta::whereDate('fecha', '>=', $request->fecha_inicial)
                        ->whereDate('fecha', '<=', $request->fecha_final);
        // Venta::withTrashed()
                // ->get();

        if($request->almacen_id != "todos"){
            $query->where('almacene_id', $request->almacen_id);
        }

        if($request->usuario_id != "todos"){
            $query->where('user_id', $request->usuario_id);
        }

        if($request->eliminados == "Si"){
            $query->onlyTrashed();
        }else{
            $query->withTrashed();
        }

        $ventas = $query->get();

        return view('reporte.ajaxAuditoria')->with(compact('ventas'));

    }

    public function ingresos()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        $vendedores = User::where('almacen_id', 1)->get();
        $tipos = Tipo::get();
        $marcas = Marca::get();
        return view('reporte.ingresos')->with(compact('almacenes', 'tipos', 'vendedores', 'marcas'));
    }

    public function ajax_ingresos(Request $request)
    {
        $q = Movimiento::select(
                                    'productos.nombre',
                                    'productos.marca_id',
                                    'productos.tipo_id',
                                    'movimientos.ingreso',
                                    'movimientos.almacene_id',
                                    'movimientos.created_at'
                                )
                                ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
                                ->whereDate('movimientos.fecha', '>=', $request->fecha_inicio)
                                ->whereDate('movimientos.fecha', '<=', $request->fecha_fin)
                                // ->where('movimientos.estado', 'Ingreso')
                                ->where('movimientos.ingreso', '>', 0);
            
        if($request->almacen_id != "todos"){
            $q->where('movimientos.almacene_id', $request->almacen_id);
        }

        if($request->marca_id != "todos"){
            $q->where('productos.marca_id', $request->marca_id);
        }

        if($request->tipo_id != "todos"){
            $q->where('productos.tipo_id', $request->tipo_id);
        }

        $productos = $q->get();
        return view('reporte.ajax_ingresos')->with(compact('productos'));
        // dd($productos);
    }

    public function metas()
    {
        $vendedores = User::where('rol', 'Tienda')
                        ->get();
        return view('reporte.metas')->with(compact('vendedores'));        
    }

    public function ajaxMetas(Request $request)
    {
        $vendedores = User::where('rol', 'Tienda')
                     ->get();
                     
        $gestion  = $request->gestion;
   
        return view('reporte.ajaxMetas')->with(compact('vendedores', 'gestion'));        
    }

    public function saldosPrecios()
    {
        $almacenes = Almacene::whereNull('estado')->get();

        $tipos = Tipo::get();
        
        return view('reporte.saldoPrecios')->with(compact('almacenes', 'tipos'));
    }

    public function ajax_listado_saldos_precios(Request $request)
    {
        // $datosMovimientos = Movimiento::where('movimientos.fecha', '<=', $request->fecha)
        //                     ->select('movimientos.producto_id', 'productos.nombre', 'tipos.nombre as nombre_tipo', 'marcas.nombre as nombre_marca', DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as total'), 'almacene_id')
        //                     ->where('movimientos.almacene_id', $request->almacen_id)
        //                     ->leftJoin('productos', 'movimientos.producto_id', '=', 'productos.id')
        //                     ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
        //                     ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
        //                     ->whereNull('productos.estado')
        //                     // ->where('estado', 'Ingreso')
        //                     // ->orWhere('estado', 'Envio')
        //                     ->groupBy('movimientos.producto_id')
        //                     ->get();
        
        // $fecha = $request->fecha;
        // $almacen = Almacene::find($request->almacen_id);
        if($request->tipo_id == "todos"){

            $productos = Producto::whereNull('estado')
                            ->orderBy('marca_id', 'asc')
                            ->get();
        }else{
            $productos = Producto::whereNull('estado')
                            ->where('tipo_id', $request->tipo_id)
                            ->orderBy('marca_id', 'asc')
                            ->get();
        }

        return view('reporte.ajax_listado_saldos_precios')->with(compact('productos'));
        // return view('reporte.ajax_listado_saldos_precios')->with(compact('productos', 'fecha', 'almacen'));
        //return view('reporte.ajax_listado_saldos')->with(compact('datosMovimientos'));
    }
}
