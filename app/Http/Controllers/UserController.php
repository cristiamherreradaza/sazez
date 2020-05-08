<?php

namespace App\Http\Controllers;

use App\User;
use App\Turno;
use DataTables;
use App\Asignatura;
use App\NotasPropuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function listado()
    {
        $usuarios = User::get();
        return view('usuario.listado')->with(compact('usuarios'));
    }

    public function guardar(Request $request)
    {
        if($request->password_usuario == $request->confirm_password_usuario)
        {
            $usuario = new User();
            $usuario->name = $request->nombre_usuario;
            $usuario->rol = $request->rol_usuario;
            $usuario->email = $request->email_usuario;
            $usuario->password = Hash::make($request->password_usuario);
            $usuario->save();
        }
        return redirect('User/listado');
    }

    public function actualizar(Request $request)
    {
        if($request->password == $request->confirm_password)
        {
            $usuario = User::find($request->id);
            $usuario->name = $request->nombre;
            $usuario->rol = $request->rol;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }
        return redirect('User/listado');
    }

    public function eliminar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->delete();
        return redirect('User/listado');
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
}
