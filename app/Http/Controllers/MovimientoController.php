<?php

namespace App\Http\Controllers;

use App\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function registraDatos()
    {
        // dd($productos[0]->id);
        for ($i=0; $i < 2000 ; $i++) { 
            $productos = DB::select('select id from productos order by rand() limit 1', [1]);
            $almacenes = DB::select('select id from almacenes order by rand() limit 1', [1]);
            $movimientos = new Movimiento();
            $is = rand(1,2);
            if($is == 1){
                $movimientos->ingreso = rand(1,100);
                $movimientos->salida = 0;
            }else{
                $movimientos->ingreso = 0;
                $movimientos->salida = rand(1,100);
            }
            # code...
            $movimientos->user_id = 1;
            $movimientos->producto_id = $productos[0]->id;
            $movimientos->almacene_id = $almacenes[0]->id;
            $movimientos->precio_compra = rand(15, 1000);
            $movimientos->precio_venta = rand(15, 1000);
            $movimientos->save();
            echo 'inserntando '.$i."<br />"; 
        }
    }
}
