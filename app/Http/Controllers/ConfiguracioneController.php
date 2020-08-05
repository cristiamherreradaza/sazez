<?php

namespace App\Http\Controllers;

use App\Tipo;
use App\Configuracione;
use Illuminate\Http\Request;

class ConfiguracioneController extends Controller
{
    public function listadoEliminaVenta()
    {
        $configuraciones = Configuracione::where('descripcion', 'comboEliminaVenta')->get();
        return view('configuracione.listadoEliminaVenta')->with(compact('configuraciones'));
    }

    public function guardarEliminaVenta(Request $request)
    {
        if ($request->configuracionId != null) {
            // dd($request->all());
            $configuracion = Configuracione::find($request->configuracionId);
            $configuracion->descripcion = 'comboEliminaVenta';
            $configuracion->valor = $request->motivo;
            $configuracion->save();
        } else {
            $configuracion = new Configuracione();
            $configuracion->descripcion = 'comboEliminaVenta';
            $configuracion->valor = $request->motivo;
            $configuracion->save();
        }

        return redirect('Configuracione/listadoEliminaVenta');
    }

    public function actualizarEliminaVenta(Request $request)
    {
        return redirect('Tipo/listado');
    }

    public function eliminarEliminaVenta($id)
    {
        $tipo = Configuracione::find($id);
        $tipo->delete();
        return redirect('Configuracione/listadoEliminaVenta');
    }

}
