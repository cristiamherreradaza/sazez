<?php

namespace App\Imports;

use App\Movimiento;
use App\Producto;
use App\Almacene;
use App\Pedido;
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
        $hoy = date("Y-m-d H:i:s");
        
        // Si es numerico la columna 7 (contando desde el 0)
        if( is_numeric($row[7]) ){
            // Busca si el codigo, nombre y modelo coinciden con la fila del excel
            $producto = Producto::where('codigo', $row[1])
                                ->where('nombre', $row[2])
                                ->where('modelo', $row[5])
                                ->firstOrFail();

            // Busca si el nombre del almacen coincide con la fila del excel
            $almacen = Almacene::where('nombre', $row[0])
                                ->firstOrFail();

            // Busca el total del producto X en el almacen central
            $total = DB::select("SELECT (SUM(ingreso) - SUM(salida))as total
                                FROM movimientos
                                WHERE producto_id = '$producto->id'
                                AND almacene_id = 1
                                GROUP BY producto_id");

            $pedido_id = session('pedido_id');
            $numero = session('numero');
            if (!empty($pedido_id)) {

                $cantidad_disponible = $total[0]->total;
                if ($row[7] <= $cantidad_disponible) {

                    $pedido = Pedido::find($pedido_id);

                    //aqui sacamos del alamacen central el producto                        
                    $salida = new Movimiento();
                    $salida->user_id = Auth::user()->id;
                    $salida->producto_id = $producto->id;
                    $salida->almacene_id = $pedido->almacene_id;
                    $salida->pedido_id = $pedido_id;
                    $salida->salida = $row[7];
                    $salida->fecha = $hoy;
                    $salida->numero = $numero;
                    $salida->estado = 'Envio';
                    $salida->save();

                    //aqui ingresamos al alamacen solicitante el producto   
                    $entrada = new Movimiento();
                    $entrada->user_id = Auth::user()->id;
                    $entrada->producto_id = $producto->id;
                    $entrada->almacene_id = $almacen->id;
                    $entrada->pedido_id = $pedido_id;
                    $entrada->ingreso = $row[7];
                    $entrada->fecha = $hoy;
                    $entrada->numero = $numero;
                    $entrada->estado = 'Envio';
                    $entrada->save();
                }
                
            } else {
                $cantidad_disponible = $total[0]->total;
                if ($row[7] <= $cantidad_disponible) {
                    //aqui sacamos del alamacen central el producto                        
                    $salida = new Movimiento();
                    $salida->user_id = Auth::user()->id;
                    $salida->producto_id = $producto->id;
                    $salida->almacene_id = 1;
                    // $salida->pedido_id = $pedido_id;
                    $salida->salida = $row[7];
                    $salida->fecha = $hoy;
                    $salida->numero = $numero;
                    $salida->estado = 'Envio';
                    $salida->save();

                    //aqui ingresamos al alamacen solicitante el producto   
                    $entrada = new Movimiento();
                    $entrada->user_id = Auth::user()->id;
                    $entrada->producto_id = $producto->id;
                    $entrada->almacene_id = $almacen->id;
                    // $entrada->pedido_id = $pedido_id;
                    $entrada->ingreso = $row[7];
                    $entrada->fecha = $hoy;
                    $entrada->numero = $numero;
                    $entrada->estado = 'Envio';
                    $entrada->save();
                }
            }
        }
    }
}
