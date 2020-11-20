<?php

namespace App\Http\Controllers;

use App\User;
use App\Cupone;
use App\Grupo;
use App\GruposUser;
use App\Combo;
use App\CombosProducto;
use App\CuponesCobrado;
use App\Almacene;
use App\Producto;
use App\Venta;
use App\VentasProducto;
use App\Movimiento;
use App\Mail\CuponMail;
use App\Mail\PromocionMail;
use App\Mail\PruebaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use QrCode;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;
use DataTables;

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
        $cupones = Cupone::get();
        $almacenes = Almacene::get();
        $clientes = User::where('rol', 'Cliente')->get();
        return view('cupon.listado')->with(compact('almacenes', 'cupones', 'clientes'));
    }

    public function ajax_listado()
    {
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
                        return '<button onclick="cobrar('.$cupones->id.')" class="btn btn-primary" title="Cobrar cupon"><i class="fas fa-laptop"></i> </button>
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
        $almacenes = Almacene::whereNull('estado')->get();
        if($cupon->producto_id)             // Es un cupon por un producto
        {
            return view('cupon.ver')->with(compact('cupon', 'almacenes'));
        }
        else                                // Es un cupon por una promocion
        {
            $promocion = Combo::find($cupon->combo_id);
            $productos_promocion = CombosProducto::where('combo_id', $promocion->id)->get();
            return view('cupon.ver_promocion')->with(compact('cupon', 'almacenes', 'promocion', 'productos_promocion'));
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
        // Debemos preguntarnos si el cupon es por un producto o por una promocion
        // Si tipo_oferta = 1 -> Producto O tipo_oferta = 2 -> Promocion
        if($request->tipo_oferta == "1")    // Cupon por un producto
        {
            // Debemos preguntarnos si el cupon es masivo o individual
            if($request->tipo_envio)        // Para una persona (individual)
            {
                // Entonces tiene que existir valores en tipo_envio y en (cliente o email)
                //dd('Existe tipo envio en producto');
                if($request->tipo_envio == "1")     // Medio a enviar es un cliente
                {
                    // Leer datos de id_cliente
                    if($request->cliente)
                    {
                        $cliente = User::find($request->cliente);
                        if($cliente)
                        {
                            // Se crea el codigo unico de cupon
                            $sw=1;
                            while($sw==1)
                            {
                                $codigo = $this->codigoGenerador();
                                $valor = Cupone::where('codigo', $codigo)->get();
                                if(count($valor)==0)
                                {
                                    $sw=0;
                                }
                            }
                            // Se crea el Cupon
                            $cupon = new Cupone();
                            $cupon->user_id = Auth::user()->id;
                            $cupon->producto_id = $request->producto_id;    //pendiente validar
                            $cupon->cliente_id = $cliente->id;
                            $cupon->almacene_id = $request->tienda;
                            $cupon->descuento = $request->producto_descuento;
                            $cupon->monto_total = $request->producto_total;
                            $cupon->codigo = $codigo;
                            $cupon->fecha_inicio = $request->fecha_inicio;
                            $cupon->fecha_final = $request->fecha_fin;
                            $cupon->save();
                            // Se preparan los datos para el envio del email
                            $producto = Producto::find($request->producto_id);
                            $tienda = Almacene::find($request->tienda);
                            if($tienda)             // Si existe una tienda especifica
                            {
                                $tienda = $tienda->nombre.', '.$tienda->direccion;
                            }
                            else                    // Si puede cobrar en cualquier tienda
                            {
                                $tienda = "Cualquier sucursal";
                            }
                            $message = [
                                'fecha_final' => $request->fecha_fin,
                                'producto' => $producto->nombre,
                                'precio_normal' => $request->producto_precio,
                                'precio_descuento' => $request->producto_total,
                                'tienda' => $tienda,
                            ];
                            // Creando imagen de QR
                            $png = QrCode::format('png')->color(1,126,191)->size(300)->generate($codigo);
                            Storage::disk('qrs')->put($codigo.'.png', $png);
                            // Se envia el email
                            Mail::to($cliente->email)->send(new CuponMail($message, $codigo));
                        }
                    }
                }
                else                                // Medio a enviar es un email
                {
                    // Leer datos de email
                    if($request->email)
                    {
                        $cliente = User::where('email', $request->email)->first();
                        // preguntar si cliente NO esta definido
                        if(!$cliente)
                        {
                            //dd('no existe email');
                            // preguntar por query builder si esta definido
                            $cliente = DB::table('users')->where('email', $request->email)->first();
                            if($cliente)
                            {
                                // SI esta en la bd pero esta borrado, habilitarlo
                                $cliente = DB::table('users')->where('email', $request->email)->update(['deleted_at' => NULL]);
                            }
                            else    
                            {
                                // NO esta en la bd crearlo
                                $cliente = new User();
                                $cliente->name = $request->email;
                                $cliente->rol = 'Cliente';
                                $cliente->email = $request->email;
                                $cliente->password = Hash::make($cliente->email);
                                $cliente->save();
                            }
                            $cliente = User::where('email', $request->email)->first();
                        }
                        // Se crea el codigo unico de cupon
                        $sw=1;
                        while($sw==1)
                        {
                            $codigo = $this->codigoGenerador();
                            $valor = Cupone::where('codigo', $codigo)->get();
                            if(count($valor)==0)
                            {
                                $sw=0;
                            }
                        }
                        // Se crea el Cupon
                        $cupon = new Cupone();
                        $cupon->user_id = Auth::user()->id;
                        $cupon->producto_id = $request->producto_id;    //pendiente validar
                        $cupon->cliente_id = $cliente->id;
                        $cupon->almacene_id = $request->tienda;
                        $cupon->descuento = $request->producto_descuento;
                        $cupon->monto_total = $request->producto_total;
                        $cupon->codigo = $codigo;
                        $cupon->fecha_inicio = $request->fecha_inicio;
                        $cupon->fecha_final = $request->fecha_fin;
                        $cupon->save();
                        // Se preparan los datos para el envio del email
                        $producto = Producto::find($request->producto_id);
                        $tienda = Almacene::find($request->tienda);
                        if($tienda)             // Si existe una tienda especifica
                        {
                            $tienda = $tienda->nombre.', '.$tienda->direccion;
                        }
                        else                    // Si puede cobrar en cualquier tienda
                        {
                            $tienda = "Cualquier sucursal";
                        }
                        $message = [
                            'fecha_final' => $request->fecha_fin,
                            'producto' => $producto->nombre,
                            'precio_normal' => $request->producto_precio,
                            'precio_descuento' => $request->producto_total,
                            'tienda' => $tienda,
                        ];
                        // Creando imagen de QR
                        $png = QrCode::format('png')->color(1,126,191)->size(300)->generate($codigo);
                        Storage::disk('qrs')->put($codigo.'.png', $png);
                        // Se envia el email
                        Mail::to($cliente->email)->send(new CuponMail($message, $codigo));
                    }
                }
            }
            else                            // Para una grupo de personas (masivo)
            {
                // Entonces tiene que existir valores en $request->grupos, preguntamos si esta definido (si hay valores)
                if($request->grupos)
                {
                    // Elaboramos la consulta dinamica donde se guardara en la variable $resultado, todos los grupos enviados de interfaz
                    $consulta = DB::table("grupos_users");
                    foreach($request->grupos as $grupo){
                        $consulta->orWhere('grupo_id', $grupo);
                    }
                    $resultado = $consulta->get('user_id');
                    // Eliminaremos resultados duplicados guardando el resultado en la variable $final
                    $final = array();
                    foreach($resultado as $row){
                        if(!in_array($row->user_id, $final))
                        {
                            array_push($final, $row->user_id);
                        }
                    }
                    // Procedemos al envio masivo en base a cada user_id que se encuentre en la variable $final
                    foreach($final as $row){
                        // Buscamos en la BD al cliente
                        $cliente = User::find($row);
                        // Si encontro al cliente(no tiene que estar eliminado en la bd)
                        if($cliente)            // Procedemos a registrar en la BD suc cupon y su envio
                        {
                            //comprobamos que el codigo generado no se encuentre en la base de datos(Unico)
                            $sw=1;
                            while($sw==1)
                            {
                                $codigo = $this->codigoGenerador();
                                $valor = Cupone::where('codigo', $codigo)->get();
                                if(count($valor)==0)
                                {
                                    $sw=0;
                                }
                            }
                            //Se crea el Cupon
                            $cupon = new Cupone();
                            $cupon->user_id = Auth::user()->id;
                            $cupon->producto_id = $request->producto_id;    //pendiente validar
                            $cupon->cliente_id = $cliente->id;
                            $cupon->almacene_id = $request->tienda;
                            $cupon->descuento = $request->producto_descuento;
                            $cupon->monto_total = $request->producto_total;
                            $cupon->codigo = $codigo;
                            $cupon->fecha_inicio = $request->fecha_inicio;
                            $cupon->fecha_final = $request->fecha_fin;
                            $cupon->save();
                         
                            
                            //Se preparan los datos para el envio del email
                            $producto = Producto::find($request->producto_id);
                            $tienda = Almacene::find($request->tienda);
                            if($tienda)
                            {
                                //Si existe una tienda especifica
                                $tienda = $tienda->nombre.', '.$tienda->direccion;
                            }
                            else
                            {
                                //Si puede cobrar en cualquier tienda
                                $tienda = "Cualquier sucursal";
                            }

                            $message = [
                                'fecha_final' => $request->fecha_fin,
                                'producto' => $producto->nombre,
                                'precio_normal' => $request->producto_precio,
                                'precio_descuento' => $request->producto_total,
                                'tienda' => $tienda,
                            ];

                            //Creando imagen de QR
                            $png = QrCode::format('png')->color(1,126,191)->size(300)->generate($codigo);
                            Storage::disk('qrs')->put($codigo.'.png', $png);

                            //Se envia el email
                            Mail::to($cliente->email)->send(new CuponMail($message, $codigo));  
                        }
                    }
                }
            }
            return redirect('Cupon/listado');
        }
        else                                // Cupon por una promocion
        {
            // Debemos preguntarnos si el cupon es masivo o individual
            if($request->tipo_envio)        // Para una persona (individual)
            {
                // Entonces tiene que existir valores en tipo_envio y en (cliente o email)
                //dd('Existe tipo envio en producto');
                if($request->tipo_envio == "1")     // Medio a enviar es un cliente
                {
                    // Leer datos de id_cliente
                    if($request->cliente)
                    {
                        $cliente = User::find($request->cliente);
                        if($cliente)
                        {
                            // Se crea el codigo unico de cupon
                            $sw=1;
                            while($sw==1)
                            {
                                $codigo = $this->codigoGenerador();
                                $valor = Cupone::where('codigo', $codigo)->get();
                                if(count($valor)==0)
                                {
                                    $sw=0;
                                }
                            }
                            //Almacenaremos el valor total de la promocion
                            $promo = Combo::find($request->promocion);
                            $productos_promo = CombosProducto::where('combo_id', $promo->id)->get();
                            $precio_total_promocion = 0;
                            foreach($productos_promo as $producto){
                                $precio_total_promocion = $precio_total_promocion + ($producto->precio*$producto->cantidad);
                            }
                            // Se crea el Cupon
                            $cupon = new Cupone();
                            $cupon->user_id = Auth::user()->id;
                            $cupon->cliente_id = $cliente->id;
                            $cupon->combo_id = $request->promocion;    //pendiente validar
                            $cupon->almacene_id = $request->tienda;
                            //$cupon->descuento = $request->producto_descuento;
                            $cupon->monto_total = $precio_total_promocion;
                            $cupon->codigo = $codigo;
                            $cupon->fecha_inicio = $request->fecha_inicio;
                            $cupon->fecha_final = $request->fecha_fin;
                            $cupon->save();
                            // Se preparan los datos de promocion(Combo) y sus respectivos productos
                            $combo = Combo::find($request->promocion);
                            $productos_combo = CombosProducto::where('combo_id', $combo->id)->get();
                            if($request->tienda)            // Si existe una tienda especifica
                            {
                                //$tienda = Almacene::find($request->tienda);
                                $tienda = Almacene::where('id', $request->tienda)->first();
                                $tienda = $tienda->nombre.', '.$tienda->direccion;
                            }
                            else                            // Si puede cobrar en cualquier tienda
                            {
                                $tienda = "Cualquier sucursal";
                            }
                            // Creando imagen de QR
                            $png = QrCode::format('png')->color(1,126,191)->size(300)->generate($codigo);
                            Storage::disk('qrs')->put($codigo.'.png', $png);
                            // Se envia el email
                            Mail::to($cliente->email)->send(new PromocionMail($codigo, $request->fecha_fin, $tienda, $combo, $productos_combo));
                        }
                    }
                }
                else                                // Medio a enviar es un email
                {
                    // Leer datos de email
                    if($request->email)
                    {
                        $cliente = User::where('email', $request->email)->first();
                        // preguntar si cliente NO esta definido
                        if(!$cliente)
                        {
                            // preguntar por query builder si esta definido
                            $cliente = DB::table('users')->where('email', $request->email)->first();
                            if($cliente)
                            {
                                // SI esta en la bd pero esta borrado, habilitarlo
                                $cliente = DB::table('users')->where('email', $request->email)->update(['deleted_at' => NULL]);
                            }
                            else    
                            {
                                // NO esta en la bd crearlo
                                $cliente = new User();
                                $cliente->name = $request->email;
                                $cliente->rol = 'Cliente';
                                $cliente->email = $request->email;
                                $cliente->password = Hash::make($cliente->email);
                                $cliente->save();
                            }
                            $cliente = User::where('email', $request->email)->first();
                        }
                        // Se crea el codigo unico de cupon
                        $sw=1;
                        while($sw==1)
                        {
                            $codigo = $this->codigoGenerador();
                            $valor = Cupone::where('codigo', $codigo)->get();
                            if(count($valor)==0)
                            {
                                $sw=0;
                            }
                        }
                        //Almacenaremos el valor total de la promocion
                        $promo = Combo::find($request->promocion);
                        $productos_promo = CombosProducto::where('combo_id', $promo->id)->get();
                        $precio_total_promocion = 0;
                        foreach($productos_promo as $producto){
                            $precio_total_promocion = $precio_total_promocion + ($producto->precio*$producto->cantidad);
                        }
                        // Se crea el Cupon
                        $cupon = new Cupone();
                        $cupon->user_id = Auth::user()->id;
                        $cupon->cliente_id = $cliente->id;
                        $cupon->combo_id = $request->promocion;    //pendiente validar
                        $cupon->almacene_id = $request->tienda;
                        // $cupon->descuento = $request->producto_descuento;
                        $cupon->monto_total = $precio_total_promocion;
                        $cupon->codigo = $codigo;
                        $cupon->fecha_inicio = $request->fecha_inicio;
                        $cupon->fecha_final = $request->fecha_fin;
                        $cupon->save();
                        // Se preparan los datos de promocion(Combo) y sus respectivos productos
                        $combo = Combo::find($request->promocion);
                        $productos_combo = CombosProducto::where('combo_id', $combo->id)->get();
                        if($request->tienda)            // Si existe una tienda especifica
                        {
                            //$tienda = Almacene::find($request->tienda);
                            $tienda = Almacene::where('id', $request->tienda)->first();
                            $tienda = $tienda->nombre.', '.$tienda->direccion;
                        }
                        else                            // Si puede cobrar en cualquier tienda
                        {
                            $tienda = "Cualquier sucursal";
                        }
                        // Creando imagen de QR
                        $png = QrCode::format('png')->color(1,126,191)->size(300)->generate($codigo);
                        Storage::disk('qrs')->put($codigo.'.png', $png);
                        // Se envia el email
                        Mail::to($cliente->email)->send(new PromocionMail($codigo, $request->fecha_fin, $tienda, $combo, $productos_combo));
                    }
                }
            }
            else                            // Para una grupo de personas (masivo)
            {
                // Entonces tiene que existir valores en $request->grupos, preguntamos si esta definido (si hay valores)
                if($request->grupos)
                {
                    // Elaboramos la consulta dinamica donde se guardara en la variable $resultado, todos los grupos enviados de interfaz
                    $consulta = DB::table("grupos_users");
                    foreach($request->grupos as $grupo){
                        $consulta->orWhere('grupo_id', $grupo);
                    }
                    $resultado = $consulta->get('user_id');
                    // Eliminaremos resultados duplicados guardando el resultado en la variable $final
                    $final = array();
                    foreach($resultado as $row){
                        if(!in_array($row->user_id, $final))
                        {
                            array_push($final, $row->user_id);
                        }
                    }
                    // Procedemos al envio masivo en base a cada user_id que se encuentre en la variable $final
                    foreach($final as $row){
                        // Buscamos en la BD al cliente
                        $cliente = User::find($row);
                        // Si encontro al cliente(no tiene que estar eliminado en la bd)
                        if($cliente)            // Procedemos a registrar en la BD suc cupon y su envio
                        {
                            //comprobamos que el codigo generado no se encuentre en la base de datos(Unico)
                            $sw=1;
                            while($sw==1)
                            {
                                $codigo = $this->codigoGenerador();
                                $valor = Cupone::where('codigo', $codigo)->get();
                                if(count($valor)==0)
                                {
                                    $sw=0;
                                }
                            }
                            //Almacenaremos el valor total de la promocion
                            $promo = Combo::find($request->promocion);
                            $productos_promo = CombosProducto::where('combo_id', $promo->id)->get();
                            $precio_total_promocion = 0;
                            foreach($productos_promo as $producto){
                                $precio_total_promocion = $precio_total_promocion + ($producto->precio*$producto->cantidad);
                            }
                            //Se crea el Cupon
                            $cupon = new Cupone();
                            $cupon->user_id = Auth::user()->id;
                            //$cupon->producto_id = $request->producto_id;    //pendiente validar
                            $cupon->cliente_id = $cliente->id;
                            $cupon->combo_id = $request->promocion;    //pendiente validar
                            $cupon->almacene_id = $request->tienda;
                            //$cupon->descuento = $request->producto_descuento;
                            $cupon->monto_total = $precio_total_promocion;
                            $cupon->codigo = $codigo;
                            $cupon->fecha_inicio = $request->fecha_inicio;
                            $cupon->fecha_final = $request->fecha_fin;
                            $cupon->save();

                            // Se preparan los datos de promocion(Combo) y sus respectivos productos
                            $combo = Combo::find($request->promocion);
                            $productos_combo = CombosProducto::where('combo_id', $combo->id)->get();
                            if($request->tienda)            // Si existe una tienda especifica
                            {
                                //$tienda = Almacene::find($request->tienda);
                                $tienda = Almacene::where('id', $request->tienda)->first();
                                $tienda = $tienda->nombre.', '.$tienda->direccion;
                            }
                            else                            // Si puede cobrar en cualquier tienda
                            {
                                $tienda = "Cualquier sucursal";
                            }
                            // Creando imagen de QR
                            $png = QrCode::format('png')->color(1,126,191)->size(300)->generate($codigo);
                            Storage::disk('qrs')->put($codigo.'.png', $png);
                            // Se envia el email
                            Mail::to($cliente->email)->send(new PromocionMail($codigo, $request->fecha_fin, $tienda, $combo, $productos_combo));
                        }
                    }
                }
            }
            return redirect('Cupon/listado');
        }
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
}
