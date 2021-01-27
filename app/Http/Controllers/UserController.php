<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Meta;
use App\User;
use App\Turno;
use App\Venta;
use Validator;
use DataTables;
use App\Perfile;
use App\Almacene;
use App\MenusUser;
use App\Asignatura;
use App\MenusPerfile;
use App\NotasPropuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function listado()
    {
        $usuarios = User::where('rol', '!=', 'Cliente')->orderBy('almacen_id')->get();
        $almacenes = Almacene::whereNull('estado')->get();
        $perfiles = Perfile::get();
        $menus = Menu::whereNull('padre')->get();
        return view('usuario.listado')->with(compact('usuarios', 'perfiles', 'almacenes', 'menus'));
    }

    public function guardar(Request $request)
    {
        // Encontramos el perfil
        $perfil = Perfile::find($request->perfil_usuario);

        // Creamos usuario
        $usuario = new User();
        $usuario->name = $request->nombre_usuario;
        $usuario->ci = $request->ci_usuario;
        $usuario->email = $request->email_usuario;
        $usuario->celulares = $request->celular_usuario;
        $usuario->nit = $request->nit_usuario;
        $usuario->razon_social = $request->razon_social_usuario;
        $usuario->perfil_id = $request->perfil_usuario;
        $usuario->rol = $perfil->nombre;
        if($request->perfil_usuario != 4){      // Si el perfil de usuario no es de mayorista, entra
            $usuario->almacen_id = $request->almacen_usuario;
        }
        $usuario->password = Hash::make($request->password_usuario);
        $usuario->save();

        if($request->perfil_usuario == 4){      // Si el perfil es de usuario mayorista, crea almacen y adiciona estado en usuario
            $almacen = new Almacene();
            $almacen->user_id = Auth::user()->id;
            $almacen->nombre = $request->nombre_nuevo_almacen;
            $almacen->direccion = $request->telefonos_nuevo_almacen;
            $almacen->telefonos = $request->direccion_nuevo_almacen;
            $almacen->mayorista = 'Si';
            //$almacen->estado = 'Mayorista';
            $almacen->save();
            // Actualizamos datos de usuario creado
            $usuario->almacen_id = $almacen->id;
            $usuario->save();
        }
        
        // Asignaremos permisos del perfil correspondiente
        if($request->perfil_usuario)
        {
            $menus = MenusPerfile::where('perfil_id', $request->perfil_usuario)->get();
            if(count($menus) > 0)
            {
                foreach($menus as $menu)
                {
                    $menu_user = new MenusUser();
                    $menu_user->user_id = $usuario->id;
                    $menu_user->menu_id = $menu->menu_id;
                    $menu_user->save();
                }
            }
        }
        return redirect('User/listado');
    }

    public function actualizar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->name = $request->nombre;
        $usuario->ci = $request->ci;
        if($request->email){
            $usuario->email = $request->email;
        }
        $usuario->celulares = $request->celular;
        $usuario->nit = $request->nit;
        $usuario->razon_social = $request->razon_social;
        // if($request->rol){
        //     $usuario->rol = $request->rol;
        // }
        if($request->perfil){
            $perfil = Perfile::find($request->perfil);
            $usuario->rol = $perfil->nombre;
            // Eliminaremos el perfil con sus respectivos menus anteriores en la tabla menusUser
            $menuusers = MenusUser::where('user_id', $usuario->id)->get();
            if(count($menuusers) > 0)
            {
                foreach($menuusers as $menuuser)
                {
                    $menuuser->delete();
                }
            }
            // Asignaremos nuevo perfil
            $usuario->perfil_id = $request->perfil;
        }
        if($request->almacen){
            $usuario->almacen_id = $request->almacen;
        }
        $usuario->save();

        $menus = MenusPerfile::where('perfil_id', $request->perfil)->get();
        if(count($menus) > 0)
        {
            // Adicionaremos los nuevos
            foreach($menus as $menu)
            {
                $menu_user = new MenusUser();
                $menu_user->user_id = $usuario->id;
                $menu_user->menu_id = $menu->menu_id;
                $menu_user->save();
            }
        }
        return redirect('User/listado');
    }

    public function ajaxEditaPerfil(Request $request)
    {
        $perfil = Perfile::find($request->perfil_id);
        $menugeneral = Menu::whereNull('padre')->get();
        $menusperfil = MenusPerfile::where('perfil_id', $perfil->id)->get();
        $usuario = User::find($request->usuario_id);
        return view('usuario.ajaxEditaPerfil')->with(compact('perfil', 'menusperfil', 'menugeneral', 'usuario'));
    }

    public function actualizaMenus(Request $request)
    {
        //dd($request->menus);
        // Si se actualizaran, primero borrar y luego agregar
        $menususuario = MenusUser::where('user_id', $request->usuario_id)->get();
        foreach($menususuario as $registro)
        {
            $registro->delete();
        }
        // Si existen datos en el checkbox
        if($request->menus)
        {
            foreach($request->menus as $menu)
            {
                $menuuser = new MenusUser();
                $menuuser->user_id = $request->usuario_id;
                $menuuser->menu_id = $menu;
                $menuuser->save();
                $hijos = Menu::where('padre', $menu)->get();
                if(count($hijos)>0)             // Si tiene algun hijo
                {
                    // Por cada hijo que tenga este menu
                    foreach($hijos as $registro)
                    {
                        // Crearemos un registro de cada hijo del menu principal, asociandolo a ese perfil
                        $menuuser = new MenusUser();
                        $menuuser->user_id = $request->usuario_id;
                        $menuuser->menu_id = $registro->id;
                        $menuuser->save();
                    }
                }
            }
        }
        return redirect('User/listado');
    }

    public function password(Request $request)
    {
        $usuario = User::find($request->id_password);
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return back();
    }

    public function eliminar(Request $request)
    {
        // Busca/encuentra al usuario con id X
        $usuario = User::find($request->id);
        // Tambien Busca/elimina sus permisos correspondientes
        $permisos = MenusUser::where('user_id', $request->id)->get();
        foreach($permisos as $permiso){
            $permiso->delete();
        }
        // Elimina al usuario
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

    public function metasListado(Request $request)
    {
        $metasUsuario = Meta::where('user_id', $request->usuarioId)
                        ->get();

        $totalVentas = Venta::select(DB::raw('SUM(total) as total'))
                        ->whereMonth('fecha', '=', '1')
                        ->whereYear('fecha', '=', '2021')
                        ->where('user_id', 22)
                        ->get();
        // dd($totalVentas);

        $datosUsuario = User::find($request->usuarioId);
        // dd($metasUsuario);      
        return view('user.metasListado')->with(compact('metasUsuario', 'datosUsuario'));

    } 

    public function guardaMeta(Request $request)
    {
        $fecha = date('Y-m-d');

        if($request->meta_id == ""){
            $meta = new Meta();
        }else{
            $meta = Meta::find($request->meta_id);
        }

        $meta->user_id = $request->user_id;
        $meta->almacene_id = $request->almacen_id;
        $meta->meta = $request->meta;
        $meta->mes = $request->mes;
        $meta->gestion = $request->gestion;
        $meta->fecha = $request->fecha;
        $meta->save();

        return redirect("User/metasListado/$request->user_id");
    }

    public function eliminaMeta(Request $request)
    {
        $meta = Meta::find($request->metaId);
        $meta->delete();

        $datosUsuario = User::find($meta->user_id);

        return redirect("User/metasListado/$datosUsuario->id");
    }
}
