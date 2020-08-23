<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Almacene;
use App\User;
use App\Proveedore;
use App\VentasProducto;
use App\Venta;

class ReporteController extends Controller
{
    public function ventas()
    {
        $almacenes = Almacene::get();
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
            $ventas->where('credito', $request->deudores);
        }
        return Datatables::of($ventas)->make(true);
    }

    public function proveedores()
    {
        $almacenes = Almacene::get();
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
        $almacenes = Almacene::get();
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
        $almacenes = Almacene::get();
        return view('reporte.promos1')->with(compact('almacenes'));
    }

    public function ajaxPromosListado(Request $request)
    {
        $ventas_id = VentasProducto::whereBetween('fecha', ['2020-08-01', '2020-08-30'])
        //$ventas_id = VentasProducto::whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereNotNull('combo_id')
                    ->groupBy('combo_id')
                    ->get();
        $array_ventas = array();
        foreach($ventas_id as $row){
            array_push($array_ventas, $row->venta_id);
        }
        //dd($array_ventas);

        
        $variable = Venta::whereBetween('fecha', ['2020-08-01', '2020-08-30'])
        //$variable = Venta::whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                        ->whereIn('id', $array_ventas)
                        ->with('almacen', 'user', 'cliente');
                        // ->select('id')
                        // ->get();

        //dd($variable);

        return Datatables::of($variable)
        ->addColumn('almacen', function (Venta $venta){ return $venta->almacen->nombre; })
        ->addColumn('user', function (Venta $venta){ return $venta->user->name; })
        ->addColumn('cliente', function (Venta $venta){ return $venta->cliente->name; })
        ->make(true);

        /*
        $ventas = DB::table('ventas')
                    ->whereNull('ventas.deleted_at')
                    ->whereIn('ventas.id', $array_ventas)
                    ->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('users', 'ventas.user_id', '=', 'users.id')
                    ->leftJoin('users as clientes', 'ventas.cliente_id', '=', 'clientes.id')
                    //->leftJoin('almacenes as origen', 'ventas.almacen_origen_id', '=', 'origen.id')
                    //->leftJoin('combos', 'ventas.producto_id', '=', 'combos.id')
                    ->select(
                        'ventas.id as nro_venta',
                        //'origen.nombre as tienda_salida', NOMBRE DE COMBO     Como desde ventas puedo sacar el codigo del cupon?
                        'almacenes.nombre as tienda',
                        'users.name as usuario',
                        'ventas.fecha as fecha',
                        'clientes.name as cliente',
                        'ventas.total as total'
                    );
        if($request->almacen_id){
            $ventas->where('ventas.almacen_id', $request->almacen_id);
        }
        return Datatables::of($ventas)->make(true);
        */
    }

    public function cupones()
    {
        $almacenes = Almacene::get();
        return view('reporte.cupones')->with(compact('almacenes'));
    }

    public function ajaxCuponesListado(Request $request)
    {
        $ventas_id = VentasProducto::whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->whereNotNull('cupon_id')
                    ->groupBy('cupon_id')
                    ->get();
        $array_ventas = array();
        foreach($ventas_id as $row){
            array_push($array_ventas, $row->venta_id);
        }
        $ventas = DB::table('movimientos')
                    ->whereNull('movimientos.deleted_at')
                    ->whereIn('movimientos.id', $array_ventas)
                    //->whereBetween('movimientos.fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                    ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                    ->leftJoin('users as clientes', 'movimientos.cliente_id', '=', 'clientes.id')
                    ->leftJoin('cupones', 'movimientos.cupon_id', '=', 'cupones.id')
                    //->leftJoin('ventas', 'movimientos.venta_id', '=', 'ventas.id')
                    ->select(
                        'movimientos.id as nro_movimiento',
                        'cupones.codigo as codigo_cupon',
                        'almacenes.nombre as tienda',
                        'users.name as usuario',
                        'movimientos.fecha as fecha',
                        'clientes.name as cliente',
                        'movimientos.precio_venta as total'
                    );
        if($request->almacen_id){
            $ventas->where('movimientos.almacene_id', $request->almacen_id);
        }
        return Datatables::of($ventas)->make(true);
    }
}
