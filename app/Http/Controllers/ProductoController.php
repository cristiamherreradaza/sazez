<?php

namespace App\Http\Controllers;

use App\Tipo;
use App\Marca;
use App\Escala;
use App\Precio;
use App\Venta;
use DataTables;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\Movimiento;
use App\Caracteristica;
use App\ImagenesProducto;
use App\CategoriasProducto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\ProductosImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    public function panelControl(Request $request)
    {
        $fecha = new \DateTime();//aqui obtenemos la fecha y hora actual
        $fecha_actual = $fecha->format('Y-m-d');//obtenes la fecha actual
        $mes = $fecha->format('m');//obtenes la fecha actual
        $anio = $fecha->format('Y');//obtenes la fecha actual
        $anio_atr = date("Y-m",strtotime($fecha_actual."- 1 year"."+ 1 month"));
        $anio_atras = $anio_atr.'-01';
        // dd($anio_atras); 

        //OBTENEMOS LAS FECHA DEL COMIENZO Y FIN DE LAS SEMANA 
        if (date("D") == "Mon"){
            $inicio_semana = date("Y-m-d");
        } else {
            $inicio_semana = date("Y-m-d", strtotime('last Monday', time()));
        }
            $fin_semana = date("Y-m-d", strtotime('next Sunday', time()));

        $usuario = Auth::user()->name;
        // dd($usuario);
        if ($usuario == 'Administrador') {
        //OBTENEMOS LAS VENTAS DIARIAS GLOBALES
        $venta_diaria = Venta::where('fecha','=',$fecha_actual) 
                ->select('*')
                ->count('fecha');

        //OBTENEMOS LAS VENTAS SEMANALES GLOBALES
        $venta_semanal = Venta::whereBetween('fecha', [$inicio_semana, $fin_semana]) 
                ->select('*')
                ->count('fecha');

        //OBTENEMOS LAS VENTAS MENSUALES GLOBALES
        $venta_mensual = Venta::whereMonth('fecha', $mes)
                ->whereYear('fecha', $anio) 
                ->select('*')
                ->count('fecha');

        //OBTENEMOS LAS VENTAS ANUALES GLOBALES
        $venta_anual = Venta::whereYear('fecha', $anio) 
                ->select('*')
                ->count('fecha');

        //OBTENEMOS LAS VENTAS ANUALES POR MESES
        $anual_mes = DB::select("SELECT YEAR(fecha) AS anio, MONTH(fecha) AS mes,  COUNT(fecha) AS total
                                    FROM ventas
                                    WHERE fecha BETWEEN '$anio_atras' AND '$fecha_actual'
                                    GROUP BY YEAR(fecha) ASC, MONTH(fecha) ASC");

        // $otro = $this->anio_meses($anual_mes);

        //OBTENEMOS LOS PRODUCTOS MAS VENDIDOS DEL MES ACTUAL
        $productos_mas_vendidos = DB::select("SELECT prod.id, prod.codigo, prod.nombre, tmp.nro
                                                FROM productos prod, (SELECT producto_id, COUNT(producto_id) as nro
                                                                                FROM ventas_productos
                                                                                WHERE MONTH(fecha) = '$mes'
                                                                                GROUP BY producto_id DESC)tmp
                                                WHERE prod.id = tmp.producto_id");

        //OBTENEMOS LA LISTA DE PRODUCTOS CON SUS STOCK
        $stock_productos = DB::select("SELECT prod.codigo, prod.cantidad_minima, prod.id, prod.nombre, tmp.total
                                                FROM productos prod, (SELECT producto_id, (SUM(ingreso) - SUM(salida))as total
                                                                        FROM movimientos
                                                                        GROUP BY producto_id)tmp
                                                WHERE prod.id = tmp.producto_id
                                                ORDER BY tmp.total ASC");
        } else {
            
            $almacen_id = Auth::user()->almacen_id;
            // dd($almacen_id);
           //OBTENEMOS LAS VENTAS DIARIAS GLOBALES
            $venta_diaria = Venta::where('fecha','=',$fecha_actual)
                    ->where('almacene_id', $almacen_id) 
                    ->select('*')
                    ->count('fecha');

            //OBTENEMOS LAS VENTAS SEMANALES GLOBALES
            $venta_semanal = Venta::whereBetween('fecha', [$inicio_semana, $fin_semana])
                    ->where('almacene_id', $almacen_id)  
                    ->select('*')
                    ->count('fecha');

            //OBTENEMOS LAS VENTAS MENSUALES GLOBALES
            $venta_mensual = Venta::whereMonth('fecha', $mes)
                    ->where('almacene_id', $almacen_id) 
                    ->whereYear('fecha', $anio) 
                    ->select('*')
                    ->count('fecha');

            //OBTENEMOS LAS VENTAS ANUALES GLOBALES
            $venta_anual = Venta::whereYear('fecha', $anio)
                    ->where('almacene_id', $almacen_id)  
                    ->select('*')
                    ->count('fecha');

            //OBTENEMOS LAS VENTAS ANUALES POR MESES
            $anual_mes = DB::select("SELECT YEAR(fecha) AS anio, MONTH(fecha) AS mes,  COUNT(fecha) AS total
                                        FROM ventas
                                        WHERE fecha BETWEEN '$anio_atras' AND '$fecha_actual'
                                        GROUP BY YEAR(fecha) ASC, MONTH(fecha) ASC");

            // $otro = $this->anio_meses($anual_mes);

            //OBTENEMOS LOS PRODUCTOS MAS VENDIDOS DEL MES ACTUAL
            $productos_mas_vendidos = DB::select("SELECT DISTINCT prod.id, prod.codigo, prod.nombre, tmp.nro
                                                    FROM productos prod, ventas vent, (SELECT vent_prod.producto_id, COUNT(vent_prod.producto_id) as nro
                                                                                        FROM ventas ven, ventas_productos vent_prod
                                                                                        WHERE ven.almacene_id = '$almacen_id'
                                                                                        AND ven.id = vent_prod.venta_id
                                                                                        AND MONTH(vent_prod.fecha) = '$mes'
                                                                                        GROUP BY vent_prod.producto_id DESC)tmp
                                                    WHERE prod.id = tmp.producto_id");

            //OBTENEMOS LA LISTA DE PRODUCTOS CON SUS STOCK
            $stock_productos = DB::select("SELECT prod.codigo, prod.cantidad_minima, prod.id, prod.nombre, tmp.total
                                                    FROM productos prod, (SELECT producto_id, (SUM(ingreso) - SUM(salida))as total
                                                                            FROM movimientos
                                                                            WHERE almacene_id = '$almacen_id'
                                                                            GROUP BY producto_id)tmp
                                                    WHERE prod.id = tmp.producto_id
                                                    ORDER BY tmp.total ASC"); 
        }
        
        return view('producto.panelControl')->with(compact('venta_diaria', 'venta_semanal', 'venta_mensual', 'venta_anual','productos_mas_vendidos', 'stock_productos'));
    }

    public function anio_meses($anual_mes)
    {   
        $num = $anual_mes[0]->mes;
        $mes = $this->meses_literal($num);

        // DB::table('prueba')->insert([
        //     'mes_literal' => $mes,
        //     'cantidad' => $anual_mes[0]->total,
        //     'orden' => 1,
        //     ]);
        //     

        for ($i=1; $i < 13 ; $i++) {
            $num += 1;
            if (!empty($anual_mes[$i]->mes)) {
                if ($num == $anual_mes[$i]->mes) {
                    
                } else {

                }
            } else {
                
            }
            # code...
        }
        // foreach ($anual_mes as $value) {
        //     if ($num == $value->mes && $num < 12) {
                
        //     } else {

        //     }
        //     $dato = $value->mes;
        //     echo $dato.', ';
        // }
        // dd($anual_mes);
        // return $anual_mes;
    }

    public function meses_literal($num)
    {
        switch ($num) {
            case 1:
                return 'Ene';
                break;
            case 2:
                return 'Feb';
                break;
            case 3:
                return 'Mar';
                break;
            case 4:
                return 'Abr';
                break;
            case 5:
                return 'May';
                break;
            case 6:
                return 'Jun';
                break;
            case 7:
                return 'Jul';
                break;
            case 8:
                return 'Ago';
                break;
            case 9:
                return 'Sep';
                break;
            case 10:
                return 'Oct';
                break;
            case 11:
                return 'Nov';
                break;
            case 12:
                return 'Dic';
                break;
        }
    }

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
        return view('producto.listado');
    }

    public function ajax_listado()
    {
        // $lista_personal = Producto::all();
        // $productos = DB::table('productos')
        $productos = DB::table('productos')
            ->whereNull('productos.deleted_at')
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
                return '<button onclick="edita_producto(' . $productos->id . ')" class="btn btn-warning"><i class="fas fa-edit"></i> </button>
                <button onclick="muestra_producto(' . $productos->id . ')" class="btn btn-info"><i class="fas fa-eye"></i></button>
                <button onclick="elimina_producto(' . $productos->id . ',\''.$productos->codigo.'\')" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>';
            })
            ->make(true);    
    }

    public function guarda(Request $request)
    {
        $marcaProducto  = Marca::find($request->marca_id);
        $tipoProducto   = Tipo::find($request->tipo_id);
        $nombreProducto = $request->nombre;

        $siglaMarca = $this->extraeCodigo($marcaProducto->nombre);
        $siglaTipo = $this->extraeCodigo($tipoProducto->nombre);
        $siglaNombre = $this->extraeCodigo($nombreProducto);
        $codigoGenerado = $siglaMarca.'-'.$siglaTipo.'-'.$siglaNombre;
        // fin generacion codigo

        if ($request->producto_id) {
            $nuevoProducto = Producto::find($request->producto_id);

        }else{
            $nuevoProducto = new Producto();
        }
        // dd($request->all());
        $nuevoProducto->user_id        = Auth::user()->id;
        $nuevoProducto->marca_id       = $request->marca_id;
        $nuevoProducto->tipo_id        = $request->tipo_id;
        $nuevoProducto->codigo         = $codigoGenerado;
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
        // $producto_id = $nuevoProducto->id;

        if ($request->has('producto_id')) 
        {
            $producto_id          = $request->producto_id;
            $borraCaracteristicas = Caracteristica::where('producto_id', $producto_id)->delete();
            $borraCategorias      = CategoriasProducto::where('producto_id', $producto_id)->delete();
            $borraPrecios         = Precio::where('producto_id', $producto_id)->delete();
            
            $cambia_codigo = Producto::find($producto_id);
            $numeroProducto = str_pad($producto_id, 5, "0", STR_PAD_LEFT);
            $cambia_codigo->codigo = $codigoGenerado.'-'.$numeroProducto;
            $cambia_codigo->save();

            // $borraImagenes        = ImagenesProducto::where('producto_id', $producto_id)->delete();
        } else {
            $producto_id = $nuevoProducto->id;
            $cambia_codigo = Producto::find($producto_id);
            $numeroProducto = str_pad($producto_id, 5, "0", STR_PAD_LEFT);
            $cambia_codigo->codigo = $codigoGenerado.'-'.$numeroProducto;
            $cambia_codigo->save();
        }

        if ($request->has('caracteristica') != null) 
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
            $llaves = array_keys($request->precio_venta);
            foreach ($llaves as $key => $ll) {
                $nuevoPrecio              = new Precio();
                $nuevoPrecio->user_id     = Auth::user()->id;
                $nuevoPrecio->producto_id = $producto_id;
                $nuevoPrecio->escala_id   = $ll;
                $nuevoPrecio->precio      = $request->precio_venta[$ll];
                $nuevoPrecio->save();
            }

        }

        if ($request->has('fotos')) 
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

    function extraeCodigo($texto)
    {
        $palabra = explode(" ", $texto);
        $primeras = Str::substr($palabra[0], 0, 3);
        $sigla = str_replace(" ", "", $primeras);
        $siglaMayusculas = strtoupper($sigla);
        return $siglaMayusculas;
    }

    public function muestra($id)
    {
        $producto = Producto::find($id);
        $categorias = Categoria::get();
        $almacenes = Almacene::get();
        $categorias_productos = CategoriasProducto::where('producto_id', $id)->get();
        return view('producto.muestra')->with(compact('producto', 'categorias', 'categorias_productos', 'almacenes'));
    }

    public function info()
    {
        return view('producto.info');
    }

    public function elimina(Request $request, $productoId)
    {
        // dd($productoId);
        $producto = Producto::find($productoId);
        $producto->delete();
        // eliminamos las tablas relacionadas
        // caracteristicas
        Caracteristica::where('producto_id', $productoId)->delete();
        // precio
        Precio::where('producto_id', $productoId)->delete();
        // categorias 
        CategoriasProducto::where('producto_id', $productoId)->delete();
        // imagenes producto 
        ImagenesProducto::where('producto_id', $productoId)->delete();
        // combos productos 
        CombosProducto::where('producto_id', $productoId)->delete();
        // pedidos productos 
        PedidosProducto::where('producto_id', $productoId)->delete();
        // ventas 
        VentasProducto::where('producto_id', $productoId)->delete();
        // cupones 
        Cupone::where('producto_id', $productoId)->delete();
        return redirect('Producto/listado');
    }
}