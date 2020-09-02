<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Categoria;
use App\Producto;
use App\Almacene;
use App\Movimiento;
use App\Tipo;
use App\ImagenesProducto;
use Illuminate\Support\Facades\DB;
use DataTables;

class TiendaController extends Controller
{
    public function publico(Request $request)
    {
		$listadoProductos = Producto::where('pagina_principal', 'Si')
							->where('publicado', 'Si')
							->inRandomOrder()
							->limit(12)
							->get();

		$listadoRecomendados = Producto::where('pagina_principal', 'No')
							->where('publicado', 'Si')
							->inRandomOrder()
							->limit(10)
							->get();

		// dd($listadoProductos);    					
        $listadoTipos = Tipo::limit(15)->get();
        return view('tienda.publico')->with(compact('listadoTipos', 'listadoRecomendados', 'listadoProductos'));
    }

}
