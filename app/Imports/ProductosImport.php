<?php

namespace App\Imports;

use App\Tipo;
use App\Marca;
use App\Precio;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\Movimiento;
use App\Caracteristica;
use App\CategoriasProducto;
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
        echo "<pre>";
        print_r($row);
        echo "</pre>";
       
        // dd($row);
        $busca_marca = Marca::where('nombre', 'like', "%$row[6]%")
                        ->first();
        // dd($busca_marca);

        if ($busca_marca == null) 
        {
            $marca          = new Marca();
            $marca->user_id = Auth::user()->id;
            $marca->nombre  = $row[6];
            $marca->save();
            $marca_id = $marca->id;
        } else {
            $marca_id = $busca_marca->id;
        }

        $busca_tipo = Tipo::where('nombre', 'like', "%$row[5]%")
           ->first();

        if ($busca_tipo == null) 
        {
            $tipo = new Tipo();
            $tipo->user_id = Auth::user()->id;
            $tipo->nombre = $row[5];
            $tipo->save();
            $tipo_id = $tipo->id;
        } else {
            $tipo_id = $busca_tipo->id;
        }

        $busca_categoria = Categoria::where('nombre', 'like', "%$row[4]%")
            ->first();

        if ($busca_categoria == null) {
            $categoria = new Categoria();
            $categoria->user_id = Auth::user()->id;
            $categoria->nombre = $row[4];
            $categoria->save();
            $categoria_id = $categoria->id;
        } else {
            $categoria_id = $busca_categoria->id;
        }


        $producto                  = new Producto();
        $producto->user_id         = Auth::user()->id;
        $producto->marca_id        = $marca_id;
        $producto->tipo_id         = $tipo_id;
        $producto->codigo          = $row[1];
        $producto->nombre          = $row[2];
        $producto->nombre_venta    = $row[3];
        $producto->modelo          = $row[7];
        $producto->precio_compra   = $row[8];
        $producto->cantidad_minima = $row[11];
        $producto->largo           = ($row[13] == "") ? 0 : $row[13];
        $producto->ancho           = ($row[14] == "") ? 0 : $row[14];
        $producto->alto            = ($row[15] == "") ? 0 : $row[15];
        $producto->peso            = ($row[16] == "") ? 0 : $row[16];;
        $producto->colores         = $row[17];
        $producto->descripcion     = $row[23];
        $producto->url_referencia  = $row[24];
        $producto->video           = $row[25];
        $producto->save();
        $producto_id = $producto->id;

        $busca_almacen = Almacene::where('nombre', 'like', "%$row[12]%")
            ->first();
        // dd($busca_almacen->id);
        if ($busca_almacen == null) {
            $almacene_id = 1;
        } else {
            $almacene_id = $busca_almacen->id;
        }

        $movimiento                = new Movimiento();
        $movimiento->user_id       = Auth::user()->id;
        $movimiento->producto_id   = $producto_id;
        $movimiento->almacene_id   = $almacene_id;
        $movimiento->precio_compra = $row[8];
        $movimiento->precio_venta  = 0;
        $movimiento->ingreso       = $row[10];
        $movimiento->save();

        $categoriasProducto = new CategoriasProducto();
        $categoriasProducto->user_id = Auth::user()->id;
        $categoriasProducto->categoria_id = $categoria_id;
        $categoriasProducto->producto_id = $producto_id;
        $categoriasProducto->save();

        $precio = new Precio();
        $precio->user_id = Auth::user()->id;
        $precio->producto_id = $producto_id;
        $precio->escala_id = 1;
        $precio->precio = $row[9];
        $precio->save();

        if ($row[18] != 'NT') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[18];
            $caracteristicas->save();
        }

        if ($row[19] != 'NT') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[19];
            $caracteristicas->save();
        }
        
        if ($row[20] != 'NT') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[20];
            $caracteristicas->save();
        }

        if ($row[21] != 'NT') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[21];
            $caracteristicas->save();
        }

        if ($row[22] != 'NT') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[22];
            $caracteristicas->save();
        }
    }

    /*public function startRow(): int
    {
        return 2;
    }*/

    public function startRow(): int
    {
        return 2;
    }

}
