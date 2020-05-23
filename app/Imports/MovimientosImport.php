<?php

namespace App\Imports;

use App\Movimiento;
use App\Producto;
use App\Almacene;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;

class MovimientosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($pedido_id);
        // return new Movimiento([
        //     // 'name'  => $row[0],
        // ]);
        if( is_numeric($row[7]) ){

            $producto = Producto::where('codigo', $row[1])
                                        ->where('nombre', $row[2])
                                        ->where('modelo', $row[5])
                                        ->firstOrFail();

            $almacen = Almacene::where('nombre', $row[0])
                                        ->firstOrFail();

            $total = DB::select("SELECT (SUM(ingreso) - SUM(salida))as total
                                                                FROM movimientos
                                                                WHERE producto_id = '$producto->id'
                                                                AND almacene_id = 1
                                                                GROUP BY producto_id");
            $cantidad_disponible = $total[0]->total;
            if ($row[7] <= $cantidad_disponible) {
                //aqui sacamos del alamacen central el producto                        
                $salida = new Movimiento();
                $salida->user_id = Auth::user()->id;
                $salida->producto_id = $producto->id;
                $salida->almacene_id = 1;
                // $salida->pedido_id = $pedido_id;
                $salida->salida = $row[7];
                $salida->save();

                //aqui ingresamos al alamacen solicitante el producto   
                $entrada = new Movimiento();
                $entrada->user_id = Auth::user()->id;
                $entrada->producto_id = $producto->id;
                $entrada->almacene_id = $almacen->id;
                // $entrada->pedido_id = $pedido_id;
                $entrada->ingreso = $row[7];
                $entrada->save();
            }

            

        }
    }
}
