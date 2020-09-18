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
        
        //dd($pedido->numero);
        // dd($pedido_id);
        // return new Movimiento([
        //     // 'name'  => $row[0],
        // ]);
        //$hoy = date("Y-m-d H:i:s");
        
        // Si es numerico la columna 7 (contando desde el 0)
        if( is_numeric($row[7]) && is_numeric($row[0])){
            // capturamos variables de session
            $pedido = session('pedido');
            $numero = session('numero');

            // Busca si el codigo, nombre coinciden con la fila del excel
            $producto = Producto::where('codigo', $row[2])
                                ->where('nombre', $row[3])
                                ->firstOrFail();

            // Busca si el nombre del almacen (origen) coincide con la fila del excel
            // $almacen = Almacene::where('nombre', $row[0])
            //                     ->firstOrFail();

            //$pedido = Pedido::find($pedido_id);

            // Busca el total del producto X en el almacen DESTINO
            $ingreso = Movimiento::where('producto_id', $producto->id)
                                ->where('almacene_id', $pedido->almacene_id)
                                ->where('ingreso', '>', 0)
                                ->sum('ingreso');
            $salida = Movimiento::where('producto_id', $producto->id)
                                ->where('almacene_id', $pedido->almacene_id)
                                ->where('salida', '>', 0)
                                ->sum('salida');
            $cantidad_disponible = $ingreso - $salida;
            // Busca el total del producto X en el almacen central
            // $total = DB::select("SELECT (SUM(ingreso) - SUM(salida))as total
            //                     FROM movimientos
            //                     WHERE producto_id = '$producto->id'
            //                     AND almacene_id = '$pedido->almacene_solicitante_id'
            //                     GROUP BY producto_id");

            // Si el valor coincide con el numero de pedido
            if($pedido->numero == $row[0]){
                // Si la cantidad ingresada ($row[7]) es mayor a 0
                if($row[7] > 0){
                    // Si la cantidad ingresada ($row[7]) para el envio no es mayor a la cantidad disponible en el almacen 
                    if($cantidad_disponible >= $row[7]){
                        // Sale del almacen ORIGEN el producto X
                        $salida = new Movimiento();
                        $salida->user_id = Auth::user()->id;
                        $salida->producto_id = $producto->id;
                        $salida->almacene_id = $pedido->almacene_id;
                        $salida->pedido_id = $pedido->id;
                        $salida->salida = $row[7];
                        $salida->fecha = date("Y-m-d H:i:s");
                        $salida->numero = $numero;
                        $salida->estado = 'Envio';
                        $salida->save();

                        // Ingresa al almacen DESTINO el producto X
                        $entrada = new Movimiento();
                        $entrada->user_id = Auth::user()->id;
                        $entrada->producto_id = $producto->id;
                        $entrada->almacen_origen_id = $pedido->almacene_solicitante_id;
                        $entrada->almacene_id = $pedido->almacene_id;
                        $entrada->pedido_id = $pedido->id;
                        $entrada->ingreso = $row[7];
                        $entrada->fecha = date("Y-m-d H:i:s");
                        $entrada->numero = $numero;
                        $entrada->estado = 'Envio';
                        $entrada->save();

                        $pedido->estado = 'Entregado';
                        $pedido->save();
                    }
                }
            }
            

            /*
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
                    $entrada->almacen_origen_id = $pedido->almacene_solicitante_id;
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
            */
        }
    }
}
