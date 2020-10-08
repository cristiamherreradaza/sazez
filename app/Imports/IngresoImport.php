<?php

namespace App\Imports;

use App\Almacene;
use App\Movimiento;
use App\Producto;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class IngresoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(is_numeric($row[5])){
            // capturamos variables de session
            $proveedor = session('proveedor');
            $incluye_distribucion = session('incluye_distribucion');
            $numero = session('numero');
            $numero_ingreso = session('numero_ingreso');
            $numero_ingreso_envio = session('numero_ingreso_envio');

            // Validamos almacen
            $almacen = Almacene::where('nombre', $row[0])->first();
            if($almacen){
                // Validamos producto
                $producto = Producto::where('codigo', $row[1])
                                    ->where('nombre', $row[2])
                                    ->firstOrFail();
                // Si existe el producto ingresa
                if($producto){
                    // Preguntamos si el ingreso es directo ()
                    if($incluye_distribucion == 'No'){
                        // Si no incluye distribucion se crea un registro por cada producto, normalmente sera a almacen central
                        $ingreso = new Movimiento();
                        $ingreso->user_id = Auth::user()->id;
                        $ingreso->producto_id = $producto->id;
                        $ingreso->tipo_id = $producto->tipo_id;
                        $ingreso->almacene_id = $almacen->id;
                        $ingreso->proveedor_id = $proveedor;
                        $ingreso->ingreso = $row[5];
                        $ingreso->fecha = date("Y-m-d H:i:s");
                        $ingreso->numero_ingreso = $numero_ingreso;
                        $ingreso->estado = 'Ingreso';
                        $ingreso->dispositivo  = session('dispositivo');
                        $ingreso->save();
                    }else{
                        // Incluye distribucion, verificar que no sea almacen central
                        if($almacen->id != 1){
                            // Incluye distribucion, por cada producto se crea 3 registros, 1 de ingreso 2 de envio
                            // Creación de Movimiento - Ingresa a Almacen Central
                            $ingreso = new Movimiento();
                            $ingreso->user_id = Auth::user()->id;
                            $ingreso->producto_id = $producto->id;
                            $ingreso->tipo_id = $producto->tipo_id;
                            $ingreso->almacene_id =  1;
                            $ingreso->proveedor_id = $proveedor;
                            $ingreso->ingreso = $row[5];
                            $ingreso->estado = 'Ingreso';
                            $ingreso->numero_ingreso = $numero_ingreso;
                            $ingreso->numero_ingreso_envio = $numero_ingreso_envio;
                            $ingreso->fecha = date("Y-m-d H:i:s");
                            $ingreso->dispositivo  = session('dispositivo');
                            $ingreso->save();
                            // Creación de Movimiento - Sale de Almacen Central
                            $salida = new Movimiento();
                            $salida->user_id = Auth::user()->id;
                            $salida->producto_id = $producto->id;
                            $salida->tipo_id = $producto->tipo_id;
                            $salida->almacene_id = 1;
                            $salida->salida = $row[5];
                            $salida->estado = 'Envio';
                            $salida->numero = $numero;
                            $salida->numero_ingreso_envio = $numero_ingreso_envio;
                            $salida->fecha = date("Y-m-d H:i:s");
                            $salida->dispositivo  = session('dispositivo');
                            $salida->save();
                            // Creación de Movimiento - Ingresa a la Sucursal
                            $ingreso = new Movimiento();
                            $ingreso->user_id = Auth::user()->id;
                            $ingreso->producto_id = $producto->id;
                            $ingreso->tipo_id = $producto->tipo_id;
                            $ingreso->almacen_origen_id = 1;
                            $ingreso->almacene_id = $almacen->id;
                            $ingreso->ingreso = $row[5];
                            $ingreso->estado = 'Envio';
                            $ingreso->numero = $numero;
                            $ingreso->numero_ingreso_envio = $numero_ingreso_envio;
                            $ingreso->fecha = date("Y-m-d H:i:s");
                            $ingreso->dispositivo  = session('dispositivo');
                            $ingreso->save();
                        }else{
                            // Se crea un registro por cada producto
                            $ingreso = new Movimiento();
                            $ingreso->user_id = Auth::user()->id;
                            $ingreso->producto_id = $producto->id;
                            $ingreso->tipo_id = $producto->tipo_id;
                            $ingreso->almacene_id = $almacen->id;
                            $ingreso->proveedor_id = $proveedor;
                            $ingreso->ingreso = $row[5];
                            $ingreso->fecha = date("Y-m-d H:i:s");
                            $ingreso->numero_ingreso = $numero_ingreso;
                            $ingreso->estado = 'Ingreso';
                            $ingreso->dispositivo  = session('dispositivo');
                            $ingreso->save();
                        }   
                    }
                }
            }
        }
    }
}
