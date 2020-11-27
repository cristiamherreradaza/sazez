<?php

namespace App\Imports;

use App\Envio;
use App\Almacene;
use App\Producto;
use App\Movimiento;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class EnviosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        // Si es numerico la columna 7 (contando desde el 0)
        if( is_numeric($row[7])){

            $numero = session('numero');
            // Busca si el codigo, nombre coinciden con la fila del excel
            $producto = Producto::where('codigo', $row[1])
                                ->where('nombre', $row[2])
                                ->firstOrFail();

            // Busca si el nombre del almacen (origen) coincide con la fila del excel
            $almacen = Almacene::where('nombre', $row[0])
                                ->firstOrFail();
            // dd($almacen->id);
            // $maximoIngreso = Movimiento::max('numero_ingreso');
            // $numeroIngreso = $maximoIngreso+1;
            // $maximaSalida = Movimiento::max('numero');
            // $numeroSalida = $maximaSalida+1;
            // dd($numeroIngreso);

            //$pedido = Pedido::find($pedido_id);

            // Busca el total del producto X en el almacen DESTINO
            $ingreso = Movimiento::where('producto_id', $producto->id)
                                ->where('almacene_id', Auth::user()->almacen_id)
                                ->where('ingreso', '>', 0)
                                ->sum('ingreso');
            $salida = Movimiento::where('producto_id', $producto->id)
                                ->where('almacene_id', Auth::user()->almacen_id)
                                ->where('salida', '>', 0)
                                ->sum('salida');

            $cantidad_disponible = $ingreso - $salida;
            // dd($cantidad_disponible);

            // Busca el total del producto X en el almacen central
            // $total = DB::select("SELECT (SUM(ingreso) - SUM(salida))as total
            //                     FROM movimientos
            //                     WHERE producto_id = '$producto->id'
            //                     AND almacene_id = '$pedido->almacene_solicitante_id'
            //                     GROUP BY producto_id");

            // Si la cantidad ingresada ($row[7]) es mayor a 0
            if($row[7] > 0){
                // Si la cantidad ingresada ($row[7]) para el envio no es mayor a la cantidad disponible en el almacen 
                if($cantidad_disponible >= $row[7]){
                    // Sale del almacen ORIGEN el producto X
                    $salida              = new Movimiento();
                    $salida->user_id     = Auth::user()->id;
                    $salida->producto_id = $producto->id;
                    $salida->tipo_id     = $producto->tipo_id;
                    // $salida->almacen_origen_id = Auth::user()->almacen_id;
                    $salida->almacene_id = Auth::user()->almacen_id;
                    $salida->salida      = $row[7];
                    $salida->fecha       = date("Y-m-d H:i:s");
                    $salida->numero      = $numero;
                    $salida->estado      = 'Envio';
                    $salida->dispositivo = session('dispositivo');
                    $salida->save();

                    // Ingresa al almacen DESTINO el producto X
                    $entrada                    = new Movimiento();
                    $entrada->user_id           = Auth::user()->id;
                    $entrada->producto_id       = $producto->id;
                    $entrada->tipo_id           = $producto->tipo_id;
                    $entrada->almacen_origen_id = Auth::user()->almacen_id;
                    $entrada->almacene_id       = $almacen->id;
                    $entrada->ingreso           = $row[7];
                    $entrada->fecha             = date("Y-m-d H:i:s");
                    $entrada->numero            = $numero;
                    $entrada->estado            = 'Envio';
                    $entrada->dispositivo       = session('dispositivo');
                    $entrada->save();

                    // $pedido->estado = 'Entregado';
                    // $pedido->save();
                }
            }
            
        }
    }
}
