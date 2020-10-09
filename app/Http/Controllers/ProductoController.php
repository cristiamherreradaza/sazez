<?php

namespace App\Http\Controllers;

use Session;
use App\Tipo;
use App\Marca;
use App\Venta;
use App\Escala;
use App\Precio;
use DataTables;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\Movimiento;
use App\Parametros;
use App\Caracteristica;
use App\Cupone;
use App\Configuracione;
use App\ImagenesProducto;
use App\CategoriasProducto;
use App\CombosProducto;
use App\PedidosProducto;
use App\VentasProducto;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ProductosExport;
use App\Imports\ProductosImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    public function panelControl(Request $request)
    {
        $datosDispositivo = $_SERVER['HTTP_USER_AGENT'];
        preg_match('#\((.*?)\)#', $datosDispositivo, $match);
        $request->session()->put('dispositivo', $match[1]);

        // validamos la fecha de la facturacion vigente
        $hoy = date('Y-m-d');

        $ultimoParametro = Parametros::where('almacene_id', Auth::user()->almacen_id)
                            ->latest()
                            ->first();
        if($ultimoParametro != null && $ultimoParametro->fecha_limite < $hoy)
        {
            $parametro = Parametros::find($ultimoParametro->id);
            $parametro->estado = 'Expirado';
            $parametro->save();
        }
        // fin validamos la fecha de la facturacion vigente

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
        $marcas = Marca::get();
        $tipos = Tipo::get();
        return view('producto.listado')->with(compact('marcas', 'tipos'));
    }

    public function ajax_listado(Request $request)
    {
        //Modo Estatico
        // $productos_en_tienda = Movimiento::where('almacene_id', Auth::user()->almacen->id)
        //             ->where('estado', $request->estado)
        //             ->groupBy('producto_id')
        //             ->get();

        // Se saca el listado de los productos que se encuentran en el almacen correspondiente
        $consulta = Movimiento::where('almacene_id', Auth::user()->almacen->id);
        if ($request->estado == 'Defectuoso') {
            $consulta = $consulta->where('estado', $request->estado);
        }
        $productos_en_tienda = $consulta->groupBy('producto_id')->get();
        
        // Variable de ayuda para el listado
        $estado = $request->estado;
                
        // En un array guardaremos los id's de los productos de ese almacen
        $array_productos = array();
        foreach($productos_en_tienda as $row){
            array_push($array_productos, $row->producto_id);
        }

        // Capturaremos los datos de todos los productos en el almacen, de acuerdo a la solicitud del filtro
        $query = Producto::whereIn('id', $array_productos);
        if ($request->codigo) {
            $query = $query->where('codigo', 'like', "%$request->codigo%");
        }
        if ($request->nombre) {
            $query = $query->where('nombre', 'like', "%$request->nombre%");
        }
        if ($request->tipo) {
            $query = $query->where('tipo_id', $request->tipo);
        }
        if ($request->marca) {
            $query = $query->where('marca_id', $request->marca);
        }
        $productos = $query->get();
        return view('producto.ajax_listado')->with(compact('productos', 'estado'));
    }

    public function guarda(Request $request)
    {
        // dd($request->all());
        $configuracion = Configuracione::where('descripcion', 'generacionCodigos')->first();
        
        if ($configuracion->valor == 'No') {
            $codigoGenerado = $request->codigo;
        } else {
            $marcaProducto = Marca::find($request->marca_id);
            $tipoProducto = Tipo::find($request->tipo_id);
            $nombreProducto = $request->nombre;

            $siglaMarca = $this->extraeCodigo($marcaProducto->nombre);
            $siglaTipo = $this->extraeCodigo($tipoProducto->nombre);
            $siglaNombre = $this->extraeCodigo($nombreProducto);
            $codigoGenerado = $siglaMarca . '-' . $siglaTipo . '-' . $siglaNombre;
        }

        if ($request->producto_id) {
            $nuevoProducto = Producto::find($request->producto_id);

        }else{
            $nuevoProducto = new Producto();
        }

        $nuevoProducto->user_id          = Auth::user()->id;
        $nuevoProducto->marca_id         = $request->marca_id;
        $nuevoProducto->tipo_id          = $request->tipo_id;
        $nuevoProducto->codigo           = $codigoGenerado;
        $nuevoProducto->nombre           = $request->nombre;
        $nuevoProducto->nombre_venta     = $request->nombre_venta;
        $nuevoProducto->modelo           = $request->modelo;
        $nuevoProducto->precio_compra    = $request->precio_compra;
        $nuevoProducto->cantidad_minima  = $request->cantidad_minima;
        $nuevoProducto->dias_garantia    = $request->dias_garantia;
        $nuevoProducto->largo            = $request->largo;
        $nuevoProducto->ancho            = $request->ancho;
        $nuevoProducto->alto             = $request->alto;
        $nuevoProducto->peso             = $request->peso;
        $nuevoProducto->colores          = $request->colores;
        $nuevoProducto->descripcion      = $request->descripcion;
        $nuevoProducto->url_referencia   = $request->url_referencia;
        $nuevoProducto->video            = $request->video;
        $nuevoProducto->publicado        = "Si";
        $nuevoProducto->pagina_principal = "No";
        $nuevoProducto->save();
        $producto_id = $nuevoProducto->id;

        if ($request->has('producto_id')) 
        {
            // borramos los las caracteristicas, categorias y precios para editar el producto
            $producto_id          = $request->producto_id;
            $borraCaracteristicas = Caracteristica::where('producto_id', $producto_id)->delete();
            $borraCategorias      = CategoriasProducto::where('producto_id', $producto_id)->delete();
            $borraPrecios         = Precio::where('producto_id', $producto_id)->delete();

            if ($configuracion->valor == 'Si') {
                 // asignamos un numero al codigo
                $cambia_codigo = Producto::find($producto_id);
                $numeroProducto = str_pad($producto_id, 5, "0", STR_PAD_LEFT);
                $cambia_codigo->codigo = $codigoGenerado.'-'.$numeroProducto;
                $cambia_codigo->save();
            }

        } else {

            if ($configuracion->valor == 'Si') {
                // $producto_id = $nuevoProducto->id;
                $cambia_codigo = Producto::find($producto_id);
                $numeroProducto = str_pad($producto_id, 5, "0", STR_PAD_LEFT);
                $cambia_codigo->codigo = $codigoGenerado . '-' . $numeroProducto;
                $cambia_codigo->save();
            }
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
                if ($request->precio_venta[$ll]>0) {
                    $nuevoPrecio              = new Precio();
                    $nuevoPrecio->user_id     = Auth::user()->id;
                    $nuevoPrecio->producto_id = $producto_id;
                    $nuevoPrecio->escala_id   = $ll;
                    $nuevoPrecio->precio      = $request->precio_venta[$ll];
                    $nuevoPrecio->save();
                }
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
            $movimiento                = new Movimiento();
            $movimiento->user_id       = Auth::user()->id;
            $movimiento->producto_id   = $producto_id;
            $movimiento->almacene_id   = $request->almacene_id;
            $movimiento->tipo_id       = $request->tipo_id;
            $movimiento->fecha         = date("Y-m-d H:i:s");
            $movimiento->precio_compra = $request->precio_compra;
            $movimiento->ingreso       = $request->cantidad;
            $movimiento->dispositivo   = session('dispositivo');
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
        $almacenes = Almacene::orderBy('nombre', 'asc')->whereNull('estado')->get();
        $categorias_productos = CategoriasProducto::where('producto_id', $id)->get();
        return view('producto.muestra')->with(compact('producto', 'categorias', 'categorias_productos', 'almacenes'));
    }

    public function info()
    {
        return view('producto.info');
    }

    // Funcion que elimina el producto seleccionado desde lista de productos
    public function elimina(Request $request, $productoId)
    {
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
        // Movimientos
        Movimiento::where('producto_id', $productoId)->delete();
        return redirect('Producto/listado');
    }

    public function exportar()
    {
        $date = strtotime(date('Y-m-d H:i:s'));
        return Excel::download(new ProductosExport, "Listado_productos_$date.xlsx");
    }

    public function garantia()
    {
        $almacenes = Almacene::get();
        $tipos = Tipo::get();
        return view('producto.garantia')->with(compact('almacenes', 'tipos'));
    }

    public function ajaxProductosGarantia(Request $request)
    {
        $tipo_id = $request->tipo;
        $productos = Producto::where('tipo_id', $tipo_id)
                            ->get();
        return view('producto.ajaxProductosGarantia')->with(compact('productos'));
    }

    public function guardaGarantia(Request $request)
    {
        //dd($request->producto_id);
        foreach($request->producto_id as $producto_id)
        {
            $producto = Producto::find($producto_id);
            if($producto)
            {
                $producto->dias_garantia = $request->dias;
                $producto->save();
            }
        }
        Session::flash('success','Se guardo correctamente!');
        return back();
    }

    public function ajaxListaIngresos()
    {
        $ingresos = Movimiento::where('movimientos.estado', '=', 'Ingreso')
                            ->where('movimientos.ingreso', '>', 0)
                            ->whereNotNull('numero_ingreso')
                            ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
                            ->leftJoin('users', 'movimientos.user_id', '=', 'users.id')
                            //->distinct()
                            ->select(
                                'movimientos.numero_ingreso',
                                'almacenes.nombre',
                                'users.name',
                                'movimientos.fecha',
                                'movimientos.estado'
                            )
                            ->groupBy('movimientos.numero_ingreso')
                            ->orderBy('movimientos.id', 'desc');
        if(Auth::user()->perfil_id != 1){
            $ingresos->where('movimientos.almacene_id', Auth::user()->almacen->id);
        }
        return Datatables::of($ingresos)
                ->addColumn('action', function ($ingresos) {
                    return '<button onclick="ver_pedido(' . $ingresos->numero_ingreso . ')" class="btn btn-info" title="Ver detalle"><i class="fas fa-eye"></i></button>';
                })
                ->make(true); 
    }

    public function listadoIngresos()
    {
        return view('producto.listadoIngresos');

    }

    public function ver_ingreso($id)
    {
        $datos = Movimiento::where('numero_ingreso', $id)
                            ->where('ingreso', '>', 0)
                            ->first();
        $productos = Movimiento::where('numero_ingreso', $id)
                                ->where('ingreso', '>', 0)
                                ->get();
        
        if($datos->numero_ingreso_envio){
            // Redirecciona a la pagina con detalle del ingreso y envio
            //dd('Ingreso con envio');
            $datos_envio = Movimiento::where('numero_ingreso_envio', $datos->numero_ingreso_envio)
                                    ->where('ingreso', '>', 0)
                                    ->where('estado', 'Envio')
                                    ->whereNotNull('almacen_origen_id')
                                    ->first();
            $productos_envio = Movimiento::where('numero_ingreso_envio', $datos->numero_ingreso_envio)
                                        ->where('estado', 'Envio')
                                        ->where('ingreso', '>', 0)
                                        ->get();
            return view('producto.ver_ingreso_envio')->with(compact('datos', 'datos_envio', 'productos', 'productos_envio'));
        }else{
            // Redirecciona a la pagina con detalle del ingreso
            //dd('Solo Ingreso');
            return view('producto.ver_ingreso')->with(compact('datos', 'productos'));
        }
        
    }

    public function ajaxBuscaIngresoProducto(Request $request)
    {
        $almacen_id = $request->almacen;   
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('producto.listadoIngresoProductosAjax')->with(compact('productos', 'almacen_id'));
    }

    public function adicionaProducto(Request $request)
    {
        if($request->producto_id){
            // Buscaremos si ya existe ese producto en ese ingreso
            $producto_lista = Movimiento::where('numero_ingreso', $request->numero_ingreso)
                                        ->where('producto_id', $request->producto_id)
                                        ->where('estado', 'Ingreso')
                                        ->first();
            
            if(!$producto_lista){    // En caso de no encontrarlo se creara los registros a ese ingreso/producto
                if($request->numero_ingreso_envio){
                    //dd($producto_lista);
                    $dato = Movimiento::where('numero_ingreso_envio', $request->numero_ingreso_envio)
                                    ->whereNotNull('almacen_origen_id')
                                    ->where('estado', 'Envio')
                                    ->first();
                    // Buscamos al producto
                    $producto = Producto::find($request->producto_id);
                    // AQUI INGRESAMOS EL MATERIAL AL ALMACEN 
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $request->producto_id;
                    $ingreso->tipo_id = $producto->tipo_id;
                    $ingreso->almacene_id = $request->almacen_ingreso;
                    $ingreso->proveedor_id = $request->proveedor_id;
                    $ingreso->ingreso = $request->producto_cantidad;
                    $ingreso->fecha = date('Y-m-d H:i:s');
                    $ingreso->numero_ingreso = $request->numero_ingreso;
                    $ingreso->numero_ingreso_envio = $request->numero_ingreso_envio;
                    $ingreso->estado = 'Ingreso';
                    $ingreso->dispositivo = session('dispositivo');
                    $ingreso->save();
                    // Creación de Movimiento - Sale de Almacen Central
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $request->producto_id;
                    $ingreso->tipo_id = $producto->tipo_id;
                    $ingreso->almacene_id = $request->almacen_ingreso;              // Siempre sera 1?
                    $ingreso->salida = $request->producto_cantidad;
                    $ingreso->estado = 'Envio';           //Ingreso/Envio/Salida
                    $ingreso->numero = $dato->numero;
                    $ingreso->numero_ingreso_envio = $request->numero_ingreso_envio;
                    $ingreso->fecha = date('Y-m-d H:i:s');
                    $ingreso->dispositivo = session('dispositivo');
                    $ingreso->save();
                    // Creación de Movimiento - Ingresa a la Sucursal
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $request->producto_id;
                    $ingreso->tipo_id = $producto->tipo_id;
                    $ingreso->almacen_origen_id = $request->almacen_ingreso;        // Siempre sera 1?
                    $ingreso->almacene_id = $dato->almacene_id;
                    $ingreso->ingreso = $request->producto_cantidad;
                    $ingreso->estado = 'Envio';           //Ingreso/Envio
                    $ingreso->numero = $dato->numero;
                    $ingreso->numero_ingreso_envio = $request->numero_ingreso_envio;
                    $ingreso->fecha = date('Y-m-d H:i:s');
                    $ingreso->dispositivo = session('dispositivo');
                    $ingreso->save();
                }else{
                    // Buscamos al producto
                    $producto = Producto::find($request->producto_id);
                    //AQUI INGRESAMOS EL MATERIAL AL ALMACEN 
                    $ingreso = new Movimiento();
                    $ingreso->user_id = Auth::user()->id;
                    $ingreso->producto_id = $request->producto_id;
                    $ingreso->tipo_id = $producto->tipo_id;
                    $ingreso->almacene_id = $request->almacen_ingreso;
                    $ingreso->proveedor_id = $request->proveedor_id;
                    $ingreso->ingreso = $request->producto_cantidad;
                    $ingreso->fecha = date('Y-m-d H:i:s');
                    $ingreso->numero_ingreso = $request->numero_ingreso;
                    $ingreso->estado = 'Ingreso';
                    $ingreso->dispositivo = session('dispositivo');
                    $ingreso->save();
                }
            }
        }
        return redirect("Producto/ver_ingreso/$request->numero_ingreso");
    }

    public function eliminaProducto($id)
    {
        $datosMovimiento = Movimiento::find($id);
        $id_producto = $datosMovimiento->producto_id;
        $numero_ingreso = $datosMovimiento->numero_ingreso;
        if($datosMovimiento->numero_ingreso_envio){
            $numero_ingreso_envio = $datosMovimiento->numero_ingreso_envio;
            $registros = Movimiento::where('producto_id', $id_producto)
                                ->where('numero_ingreso_envio', $numero_ingreso_envio)
                                ->get();
            foreach($registros as $registro){
                $registro->delete();
            }
        }else{
            $registros = Movimiento::where('producto_id', $id_producto)
                                ->where('numero_ingreso', $numero_ingreso)
                                ->where('estado', 'Ingreso')
                                ->get();
            foreach($registros as $registro){
                $registro->delete();
            }
        }
        return redirect("Producto/ver_ingreso/$numero_ingreso");
    }

    public function eliminaIngreso($id)
    {
        $datos = Movimiento::where('estado', 'Ingreso')
                            ->where('numero_ingreso', $id)
                            ->first();
        if($datos->numero_ingreso_envio){
            $registros_ingreso = Movimiento::where('numero_ingreso_envio', $datos->numero_ingreso_envio)
                                            ->get();
            foreach($registros_ingreso as $registro){
                $registro->delete();
            }
        }else{
            $registros_ingreso = Movimiento::where('estado', 'Ingreso')
                                            ->where('numero_ingreso', $id)
                                            ->get();
            foreach($registros_ingreso as $registro){
                $registro->delete();
            }
        }
        return redirect('Producto/listadoIngresos');
    }

    public function discontinua($id)
    {
        $producto = Producto::find($id);
        $producto->estado = 'Discontinuo';
        $producto->save();
        return redirect('Producto/listado');
    }

    public function continua($id)
    {
        $producto = Producto::find($id);
        $producto->estado = NULL;
        $producto->save();
        return redirect('Producto/listado');
    }

    public function vista_previa_ingreso($id)
    {
        $productos_envio = Movimiento::where('estado', 'Ingreso')
                                    ->where('numero_ingreso', $id)
                                    ->where('ingreso', '>', 0)
                                    ->get();
        $cantidad_producto = Movimiento::where('estado', 'Ingreso')
                                        ->where('numero_ingreso', $id)
                                        ->where('ingreso', '>', 0)
                                        ->count();
        $detalle = Movimiento::where('estado', 'Ingreso')
                            ->where('numero_ingreso', $id)
                            ->where('ingreso', '>', 0)
                            ->first();
        $complemento = 20 - $cantidad_producto;
        //dd($complemento);
        return view('producto.vista_previa_ingreso')->with(compact('productos_envio', 'detalle', 'cantidad_producto', 'complemento'));
    }

    public function ajaxInformacion(Request $request)
    {
        $producto_id = $request->producto_id;
        $datosProducto = Producto::find($producto_id);
        $cantidadTotal = Movimiento::select(
            DB::raw('SUM(movimientos.ingreso) - SUM(movimientos.salida) as total'),
            'almacenes.nombre as almacen'
        )
        ->leftJoin('almacenes', 'movimientos.almacene_id', '=', 'almacenes.id')
        ->where('movimientos.producto_id', $producto_id)
        ->groupBy('movimientos.almacene_id')
        ->get();

        $precios = Precio::where('producto_id', $producto_id)
                    ->get();
        
        return view('producto.ajaxInformacion')->with(compact('cantidadTotal', 'datosProducto', 'precios'));
    }

}