<?php

namespace App\Http\Controllers;

use QrCode;
use App\User;
use App\Combo;
use App\Grupo;
use App\Venta;
use Exception;
use App\Cupone;
use DataTables;
use App\Almacene;
use App\Producto;
use App\GruposUser;
use App\Movimiento;
use App\CombosProducto;
use App\CuponesCliente;
use App\CuponesCobrado;
use App\Mail\CuponMail;
use App\VentasProducto;
use App\Mail\PruebaMail;
use Illuminate\Http\File;
use App\Mail\PromocionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CuponController extends Controller
{
    public function nuevo()
    {
        $grupos = Grupo::get();
        $clientes = User::where('rol', 'Cliente')->get();
        $almacenes = Almacene::whereNull('estado')->get();
        $promociones = Combo::whereDate('fecha_final', '>=', date('Y-m-d'))->get();
        return view('cupon.nuevo')->with(compact('almacenes', 'clientes', 'promociones', 'grupos'));
    }

    public function listado()
    {
        /*$cupones = Cupone::limit(100)
                        ->orderBy('id', 'desc')
                        ->get();*/
        // dd($cupones);
        $cupones = Cupone::get();
        $almacenes = Almacene::get();
        $clientes = User::where('rol', 'Cliente')->get();
        return view('cupon.listado')->with(compact('almacenes', 'cupones', 'clientes'));
    }

    public function ajax_listado()
    {
        $rol = Auth::user()->rol;

        if($rol == 'Administrador'){

            $cupones = DB::table('cupones')
                        ->whereNull('cupones.deleted_at')
                        ->leftJoin('productos', 'cupones.producto_id', '=', 'productos.id')
                        ->leftJoin('combos', 'cupones.combo_id', '=', 'combos.id')
                        ->leftJoin('users', 'cupones.cliente_id', '=', 'users.id')
                        ->leftJoin('almacenes', 'cupones.almacene_id', '=', 'almacenes.id')
                        ->leftJoin('cupones_cobrados', 'cupones.id', '=', 'cupones_cobrados.cupone_id')
                        ->select(
                            'cupones.id',
                            'cupones.codigo as codigo',
                            'users.id as cliente_id',
                            'users.name as cliente_nombre',
                            'productos.id as producto_id',
                            'productos.nombre as producto_nombre',
                            'combos.id as combo_id',
                            'combos.nombre as combo_nombre',
                            'almacenes.nombre as tienda',
                            'cupones_cobrados.fecha as cobrado',
                            'cupones.fecha_inicio as fecha_inicio',
                            'cupones.fecha_final as fecha_final',
                            'cupones.estado as estado'
                        )
                        ->orderBy('id', 'desc');

        }else{

            $cupones = DB::table('cupones')
                        ->whereNull('cupones.deleted_at')
                        ->where(function($query){
                            $query->where('cupones.almacene_id', Auth::user()->almacen->id)
                            ->orwhereNull('cupones.almacene_id');
                        })
                        ->leftJoin('productos', 'cupones.producto_id', '=', 'productos.id')
                        ->leftJoin('combos', 'cupones.combo_id', '=', 'combos.id')
                        ->leftJoin('users', 'cupones.cliente_id', '=', 'users.id')
                        ->leftJoin('almacenes', 'cupones.almacene_id', '=', 'almacenes.id')
                        ->leftJoin('cupones_cobrados', 'cupones.id', '=', 'cupones_cobrados.cupone_id')
                        ->select(
                            'cupones.id',
                            'cupones.codigo as codigo',
                            'users.id as cliente_id',
                            'users.name as cliente_nombre',
                            'productos.id as producto_id',
                            'productos.nombre as producto_nombre',
                            'combos.id as combo_id',
                            'combos.nombre as combo_nombre',
                            'almacenes.nombre as tienda',
                            'cupones_cobrados.fecha as cobrado',
                            'cupones.fecha_inicio as fecha_inicio',
                            'cupones.fecha_final as fecha_final',
                            'cupones.estado as estado'
                        )
                        ->orderBy('id', 'desc');
        }

        return Datatables::of($cupones)->addColumn('action', function ($cupones) {
            // Si el usuario tiene perfil de administrador 
            if(Auth::user()->perfil_id == 1)
            {
                // Si no se cobró el cupón, 
                if($cupones->estado == 'Vigente')
                {
                    // Si la fecha_fin del cupon es menor a la fecha actual, muestra todos los botones (Ver Cupon, Cobrar, ELiminar)
                    if(strtotime($cupones->fecha_final) >= strtotime(date('Y-m-d H:i:s')))
                    {
                        return '<button onclick="generaQr('.$cupones->id.')" id="boton-'.$cupones->id.'" class="btn waves-effect waves-light btn-outline-dark" title="Genera QR"><i class="fas fa-qrcode"></i> </button>
                                <button onclick="cobrar('.$cupones->id.')" class="btn btn-primary" title="Cobrar cupon"><i class="fas fa-laptop"></i> </button>
                                <button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>
                                <button onclick="eliminar('.$cupones->id.')" class="btn btn-danger" title="Eliminar cupon"><i class="fas fa-trash-alt"></i></button>';
                    }
                    else
                    {
                        return '<button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>';
                    }
                }
                // Si se cobró el cupón, muestra el boton Ver Cupon
                else
                {
                    return '<button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>';
                }
            }
            // Si el usuario no tiene perfil de administrador
            else
            {
                // Si no se cobró el cupón, muestra los botones Ver Cupon y Cobrar
                if($cupones->estado == 'Vigente')
                {
                    // Si la fecha_fin del cupon es menor a la fecha actual, muestra todos los botones (Ver Cupon, Cobrar, ELiminar)
                    if(strtotime($cupones->fecha_final) >= strtotime(date('Y-m-d H:i:s')))
                    //if(strtotime(date('Y-m-d H:i:s')) >= strtotime($cupones->fecha_final))
                    {
                        return '<button onclick="cobrar('.$cupones->id.')" class="btn btn-primary" title="Cobrar cupon"><i class="fas fa-laptop"></i> </button>
                                <button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>';
                    }
                    else
                    {
                        return '<button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>';
                    }
                }
                // Si se cobró el cupón, muestra el boton Ver Cupon
                else
                {
                    return '<button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>';
                }
            }
            // if(is_null($cupones->cobrado)){
            //     if(strtotime(date('Y-m-d H:i:s')) >= strtotime($cupones->fecha_final)){
            //         return '<button onclick="eliminar('.$cupones->id.')" class="btn btn-danger" title="Eliminar cupon"><i class="fas fa-trash-alt"></i></button>';
            //     }else{
            //         return '<button onclick="cobrar('.$cupones->id.', \''.$cupones->cliente_id.'\', \''.$cupones->producto_id.'\', \''.$cupones->combo_id.'\')" class="btn btn-primary" title="Cobrar cupon"><i class="fas fa-laptop"></i> </button>
            //         <button onclick="ver('.$cupones->id.')" class="btn btn-info" title="Vista impresion cupon"><i class="fas fa-eye"></i> </button>
            //         <button onclick="eliminar('.$cupones->id.')" class="btn btn-danger" title="Eliminar cupon"><i class="fas fa-trash-alt"></i></button>';
            //     }
            // }
        })
        ->make(true);
    }

    public function cobra_cupon($id)
    {
        $cupon = Cupone::find($id);
        return view('cupon.cobra_cupon')->with(compact('cupon'));
    }

    // public function ajaxMuestraCupon(Request $request)
    // {
    //     $cupon = Cupone::find($request->cupon_id);
    //     $cliente = User::find($request->cliente_id);
    //     if($request->producto_id){
    //         $producto = Producto::find($request->producto_id);
    //         return view('cupon.ajaxMuestraCupon')->with(compact('cupon', 'cliente', 'producto'));
    //     }else{
    //         $producto=NULL;
    //         $combo = Combo::find($request->combo_id);
    //         $productos_combo = CombosProducto::where('combo_id', $combo->id)->get();
    //         return view('cupon.ajaxMuestraCupon')->with(compact('cupon', 'cliente', 'producto', 'combo', 'productos_combo'));
    //     }
    // }

    public function ver($id)
    {
        $cupon = Cupone::find($id);
        
        if($cupon->producto_id)
        {
            $producto = Producto::find($cupon->id);
            return view('cupon.ver_promocion')->with(compact('cupon', 'producto'));
        }
        else
        {
            $promocion = Combo::find($cupon->combo_id);
            $productos_promocion = CombosProducto::where('combo_id', $promocion->id)->get();
            return view('cupon.ver_promocion')->with(compact('cupon', 'promocion', 'productos_promocion'));
        }
        
    }

    public function ajaxBuscaProducto(Request $request)
    {
        $productos = Producto::where('nombre', 'like', "%$request->termino%")
                            ->orWhere('codigo', 'like', "%$request->termino%")
                            ->limit(8)
                            ->get();
        return view('cupon.ajaxBuscaProducto')->with(compact('productos'));
    }

    public function guardar(Request $request)
    {
        $cupon = new Cupone();

        $cupon->user_id      = Auth::user()->id;
        $cupon->almacene_id  = $request->tienda;
        $cupon->descuento    = $request->producto_descuento;
        $cupon->producto_id  = $request->producto_id;
        $cupon->combo_id     = $request->combo_id;
        $cupon->monto_total  = $request->producto_total;
        $cupon->fecha_inicio = $request->fecha_inicio;
        $cupon->fecha_final  = $request->fecha_fin;
        $cupon->estado       = 'Vigente';
        
        $cupon->save();

        return redirect('Cupon/listado');
    }

    public function cobrar(Request $request)
    {
        // Registramos en Ventas
        $venta = new Venta();
        $venta->user_id = Auth::user()->id;
        $venta->almacene_id = Auth::user()->almacen->id;
        $venta->cliente_id = $request->cobro_cliente_id;
        $venta->total = $request->cobro_total;
        $venta->fecha = date('Y-m-d');
        $venta->estado = 'Cupon';
        $venta->save();

        // Cambiamos su estado de 'Vigente' a 'Cobrado'
        $cupon = Cupone::find($request->cobro_cupon_id);
        $cupon->estado = 'Cobrado';
        $cupon->save();

        if($request->cobro_producto_id){        //Si es cupon por un producto
            // Buscamos al producto
            $item = Producto::find($request->cobro_producto_id);
            // Registramos en Ventas_producto
            $ventaProducto = new VentasProducto();
            $ventaProducto->user_id = Auth::user()->id;
            $ventaProducto->producto_id = $request->cobro_producto_id;
            $ventaProducto->tipo_id = $item->tipo_id;
            $ventaProducto->cupon_id = $request->cobro_cupon_id;
            $ventaProducto->venta_id = $venta->id;
            $ventaProducto->precio_venta = $request->cobro_total;
            $ventaProducto->precio_cobrado = $request->cobro_total;
            $ventaProducto->cantidad = 1;
            $ventaProducto->fecha = date('Y-m-d');
            $ventaProducto->fecha_garantia = Carbon::now()->addDay($item->dias_garantia);
            $ventaProducto->save();
        }else{                                  //Es cupon por una promocion
            $productos_combo = CombosProducto::where('combo_id', $request->cobro_combo_id)->get();
            foreach($productos_combo as $producto){
                // Buscamos al producto
                $item = Producto::find($producto->producto_id);
                // Registramos en Ventas_producto
                $ventaProducto = new VentasProducto();
                $ventaProducto->user_id = Auth::user()->id;
                $ventaProducto->producto_id = $producto->producto_id;
                $ventaProducto->tipo_id = $item->tipo_id;
                $ventaProducto->combo_id = $request->cobro_combo_id;
                $ventaProducto->cupon_id = $request->cobro_cupon_id;
                $ventaProducto->venta_id = $venta->id;
                $ventaProducto->precio_venta = $producto->precio;
                $ventaProducto->precio_cobrado = $producto->precio;
                $ventaProducto->cantidad = $producto->cantidad;
                $ventaProducto->fecha = date('Y-m-d');
                $ventaProducto->fecha_garantia = Carbon::now()->addDay($item->dias_garantia);
                $ventaProducto->save();
            }
        }
        
        if($request->cobro_producto_id){
            // Buscamos al producto
            $item = Producto::find($request->cobro_producto_id);
            // Registrar en Movimientos
            $movimiento = new Movimiento();
            $movimiento->user_id = Auth::user()->id;
            $movimiento->producto_id = $request->cobro_producto_id;
            $movimiento->tipo_id = $item->tipo_id;
            $movimiento->almacene_id = Auth::user()->almacen->id;
            $movimiento->cliente_id = $request->cobro_cliente_id;
            $movimiento->venta_id = $venta->id;
            $movimiento->cupon_id = $request->cobro_cupon_id;
            $movimiento->precio_venta = $request->cobro_total;
            $movimiento->salida = 1;
            $movimiento->fecha = date('Y-m-d H:i:s');
            $movimiento->dispositivo  = session('dispositivo');
            $movimiento->estado = 'Cupon';
            $movimiento->save();
        }else{
            $productos_combo = CombosProducto::where('combo_id', $request->cobro_combo_id)->get();
            foreach($productos_combo as $producto){
                // Buscamos al producto
                $item = Producto::find($producto->producto_id);
                // Registrar en Movimientos
                $movimiento = new Movimiento();
                $movimiento->user_id = Auth::user()->id;
                $movimiento->producto_id = $producto->producto_id;
                $movimiento->tipo_id = $item->tipo_id;
                $movimiento->almacene_id = Auth::user()->almacen->id;
                $movimiento->cliente_id = $request->cobro_cliente_id;
                $movimiento->venta_id = $venta->id;
                $movimiento->combo_id = $request->cobro_combo_id;
                $movimiento->cupon_id = $request->cobro_cupon_id;
                $movimiento->precio_venta = $producto->precio;
                $movimiento->salida = $producto->cantidad;
                $movimiento->fecha = date('Y-m-d H:i:s');
                $movimiento->dispositivo  = session('dispositivo');
                $movimiento->estado = 'Cupon';
                $movimiento->save();
            }
        }
        
        // Actualizamos datos del cliente
        $cliente = User::find($request->cobro_cliente_id);
        $cliente->name = $request->cobro_nombre;
        $cliente->ci = $request->cobro_ci;
        if(!$cliente->password){
            $cliente->password = Hash::make($cliente->email);
        }
        $cliente->celulares = $request->cobro_celular;
        $cliente->nit = $request->cobro_nit;
        $cliente->razon_social = $request->cobro_razon_social;
        $cliente->save();

        // Registramos la transaccion
        $datos_cupon = new CuponesCobrado();
        $datos_cupon->cupone_id = $request->cobro_cupon_id;
        $datos_cupon->cobrador_id = Auth::user()->id;
        $datos_cupon->almacene_id = Auth::user()->almacen->id;
        $datos_cupon->fecha = date('Y-m-d H:i:s');
        $datos_cupon->save();

        return redirect('Cupon/listado');
    }

    public function codigoGenerador()
    {
        //aqui generaremos el codigo
        $length = 14;
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $clave_privada="";
        for($i=0;$i<$length;$i++){
            if($i==4 || $i==9){
                $clave_privada .= '-';
            }else{
                $rand = rand() % strlen($charset);
                $clave_privada .= substr($charset, $rand, 1);
            }            
        }
        return $clave_privada;
    }

    public function eliminar(Request $request)
    {
        $cupon = Cupone::find($request->id);
        $cupon->delete();
        return redirect('Cupon/listado');
    }

    public function pruebaCorreo(Request $request)
    {
        $actual = strtotime(date('Y-m-d H:i:s'));
        $final = strtotime('2020-06-23 18:32:00');
        if($actual > $final){
            echo $actual ."<br>". $final;
            dd('Caduco');
        }else{
            echo $actual ."<br>". $final;
            dd('Vigente');
        }
        //$png = QrCode::format('png')->size(512)->generate('AAAA-BBBB-CCCC');
        //Storage::disk('qrs')->put('qwe.png', $png);

        //$filename = 'qwe.png';
        //request()->documento->move(public_path('qrs'), $filename);
        //request()->documento->move(public_path('images'), $filename);
        // $fichero = 'gente.txt';
        // // Abre el fichero para obtener el contenido existente
        // $actual = file_get_contents($fichero);
        // // Añade una nueva persona al fichero
        // $actual .= "John Smith\n";
        // // Escribe el contenido al fichero
        // file_put_contents($fichero, $actual);
        //Storage::putFileAs('photos', new File('/path/to/photo'), 'aaa1.png');
        //Storage::disk('public')->move('storage/app/aaa.png', 'public/qrs/aaa.png');
        //echo $var;
        //Storage::putFileAs('photos', new File(public_path() . '\qrs'), 'photo.png');
        //Storage::putFile($var, new File(public_path() . '\qrs'));
        // $png = base64_encode($png);
        // dd($png);
        // //echo "<img src='data:image/png;base64," . $png . "'>";

        $png="G2EF-3ZQB-R2W9";
        Mail::to("arielfernandez.rma7@gmail.com")->send(new PruebaMail($png));
    }

    public function pruebaImprime()
    {
        return view('cupon.prueba');
    }

    public function registraClienteCupon(Request $request)
    {
        // $verificaRegistroCupon = 'Si';

        $datosCupon = Cupone::find($request->cupon_id);

        $buscaUsuario = User::where('ci', $request->ci)
                    ->first();

        if($buscaUsuario == null){

            $buscaUsuarioNit = User::where('nit', $request->nit)
                            ->whereNotNull('nit')
                            ->first();
            // dd($buscaUsuarioNit);
            if ($buscaUsuarioNit != null) {

                $usuario = User::find($buscaUsuarioNit->id);
                $usuario->name         = $request->name;
                $usuario->ci           = $request->ci;
                $usuario->razon_social = $request->razon_social;
                $usuario->save();
                $clienteId = $buscaUsuarioNit->id;

            } else {

                $correoTemporal = date("YmdHis") . '@notiene.com';

                $usuario = new User();
                $usuario->name         = $request->name;
                $usuario->ci           = $request->ci;
                $usuario->razon_social = $request->razon_social;
                $usuario->email        = $correoTemporal;
                $usuario->password     = Hash::make('123456789');

                $usuario->save();
                $clienteId = $usuario->id;
            }

        }else{
            $clienteId = $buscaUsuario->id;

            $usuario = User::find($buscaUsuario->id);
            $usuario->name         = $request->name;
            $usuario->ci           = $request->ci;
            $usuario->razon_social = $request->razon_social;
            $usuario->save();
            // $clienteId = $buscaUsuarioNit->id;
        }

        // dd($clienteId);

        $verificaCupon = CuponesCliente::where('cupone_id', $datosCupon->id)
                                        ->where('cliente_id', $clienteId)
                                        ->first();
        // dd($verificaCupon);

        if($verificaCupon == null){

            $verificaRegistroCupon = 'No';

            $hoy = date("Y-m-d H:i:s");

            $cupon = new CuponesCliente();
            $cupon->cupone_id      = $datosCupon->id;
            $cupon->producto_id    = $datosCupon->producto_id;
            $cupon->combo_id       = $datosCupon->combo_id;
            $cupon->cliente_id     = $clienteId;
            $cupon->almacene_id    = $datosCupon->almacene_id;
            $cupon->fecha_creacion = $hoy;
            $cupon->descuento      = $datosCupon->descuento;
            $cupon->monto_total    = $datosCupon->monto_total;
            $cupon->fecha_inicio   = $datosCupon->fecha_inicio;
            $cupon->fecha_final    = $datosCupon->fecha_final;
            $cupon->save();

            $cuponId = $cupon->id;
        }else{

            $verificaRegistroCupon = 'Si';
            $cuponId = $verificaCupon->id;
        }

        $datosCuponRegistrado = CuponesCliente::find($cuponId);
        return view('cupon.registraClienteCupon')->with(compact('verificaRegistroCupon', 'datosCuponRegistrado'));
    }
}
