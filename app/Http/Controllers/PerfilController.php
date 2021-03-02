<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Perfile;
use App\Menu;
use App\MenusPerfile;

class PerfilController extends Controller
{
    public function listado()
    {
        $perfiles = Perfile::get();
        $menus = Menu::whereNull('padre')->get();
        return view('perfil.listado')->with(compact('perfiles', 'menus'));                            
    }

    public function guardar(Request $request)
    {
        // dd($request->menus);
        $perfil = new Perfile();
        $perfil->user_id = Auth::user()->id;
        $perfil->nombre = $request->nombre_perfil;
        $perfil->descripcion = $request->descripcion_perfil;
        $perfil->save();

        // por cada id/checkbox que se ingrese
        // debe buscarse el id respoectivo en la tabla menus y si tiene algun hijo(padre -> id), tambien incluirlo
        // estos guardarlos en menus perfiles
        if($request->menus)
        {
            foreach($request->menus as $menu_id)
            {
                // Agregaremos al menu padre en los menusperfile
                $menusperfil = new MenusPerfile();
                $menusperfil->perfil_id = $perfil->id;
                $menusperfil->menu_id = $menu_id;
                $menusperfil->save();
                // Buscaremos todos los hijos de ese menu
                $menus = Menu::where('padre', $menu_id)->get();
                if(count($menus)>0)             // Si tiene algun hijo
                {
                    // Por cada hijo que tenga este menu
                    foreach($menus as $menu)
                    {
                        // Crearemos un registro de cada hijo del menu principal, asociandolo a ese perfil
                        $menusperfil = new MenusPerfile();
                        $menusperfil->perfil_id = $perfil->id;
                        $menusperfil->menu_id = $menu->id;
                        $menusperfil->save();
                    }
                }
            }
        }
        return redirect('Perfil/listado');
    }

    public function ajaxEditaPerfil(Request $request)
    {
        $perfil = Perfile::find($request->id);
        $menugeneral = Menu::whereNull('padre')->get();
        $menusperfil = MenusPerfile::where('perfil_id', $perfil->id)->get();
        return view('perfil.ajaxEditaPerfil')->with(compact('perfil', 'menusperfil', 'menugeneral'));

    }

    public function actualizar(Request $request)
    {
        //dd($request->datosmenu);
        $perfil = Perfile::find($request->id);
        $perfil->user_id = Auth::user()->id;
        $perfil->nombre = $request->nombre;
        $perfil->descripcion = $request->descripcion;
        $perfil->save();

        //Buscamos todos los menusperfiles correspondientes al perfil
        $menusperfiles = MenusPerfile::where('perfil_id', $perfil->id)->get();
        //Luego eliminamos todos sus registros
        foreach($menusperfiles as $registro)
        {
            $registro->delete();
        }
        //Preguntamos si existen datos enviados mediante el checkbox
        if($request->datosmenu)
        {
            foreach($request->datosmenu as $menu_id)
            {
                // Agregaremos al menu padre en los menusperfile
                $menusperfil = new MenusPerfile();
                $menusperfil->perfil_id = $perfil->id;
                $menusperfil->menu_id = $menu_id;
                $menusperfil->save();
                // Buscaremos todos los hijos de ese menu
                $menus = Menu::where('padre', $menu_id)->get();
                if(count($menus)>0)             // Si tiene algun hijo
                {
                    // Por cada hijo que tenga este menu
                    foreach($menus as $menu)
                    {
                        // Crearemos un registro de cada hijo del menu principal, asociandolo a ese perfil
                        $menusperfil = new MenusPerfile();
                        $menusperfil->perfil_id = $perfil->id;
                        $menusperfil->menu_id = $menu->id;
                        $menusperfil->save();
                    }
                }
            }
        }
        return redirect('Perfil/listado');
    }

    public function eliminar(Request $request)
    {
        $menusperfiles = MenusPerfile::where('perfil_id', $request->id)->get();
        foreach($menusperfiles as $registro)
        {
            $registro->delete();
        }
        $perfil = Perfile::find($request->id);
        $perfil->delete();
        return redirect('Perfil/listado');
    }
}
