<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Almacene;
use App\Asignatura;
use App\NotasPropuesta;
use App\Turno;
use App\User;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function inicio()
    {
        return view('cliente.inicio');
    }
    
    public function listado()
    {
        $clientes = User::where('rol', 'Cliente')->get();
        $almacenes = Almacene::get();
        return view('cliente.listado')->with(compact('clientes', 'almacenes'));
    }

    public function guardar(Request $request)
    {
        $usuario = new User();
        $usuario->name = $request->nombre_usuario;
        $usuario->email = $request->email_usuario;
        $usuario->celulares = $request->celular_usuario;
        $usuario->nit = $request->nit_usuario;
        $usuario->rol = 'Cliente';
        $usuario->razon_social = $request->razon_social_usuario;
        $usuario->password = Hash::make($request->password_usuario);
        $usuario->save();
        return redirect('Cliente/listado');
    }

    public function actualizar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->name = $request->nombre;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        if($request->celular){
            $usuario->celulares = $request->celular;
        }
        if($request->razon_social){
            $usuario->razon_social = $request->razon_social;
        }
        if($request->nit){
            $usuario->nit = $request->nit;
        }
        $usuario->save();
        return redirect('Cliente/listado');
    }

    public function eliminar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->delete();
        return redirect('Cliente/listado');
    }
}
