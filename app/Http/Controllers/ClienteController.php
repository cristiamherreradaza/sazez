<?php

namespace App\Http\Controllers;

use App\User;
use App\Grupo;
use App\Turno;
use App\Almacene;
use App\Asignatura;
use App\NotasPropuesta;
use App\GruposUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $usuario->celulares = $request->celular;
        $usuario->razon_social = $request->razon_social;
        $usuario->nit = $request->nit;
        $usuario->save();
        return redirect('Cliente/listado');
    }

    public function password(Request $request)
    {
        $usuario = User::find($request->id_password);
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return redirect('Cliente/listado');
    }

    public function eliminar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->delete();
        return redirect('Cliente/listado');
    }

    public function ajaxGuardaCliente(Request $request)
    {
        $validaEmail = 0;
        $clienteId = 0;
        $consultaEmail = User::where('email', $request->email_usuario)->count();
        if($consultaEmail > 0){
            $validaEmail = 1;
        }else{
            $usuario = new User();
            $usuario->name = $request->nombre_usuario;
            $usuario->email = $request->email_usuario;
            $usuario->celulares = $request->celular_usuario;
            $usuario->nit = $request->nit_usuario;
            $usuario->rol = 'Cliente';
            $usuario->razon_social = $request->razon_social_usuario;
            $usuario->password = Hash::make($request->password_usuario);
            $usuario->save();
            $clienteId = $usuario->id;
        }

        return response()->json([
            'validaEmail' => $validaEmail,
            'clienteId' => $clienteId
        ]);

    }

    public function ajaxVerificaCorreo(Request $request)
    {
        $valida = 0;
        $consultaEmail = User::where('email', $request->correo)->count();
        if($consultaEmail > 0){
            $valida = 1;
        }

        return response()->json([
            'valida' => $valida
        ]);
    }

    public function ajaxComboClienteNuevo(Request $request, $clienteId)
    {
        // dd($clienteId);
        $clientes = User::where('rol', 'Cliente')
            ->get();
        $clienteSeleccionado = $clienteId;

        return view('cliente.ajaxComboClienteNuevo')->with(compact('clientes', 'clienteSeleccionado'));
    }

    public function ajaxEditaCliente(Request $request)
    {
        $grupos        = Grupo::all();
        $gruposCliente = GruposUser::where('user_id', $request->clienteId)->get();
        $datosCliente  = User::find($request->clienteId);
        return view('cliente.ajaxEditaCliente')->with(compact('datosCliente', 'grupos', 'gruposCliente'));
    }

    public function guardaAjaxClienteEdicion(Request $request)
    {
        // dd($request->all());
        $cliente               = User::find($request->clienteId);
        $cliente->name         = $request->nombre_usuario;
        $cliente->celulares    = $request->celulares;
        $cliente->nit          = $request->nit_usuario;
        $cliente->razon_social = $request->razon_social_usuario;
        $cliente->save();

        if($request->has('grupos')){
            GruposUser::where('user_id', $request->clienteId)->delete();
            foreach ($request->grupos as $g) {
                $grupo           = new GruposUser();
                $grupo->user_id  = $request->clienteId;
                $grupo->grupo_id = $g;
                $grupo->save();
            }
        }

        return response()->json([
            'msg'       => 1,
            'clienteId' => $request->clienteId
        ]);
    }
}
