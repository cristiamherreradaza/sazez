<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DataTables;
use App\Almacene;
use App\Asignatura;
use App\NotasPropuesta;
use App\Turno;
use App\User;
use Validator;

class UserController extends Controller
{
    public function listado()
    {
        $usuarios = User::get();
        $almacenes = Almacene::get();
        return view('usuario.listado')->with(compact('usuarios', 'almacenes'));
    }

    public function guardar(Request $request)
    {
        $usuario = new User();
        $usuario->name = $request->nombre_usuario;
        $usuario->email = $request->email_usuario;
        $usuario->celulares = $request->celular_usuario;
        $usuario->nit = $request->nit_usuario;
        $usuario->razon_social = $request->razon_social_usuario;
        $usuario->rol = $request->rol_usuario;
        $usuario->almacen_id = $request->almacen_usuario;
        $usuario->password = Hash::make($request->password_usuario);
        $usuario->save();
        return redirect('User/listado');
    }

    public function actualizar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->name = $request->nombre;
        $usuario->email = $request->email;
        $usuario->celulares = $request->celular;
        $usuario->razon_social = $request->razon_social;
        $usuario->nit = $request->nit;
        if($request->rol){
            $usuario->rol = $request->rol;
        }
        if($request->rol){
            $usuario->almacen_id = $request->almacen;
        }
        $usuario->save();
        return redirect('User/listado');
    }

    public function password(Request $request)
    {
        $usuario = User::find($request->id_password);
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return redirect('User/listado');
    }

    public function eliminar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->delete();
        return redirect('User/listado');
    }

    public function perfil()
    {
        return view('usuario.perfil');
    }

    public function asignar()
    {
        $users = User::where('vigente', 'si')->get();
        return view('user.asignar')->with(compact('users'));
    }

    // public function listado()
    // {
    // 	return view('user.listado');
    // }

    public function ajax_listado()
    {
    	$lista_personal = User::all();
    	return Datatables::of($lista_personal)
            ->addColumn('action', function ($lista_personal) {
                return '<button onclick="asigna_materias('.$lista_personal->id.')" class="btn btn-info"><i class="fas fa-eye"></i></a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
    }

    public function asigna_materias($usuario_id = null)
    {
        $gestion_vigente = date('Y');

        $datos_persona = User::find($usuario_id);

        $turnos = Turno::where('deleted_at', NULL)->get();

        $asignaturas = Asignatura::where('deleted_at', NULL)
                    ->where('anio_vigente', $gestion_vigente)
                    ->get();

        $asignaturas_docente = NotasPropuesta::where('deleted_at', NULL)
                            ->where('user_id', $usuario_id)
                            ->where('anio_vigente', $gestion_vigente)
                            ->get();

    	return view('user.asigna_materias')->with(compact('asignaturas', 'asignaturas_docente', 'datos_persona', 'turnos'));
    }

    public function guarda_asignacion(Request $request)
    {
        $error_duplicado = 0;
        $asignacionGuardada = 0;
        $validacion = NotasPropuesta::where('deleted_at', NULL)
                    ->where('asignatura_id', $request->asignatura_id)
                    ->where('user_id', $request->user_id)
                    ->where('turno_id', $request->turno_id)
                    ->where('paralelo', $request->paralelo)
                    ->where('anio_vigente', $request->anio_vigente)
                    ->count();
        // dd($validacion);

        if ($validacion > 0) {
            $error_duplicado = 1;
        }else{
            $nNotaPropuesta = new NotasPropuesta();
            $nNotaPropuesta->asignatura_id = $request->asignatura_id;   
            $nNotaPropuesta->user_id = $request->user_id;   
            $nNotaPropuesta->paralelo = $request->paralelo;   
            $nNotaPropuesta->turno_id = $request->turno_id;   
            $nNotaPropuesta->anio_vigente = $request->anio_vigente;   
            $nNotaPropuesta->save();
            $asignacionGuardada = 1;
        }

        return response()->json([
            'error_duplicado' => $error_duplicado,
            'asignacionGuardada' => $asignacionGuardada
        ]);
    }

    public function eliminaAsignacion(Request $request, $np_id)
    {
        $datosNP = NotasPropuesta::find($np_id);

        $eliminaNP = NotasPropuesta::find($np_id);
        $eliminaNP->deleted_at = date('Y-m-d H:i:s');
        $eliminaNP->save();
        return response()->json([
            'usuario' => $datosNP->user_id
        ]);
    }

    public function actualizarImagen(Request $request)
    {
        //dd($request->documento);
        $validation = Validator::make($request->all(), [
            'documento' => 'required|mimes:jpeg,jpg,png|max:2048'
        ]);
        if($validation->passes()){

            $filename = time().'.'.request()->documento->getClientOriginalExtension();
            request()->documento->move(public_path('assets/images/users'), $filename);

            $usuario = User::find($request->id_usuario);
            $usuario->image = $filename;
            $usuario->save();
            
            return redirect('User/listado');
        }else{
            switch ($validation->errors()->first()) {
                default:
                    $mensaje = "Fallo al cambiar de imagen, verificar que el archivo importado sea del tipo .jpg .jpeg .png y el limite no sea mayor a 2048 kbs.";
                    break;
            }
            return redirect('User/perfil')->with('flash', $mensaje);
        }
    }
}
