<?php

namespace App\Http\Controllers;

use App\User;
use App\Cupone;
use App\CuponesCobrado;
use App\Almacene;
use App\Producto;
use App\Venta;
use App\VentasProducto;
use App\Movimiento;
use App\Mail\CuponMail;
use App\Mail\PruebaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use QrCode;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Exception;

class CuponController extends Controller
{
    public function listado()
    {
        $cupones = Cupone::get();
        $almacenes = Almacene::get();
        $clientes = User::where('rol', 'Cliente')->get();
        return view('cupon.listado')->with(compact('almacenes', 'cupones', 'clientes'));
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
        //Validamos que exista el producto
        if(!$request->producto_id){
            return redirect('Cupon/listado');
        }

        //Validamos que se haya introducido un cliente o un email
        if(!$request->cliente && !$request->email){
            return redirect('Cupon/listado');
        }

        //si existe cliente id
        if($request->cliente){
            //registrara en cupon
            $id_cliente = $request->cliente;
            $correo_destino = User::find($request->cliente);
            $correo_destino = $correo_destino->email;
        }else{
            //se asume que se envio un email, entonces validamos que email este no se encuentre en la base de datos
            //buscamos si se encuentra este email en la base de datos
            $usuario = User::where('email', $request->email)->first();
            // Preguntamos si esta variable no esta definida (si no encontro registro)           
            if(!$usuario){
                //no existe el email o es un registro eliminado(soft delete) y crea un nuevo usuario
                try{
                    $cliente = new User();
                    $cliente->name = $request->email;
                    $cliente->rol = 'Cliente';
                    $cliente->email = $request->email;
                    $cliente->save();
                    $id_cliente = $cliente->id;
                    //guardamos este correo para su posterior envio
                    $correo_destino = $request->email;
                }catch(Exception $e){
                    //existe este registro, pero esta eliminado
                    return redirect('Cupon/listado');
                }
            }else{
                //existe el email en la base de datos, entonces se captura el id de ese email
                $id_cliente = $usuario->id;
                //guardamos este correo para su posterior envio
                $correo_destino = $request->email;
            }
        }

        //comprobamos que el codigo generado no se encuentre en la base de datos(Unico)
        $sw=1;
        while($sw==1){
            $codigo = $this->codigoGenerador();
            $valor = Cupone::where('codigo', $codigo)->get();
            if(count($valor)==0){
                $sw=0;
            }
        }

        //Se crea el Cupon
        $cupon = new Cupone();
        $cupon->user_id = Auth::user()->id;
        $cupon->producto_id = $request->producto_id;
        $cupon->cliente_id = $id_cliente;
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
        if($tienda){
            //Si existe una tienda especifica
            $tienda = $tienda->nombre;
        }else{
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
        Mail::to($correo_destino)->send(new CuponMail($message, $codigo));

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

        // Registramos en Ventas_producto
        $ventaProducto = new VentasProducto();
        $ventaProducto->user_id = Auth::user()->id;
        $ventaProducto->producto_id = $request->cobro_producto_id;
        $ventaProducto->venta_id = $venta->id;
        $ventaProducto->precio_venta = $request->cobro_precio;
        $ventaProducto->precio_cobrado = $request->cobro_total;
        $ventaProducto->cantidad = 1;
        $ventaProducto->fecha = date('Y-m-d');
        $ventaProducto->save();
        
        // Registrar en Movimientos
        $movimiento = new Movimiento();
        $movimiento->user_id = Auth::user()->id;
        $movimiento->producto_id = $request->cobro_producto_id;
        $movimiento->almacene_id = Auth::user()->almacen->id;
        $movimiento->venta_id = $venta->id;
        $movimiento->precio_venta = $request->cobro_total;
        $movimiento->salida = 1;
        $movimiento->fecha = date('Y-m-d');
        $movimiento->estado = 'Cupon';
        $movimiento->save();

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
        $datos_cupon->fecha = $request->cobro_cupon_id;
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

}
