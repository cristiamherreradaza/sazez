<?php

namespace App\Http\Controllers;

use App\Tipo;
use App\Marca;
use App\Escala;
use App\Precio;
use DataTables;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\CategoriasProducto;
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
        $tipos = Tipo::all();
        return view('producto/nuevo')->with(compact('marcas', 'categorias', 'almacenes', 'escalas', 'tipos'));
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
            ->leftJoin('tipos', 'productos.tipo_id', '=', 'tipos.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select(
                'productos.id', 
                'productos.codigo', 
                'productos.nombre as producto', 
                'productos.nombre_venta as venta', 
                'tipos.nombre as tipo', 
                'marcas.nombre as marca', 
                'productos.colores'
            );

        return Datatables::of($productos)
            ->addColumn('action', function ($productos) {
                return '<button onclick="asigna_materias(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);    
    }

    public function guarda(Request $request)
    {
        // dd(Auth::user()->id);
        
        // dd($request->precio_venta);
        $nuevoProducto                 = new Producto();
        $nuevoProducto->user_id        = Auth::user()->id;
        $nuevoProducto->marca_id       = $request->marca_id;
        $nuevoProducto->tipo_id        = $request->tipo_id;
        $nuevoProducto->codigo         = $request->codigo;
        $nuevoProducto->nombre         = $request->nombre;
        $nuevoProducto->nombre_venta   = $request->nombre_venta;
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

        $producto_id = $nuevoProducto->id;

        $categorias = $request->categorias_valores;
        $array_categorias = explode(',', $categorias);

        foreach ($array_categorias as $key => $ac) 
        {
            $nuevaCategoria               = new CategoriasProducto();
            $nuevaCategoria->user_id      = Auth::user()->id;
            $nuevaCategoria->categoria_id = $ac;
            $nuevaCategoria->producto_id  = $producto_id;
            $nuevaCategoria->save();
        }

        foreach ($request->precio_venta as $key => $pv) {
            // echo $request->escalas[$key] . ' ' . $pv . '<br />';
            $nuevoPrecio = new Precio();
            $nuevoPrecio->user_id = Auth::user()->id;
            $nuevoPrecio->producto_id = $producto_id;
            $nuevoPrecio->escala_id = $request->escalas[$key];
            $nuevoPrecio->precio = $pv;
            $nuevoPrecio->save();
        }
        // dd($request->all());
        return redirect('Producto/listado');
    }

    public function edita(Request $request, $producto_id)
    {
        $producto = Producto::find($producto_id);
        dd($producto);
    }

    public function ajax_verifica_codigo()
    {

    }

}
