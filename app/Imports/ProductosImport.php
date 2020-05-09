<?php

namespace App\Imports;

use App\Marca;
use App\Producto;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
        // echo $row[2] . "<br />";
        /*return new Producto([
            'numero'=>$row[0],
            'codigo'=>$row[1],
            'nombre'=>$row[2],
        ]);*/
        $marca = Marca::where('nombre', 'like', "%$row[6]%")
                        ->get();
        if (isset($marca->id)) {
            echo 'esta';
        }else{
            echo 'no';
        }
        dd($marca);

        $producto                 = new Producto();
        $producto->user_id        = Auth::user()->id;
        $producto->marca_id       = $row[0];
        $producto->tipo_id        = $row[4];
        $producto->codigo         = $row[1];
        $producto->nombre         = $row[2];
        $producto->nombre_venta   = $row[3];
        $producto->modelo         = $row[7];
        $producto->precio_compra  = $row[8];
        $producto->largo          = $row[12];
        $producto->ancho          = $row[13];
        $producto->alto           = $row[14];
        $producto->peso           = $row[15];
        $producto->colores        = $row[16];
        $producto->descripcion    = $row[22];
        $producto->url_referencia = $row[23];
        $producto->video          = $row[24];
        $producto->save();
    }

    public function startRow(): int
    {
        return 2;
    }

}
