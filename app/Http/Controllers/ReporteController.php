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

class ReporteController extends Controller
{
    public function reporte_tienda()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('reporte.reporte_tienda')->with(compact('almacenes'));
    }

    public function ajax_tienda_listado(Request $request)
    {   
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
                    ->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
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
        // if($request->has('almacen_id')){
        //     $ventas->where('almacene_id', $request->almacen_id);
        // }
        if($request->almacen_id){
            $ventas->where('ventas.almacene_id', $request->almacen_id);
        }
        if($request->usuario_id){
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
                    ->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
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
        ->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
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
        // $row = DB::table('ventas_productos')
        //     ->select(
        //         'ventas_productos.*',
        //         'contacts.name',
        //         DB::raw('SUM(precio_cobrado*cantidad) as total')
        //         )
        //     ->leftJoin('ventas', 'ventas_productos.supplier', '=', 'ventas.id')
        //     ->leftJoin('inventory_has_warehouses', 'ventas_productos.id', '=', 'inventory_has_warehouses.inventory_id')
        //     ->where('ventas_productos.subscriber_id',$id)
        //     ->groupBy('inventory_has_warehouses.inventory_id');

        // return Datatables::of($row)->make(true);


        // Capturamos todos los registros que tengan combo_id en el rango de fechas x-y de la tabla ventas_productos
        //$ventas_id = VentasProducto::whereBetween('fecha', ['2020-08-01', '2020-08-30'])
        $ventas_id = VentasProducto::whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereNotNull('combo_id')
                    ->groupBy('combo_id')
                    ->get();
        // En un array guardaremos el atributo venta_id de los registros capturados
        $array_ventas = array();
        foreach($ventas_id as $row){
            array_push($array_ventas, $row->venta_id);
        }

        //dd($array_ventas);
        
        // En $variable almacenaremos los registros a mostrar en interfaz
        //$variable = Venta::whereBetween('fecha', ['2020-08-01', '2020-08-30'])
        // $variable = Venta::whereIn('id', $array_ventas)
        //                 ->with('almacen', 'user', 'cliente');

        /*          OPCION 1
        $variable = VentasProducto::whereIn('venta_id', $array_ventas)
                        ->with('almacen', 'user', 'cliente')
                        ->select(DB::raw('SUM(precio_cobrado*cantidad) as total'))
                        ->groupBy('combo_id')
                        ->get();

                        dd($variable);
        // Envio de los datos a la vista
        return Datatables::of($variable)
        ->addColumn('almacen', function (Venta $venta){ return $venta->almacen->nombre; })
        ->addColumn('user', function (Venta $venta){ return $venta->user->name; })
        ->addColumn('cliente', function (Venta $venta){ return $venta->cliente->name; })
        ->make(true);
        */

        /* opcion 2
        $variable = VentasProducto::whereIn('venta_id', $array_ventas)
                        ->with('almacen', 'user', 'cliente', 'total')
                        ->select(DB::raw('SUM(precio_cobrado*cantidad) as total'))
                        ->groupBy('combo_id');
                        //->get();

                        //dd($variable);
        // Envio de los datos a la vista
        return Datatables::of($variable)
        ->addColumn('almacen', function (Venta $venta){ return $venta->almacen->nombre; })
        ->addColumn('user', function (Venta $venta){ return $venta->user->name; })
        ->addColumn('cliente', function (Venta $venta){ return $venta->cliente->name; })
        ->addColumn('cliente', function (Venta $venta){ return $venta->cliente->name; })
        ->make(true);
        */

        /*
        $ventas = DB::table('movimientos')
                    ->whereNull('movimientos.deleted_at')
                    ->whereIn('movimientos.venta_id', $array_ventas)
                    //->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                    ->leftJoin('users as clientes', 'movimientos.cliente_id', '=', 'clientes.id')
                    //->leftJoin('almacenes as origen', 'movimientos.almacen_origen_id', '=', 'origen.id')
                    //->leftJoin('combos', 'movimientos.producto_id', '=', 'combos.id')
                    ->select(
                        'movimientos.id as nro_venta',
                        //'origen.nombre as tienda_salida', NOMBRE DE COMBO     Como desde movimientos puedo sacar el codigo del cupon?
                        'almacenes.nombre as tienda',
                        'users.name as usuario',
                        'movimientos.fecha as fecha',
                        'clientes.name as cliente',
                        DB::raw('SUM(movimientos.precio_venta*movimientos.ingreso) as total')
                    )
                    ->groupBy('movimientos.combo_id')
                    ->get();

                    dd($ventas);
        if($request->almacen_id){
            $ventas->where('movimientos.almacene_id', $request->almacen_id);
        }
        return Datatables::of($ventas)->make(true);
        */

        
        $ventas = DB::table('ventas')
                    ->whereNull('ventas.deleted_at')
                    ->whereIn('ventas.id', $array_ventas)
                    //->where('ventas_productos.combo_id', '=', 'combos.id')
                    //->whereBetween('ventas.fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('users', 'ventas.user_id', '=', 'users.id')
                    ->leftJoin('users as clientes', 'ventas.cliente_id', '=', 'clientes.id')
                    ->join('ventas_productos', 'ventas.id', '=', 'ventas_productos.venta_id')
                    ->join('combos', 'ventas_productos.combo_id', '=', 'combos.id')
                    //->leftJoin('almacenes as origen', 'ventas.almacen_origen_id', '=', 'origen.id')
                    //->leftJoin('combos', 'ventas.producto_id', '=', 'combos.id')
                    ->select(
                        'ventas.id as nro_venta',
                        //'origen.nombre as tienda_salida', NOMBRE DE COMBO     Como desde ventas puedo sacar el codigo del cupon?
                        //'ventas_productos.combo_id as combo',
                        'combos.nombre as combo',
                        'almacenes.nombre as tienda',
                        'users.name as usuario',
                        'ventas.fecha as fecha',
                        'clientes.name as cliente',
                        'ventas.total as total'
                    )
                    ->groupBy('ventas_productos.combo_id');
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
                    ->whereBetween('cupones_cobrados.fecha', [$request->fecha_inicial, $request->fecha_final])
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
            $fecha = $request->fecha;
            $almacen = Almacene::find($request->almacen_id);
            $query = Producto::orderBy('tipo_id');
            if ($request->tipo_id) {
                $query = $query->where('tipo_id', $request->tipo_id);
            }
            // if($request->continuo){
            //     $query = $query->whereNotNull('estado');
            // }
            $productos = $query->get();
            return view('reporte.ajax_listado_saldos')->with(compact('almacen', 'fecha', 'productos'));
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
}
