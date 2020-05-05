<?php

namespace App\Http\Controllers;

use App\Marca;
use DataTables;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\Escala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function nuevo()
    {
        $marcas = Marca::where('deleted_at', NULL)->get();
        $categorias = Categoria::where('deleted_at', NULL)->get();
        $almacenes = Almacene::where('deleted_at', NULL)->get();
        $escalas = Escala::where('deleted_at', NULL)->get();
        return view('producto/nuevo')->with(compact('marcas', 'categorias', 'almacenes', 'escalas'));
    }

    public function listado()
    {
        // dd($productos);
        return view('producto.listado');
    }

    public function ajax_listado()
    {
        // $lista_personal = Producto::all();
        $productos = DB::table('productos')
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select('productos.id', 'productos.codigo', 'productos.nombre as producto', 'productos.tipo', 'categorias.nombre as categoria', 'marcas.nombre as marca', 'productos.colores');

        return Datatables::of($productos)
            ->addColumn('action', function ($productos) {
                return '<button onclick="asigna_materias(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);    
    }

    public function guarda(Request $request)
    {
    	$data = Producto::find(1)->delete();
        dd($request->all());
        // dd($request->precio_venta);
        $nuevoProducto                 = new Producto();
        $nuevoProducto->user_id        = Auth::user()->id;
        $nuevoProducto->marca_id       = $request->marca_id;
        $nuevoProducto->categoria_id   = $request->categoria_id;
        $nuevoProducto->codigo         = $request->codigo;
        $nuevoProducto->nombre         = $request->nombre;
        $nuevoProducto->tipo           = $request->tipo;
        $nuevoProducto->modelo         = $request->modelo;
        $nuevoProducto->precio_compra  = $request->precio_compra;
        $nuevoProducto->largo          = $request->largo;
        $nuevoProducto->ancho          = $request->ancho;
        $nuevoProducto->alto           = $request->alto;
        $nuevoProducto->peso           = $request->peso;
        $nuevoProducto->colores        = $request->colores;
        $nuevoProducto->descripcion    = $request->descripcion;
        $nuevoProducto->url_referencia = $request->url_referencia;
        $nuevoProducto->video          = $request->video;
        $nuevoProducto->save();
        // return redirect('Producto/listado');
    }

    public function ajax_verifica_codigo()
    {

    }

}
