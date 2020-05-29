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
use App\Movimiento;
use App\Caracteristica;
use App\ImagenesProducto;
use App\CategoriasProducto;
use Illuminate\Http\Request;
use App\Imports\ProductosImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

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

    public function guarda(Request $request)
    {
        if ($request->producto_id) {
            $nuevoProducto = Producto::find($request->producto_id);

        }else{
            $nuevoProducto = new Producto();
        }
        // dd($request->all());
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

        // $sigla_marca = $this->extraeCodigo();
        $marcaProducto = Marca::find($request->marca_id);

        if ($request->producto_id) {
            $producto_id          = $request->producto_id;
            $borraCaracteristicas = Caracteristica::where('producto_id', $producto_id)->delete();
            $borraCategorias      = CategoriasProducto::where('producto_id', $producto_id)->delete();
            $borraPrecios         = Precio::where('producto_id', $producto_id)->delete();
            // $borraImagenes        = ImagenesProducto::where('producto_id', $producto_id)->delete();
        } else {
            $producto_id = $nuevoProducto->id;
        }

        if ($request->caracteristica[0] != null) 
        {
            foreach ($request->caracteristica as $key => $c) 
            {
                if ($c != null) {
                    $caracteristica = new Caracteristica();
                    $caracteristica->user_id = Auth::user()->id;
                    $caracteristica->producto_id = $producto_id;
                    $caracteristica->descripcion = $c;
                    $caracteristica->save();
                }
            }

        }

        if ($request->has('categorias_valores')) 
        {
            $categorias = $request->categorias_valores;
            $array_categorias = explode(',', $categorias);

            foreach ($array_categorias as $key => $ac) {
                $nuevaCategoria               = new CategoriasProducto();
                $nuevaCategoria->user_id      = Auth::user()->id;
                $nuevaCategoria->categoria_id = $ac;
                $nuevaCategoria->producto_id  = $producto_id;
                $nuevaCategoria->save();
            }
        }

        if ($request->has('precio_venta')) 
        {
            foreach ($request->precio_venta as $key => $pv) {
                // echo $request->escalas[$key] . ' ' . $pv . '<br />';
                $nuevoPrecio              = new Precio();
                $nuevoPrecio->user_id     = Auth::user()->id;
                $nuevoPrecio->producto_id = $producto_id;
                $nuevoPrecio->escala_id   = $request->escalas[$key];
                $nuevoPrecio->precio      = $pv;
                $nuevoPrecio->save();
            }

        }

        if ($request->fotos[0] != null) 
        {
            foreach ($request->fotos as $key => $f) 
            {
                $archivo = $f;
                $direccion = 'imagenesProductos/'; // upload path
                $nombreArchivo = date('YmdHis').$key. "." . $archivo->getClientOriginalExtension();
                $archivo->move($direccion, $nombreArchivo);

                $imagenProducto              = new ImagenesProducto();
                $imagenProducto->user_id     = Auth::user()->id;
                $imagenProducto->producto_id = $producto_id;
                $imagenProducto->imagen      = $nombreArchivo;
                $imagenProducto->save();
            }
        }

        if ($request->cantidad > 0) {
            $movimiento = new Movimiento();
            $movimiento->user_id       = Auth::user()->id;
            $movimiento->producto_id   = $producto_id;
            $movimiento->almacene_id   = $request->almacene_id;
            $movimiento->precio_compra = $request->precio_compra;
            $movimiento->ingreso       = $request->cantidad;
            $movimiento->save();
        }

        return redirect('Producto/listado');
    }

    public function edita(Request $request, $producto_id)
    {
        $producto = Producto::find($producto_id);
        $marcas = Marca::where('deleted_at', NULL)->get();
        $categorias = Categoria::where('deleted_at', NULL)->get();
        $categorias_productos = CategoriasProducto::where('producto_id', $producto_id)->get();
        $precios = Precio::where('producto_id', $producto_id)->get();
        $caracteristicas_producto = Caracteristica::where('producto_id', $producto_id)->get();
        $imagenes_producto = ImagenesProducto::where('producto_id', $producto_id)->get();
        // dd($categorias_productos);
        $almacenes = Almacene::where('deleted_at', NULL)->get();
        $escalas = Escala::where('deleted_at', NULL)->get();
        $tipos = Tipo::all();
        return view('producto.edita')->with(compact(
                                                'producto', 
                                                'marcas', 
                                                'categorias', 
                                                'almacenes', 
                                                'escalas', 
                                                'tipos', 
                                                'categorias_productos', 
                                                'precios',
                                                'caracteristicas_producto',
                                                'imagenes_producto'
                                            ));
        // dd($producto);
    }

    public function importaExcel(Request $request)
    {
        if ($archivo = $request->file('excel')) 
        {
            $direccion = 'excels/'; // upload path
            $nombreArchivo = date('YmdHis') . "." . $archivo->getClientOriginalExtension();
            $archivo->move($direccion, $nombreArchivo);

            $archivo = public_path("excels/$nombreArchivo");
            // dd($archivo);
            Excel::import(new ProductosImport, $archivo);
        }
        return redirect('Producto/listado');

    }

    public function ajax_verifica_codigo()
    {

    }

    public function elimina_imagen(Request $request, $imagen_id, $producto_id)
    {
        ImagenesProducto::destroy($imagen_id);
        return response()->json([
            'sw' => 1
        ]);
    }

    public function ajaxMuestraImgProducto(Request $request, $producto_id)
    {
        $imagenes_producto = ImagenesProducto::where('producto_id', $producto_id)->get();
        return view('producto.ajaxMuestraImgProducto')->with(compact('imagenes_producto'));
    }

    public function panelControl(Request $request)
    {
        return view('producto.panelControl');
    }

    function extraeCodigo($texto)
    {
        $palabra = explode(" ", $texto);
        $primeras = Str::substr($palabra[0], 0, 3);
        $sigla = str_replace(" ", "", $primeras);
        return $sigla;
    }
}
