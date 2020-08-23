<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Categoria;
use App\Producto;
use App\Almacene;
use App\Movimiento;
use App\ImagenesProducto;
use Illuminate\Support\Facades\DB;
use DataTables;

class TiendaController extends Controller
{
    public function publico(Request $request)
    {
        return view('tienda.publico');
    }

}
