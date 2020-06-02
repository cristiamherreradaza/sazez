<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Escala;
use App\Almacene;
use App\Tipo;
use App\Producto;
use App\Precio;
use Session;


class EscalaController extends Controller
{
    public function listado()
    {
        $escalas = Escala::get();
        return view('escala.listado')->with(compact('escalas'));
    }

    public function guardar(Request $request)
    {
        $escala = new Escala();
        $escala->user_id = Auth::user()->id;
        $escala->nombre = $request->nombre_escala;
        $escala->minimo = $request->minimo;
        $escala->maximo = $request->maximo;
        $escala->save();
        return redirect('Escala/listado');
    }

    public function actualizar(Request $request)
    {
        $escala = Escala::find($request->id);
        $escala->user_id = Auth::user()->id;
        $escala->nombre = $request->nombre;
        $escala->save();
        return redirect('Escala/listado');
    }

    public function eliminar(Request $request)
    {
        $escala = Escala::find($request->id);
        $escala->delete();
        return redirect('Escala/listado');
    }

    public function grupo_escala()
    {
        $escalas = Escala::get();
        $almacenes = Almacene::get();
        $tipos = Tipo::get();
        return view('escala.grupo_escala')->with(compact('almacenes', 'escalas', 'tipos'));
    }   

    

    public function ajax_producto(Request $request)
    {
        $tipo_id = $request->tipo;//buscar el carnet de identidad de una persona
        $producto = Producto::where("tipo_id", $tipo_id)
                    ->get();
        return view('escala.lista_escalas_productos')->with(compact('producto'));
        // return response()->json($producto);
    }

    public function guarda_multiple(Request $request)
    {
        $tipo_id = $request->tipo_id;
        $escala_id = $request->escala_id;
        $precio = $request->precio;
        $nro = sizeof($request->producto_id);

        for ($i=0; $i < $nro; $i++) {

            $escala = Precio::where('producto_id', $request->producto_id[$i])
                                        ->where('escala_id', $escala_id)
                                        ->first();
            if (!empty($escala)) {
                $precios = Precio::find($escala->id);
                $precios->user_id = Auth::user()->id;
                $precios->producto_id = $request->producto_id[$i];
                $precios->escala_id = $escala_id;
                $precios->precio = $precio;
                $precios->save(); 
            } else {
                $precioss = new Precio();
                $precioss->user_id = Auth::user()->id;
                $precioss->producto_id = $request->producto_id[$i];
                $precioss->escala_id = $escala_id;
                $precioss->precio = $precio;
                $precioss->save(); 
            }
        }

        Session::flash('success','Se guardo correctamente!');
        return back();
    }

    public function prueba()
    {
        return view('escala.prueba');
    }

    public function prueba1()
    {
        return view('escala.prueba1');
    }
}
