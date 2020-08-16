<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Almacene;
use App\User;

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
        //dd($request->fecha_final);
        
        $ventas = DB::table('ventas')
                    ->whereNull('ventas.deleted_at')
                    ->whereBetween('fecha', [$request->fecha_inicial, $request->fecha_final])
                    ->leftJoin('users', 'ventas.user_id', '=', 'users.id')
                    ->leftJoin('almacenes', 'ventas.almacene_id', '=', 'almacenes.id')
                    ->select(
                        'ventas.id as nro_venta',
                        'almacenes.nombre as tienda',
                        'users.name as usuario_nombre',
                        'ventas.cliente_id as cliente_id',
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

        // SELECT almacenes.nombre as alma_nombre, tipos.nombre as tipo_nombre, productos.nombre as prod_nombre, marcas.nombre as marc_nombre, productos.colores, (SUM(movimientos.ingreso) - SUM(movimientos.salida)) as stock
        // FROM movimientos
        // LEFT JOIN almacenes ON movimientos.almacene_id = almacenes.id
        // LEFT JOIN productos ON movimientos.producto_id = productos.id
        // LEFT JOIN tipos ON productos.tipo_id = tipos.id
        // LEFT JOIN marcas ON productos.marca_id = marcas.id
        // WHERE movimientos.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
        // GROUP BY movimientos.producto_id, almacenes.id

    }
}
