<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\CuponMail;
use Illuminate\Support\Facades\Mail;
use App\Almacene;
use App\Cupone;
use App\Producto;
use App\User;

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
        $cupon->cliente_id = $request->cliente;
        $cupon->almacene_id = $request->tienda;
        $cupon->descuento = $request->producto_descuento;
        $cupon->monto_total = $request->producto_total;
        $cupon->codigo = $codigo;
        $cupon->fecha_inicio = $request->fecha_inicio;
        $cupon->fecha_final = $request->fecha_fin;
        $cupon->save();

        //Se preparan los datos para el envio del email
        $producto = Producto::find($request->producto_id);
        $producto = $producto->nombre;
        $message = [
            'fecha_final' => $request->fecha_fin,
            'producto' => $producto,
        ];
        //Se envia el email
        Mail::to("arielfernandez.rma7@gmail.com")->send(new CuponMail($message, $codigo));

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

}
