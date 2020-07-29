<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Proveedore;

class ProveedorController extends Controller
{
    public function listado()
    {
        $proveedores = Proveedore::get();
        return view('proveedor.listado')->with(compact('proveedores'));                            
    }

    public function guardar(Request $request)
    {
        $proveedor = new Proveedore();
        $proveedor->user_id = Auth::user()->id;
        $proveedor->nombre = $request->nombre_proveedor;
        $proveedor->direccion = $request->direccion_proveedor;
        $proveedor->telefonos = $request->telefonos_proveedor;
        $proveedor->save();
        return redirect('Proveedor/listado');
    }

    public function actualizar(Request $request)
    {
        $proveedor = Proveedore::find($request->id);
        $proveedor->user_id = Auth::user()->id;
        $proveedor->nombre = $request->nombre;
        $proveedor->direccion = $request->direccion;
        $proveedor->telefonos = $request->telefonos;
        $proveedor->save();
        return redirect('Proveedor/listado');
    }

    public function eliminar(Request $request)
    {
        $proveedor = Proveedore::find($request->id);
        $proveedor->delete();
        return redirect('Proveedor/listado');
    }
}
