<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    public function nuevo()
    {
        return view('paquete.nuevo');
    }

    public function ajax_listado()
    {
        // $lista_personal = Producto::all();
        $productos = DB::table('productos')
            ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select(
                'productos.id',
                'productos.codigo',
                'productos.nombre as nombre',
                'productos.nombre_venta',
                'tipos.nombre as tipo',
                'marcas.nombre as marca',
                'productos.colores'
            );

        return Datatables::of($productos)
            ->addColumn('action', function ($productos) {
                return '<button onclick="edita_producto(' . $productos->id . ')" class="btn btn-warning"><i class="fas fa-edit"></i></button> <button onclick="asigna_materias(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>';
            })
            ->make(true);
    }
}
