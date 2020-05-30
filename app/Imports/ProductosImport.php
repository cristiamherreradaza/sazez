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
use Illuminate\Support\Str;

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
        // print_r($row[0]);
        // echo "</pre>";
        $busca_marca = Marca::where('nombre', 'like', "%$row[5]%")
                        ->first();

        if ($busca_marca == null) 
        {
            $marca          = new Marca();
            $marca->user_id = Auth::user()->id;
            $marca->nombre  = $row[5];
            $marca->save();
            $marca_id = $marca->id;
            $sigla_marca = $this->extraeCodigo($row[5]);
        } else {
            $marca_id = $busca_marca->id;
            $sigla_marca = $this->extraeCodigo($busca_marca->nombre);
        }

        $busca_tipo = Tipo::where('nombre', 'like', "%$row[4]%")
           ->first();

        if ($busca_tipo == null) 
        {
            $tipo = new Tipo();
            $tipo->user_id = Auth::user()->id;
            $tipo->nombre = $row[4];
            $tipo->save();
            $tipo_id = $tipo->id;
            $sigla_tipo = $this->extraeCodigo($row[4]);
        } else {
            $tipo_id = $busca_tipo->id;
            $sigla_tipo = $this->extraeCodigo($busca_tipo->nombre);
        }

        $busca_categoria = Categoria::where('nombre', 'like', "%$row[3]%")
            ->first();

        if ($busca_categoria == null) {
            $categoria = new Categoria();
            $categoria->user_id = Auth::user()->id;
            $categoria->nombre = $row[3];
            $categoria->save();
            $categoria_id = $categoria->id;
            $sigla_categoria = $this->extraeCodigo($row[3]);
        } else {
            $categoria_id = $busca_categoria->id;
            $sigla_categoria = $this->extraeCodigo($busca_categoria->nombre);
        }

        $sigla_nombre = $this->extraeCodigo($row[1]);

        $codigoGenerado = $sigla_marca.'-'.$sigla_tipo.'-'.$sigla_nombre;

        $producto                  = new Producto();
        $producto->user_id         = Auth::user()->id;
        $producto->marca_id        = $marca_id;
        $producto->tipo_id         = $tipo_id;
        $producto->codigo          = $codigoGenerado;
        $producto->nombre          = $row[1];
        $producto->nombre_venta    = $row[2];
        $producto->modelo          = $row[6];
        $producto->precio_compra   = $row[7];
        $producto->cantidad_minima = $row[10];
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

        $cambia_codigo = Producto::find($producto_id);
        $cambia_codigo->codigo = $codigoGenerado.'-'.$producto_id;
        $cambia_codigo->save();

        $busca_almacen = Almacene::where('nombre', 'like', "%$row[11]%")
            ->first();
        if ($busca_almacen == null) {
            $almacene_id = 1;
        } else {
            $almacene_id = $busca_almacen->id;
        }

        $movimiento                = new Movimiento();
        $movimiento->user_id       = Auth::user()->id;
        $movimiento->producto_id   = $producto_id;
        $movimiento->almacene_id   = $almacene_id;
        $movimiento->precio_compra = $row[7];
        $movimiento->precio_venta  = 0;
        $movimiento->ingreso       = $row[9];
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
        $precio->precio = $row[8];
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

    function extraeCodigo($texto)
    {
        $palabra = explode(" ", $texto);
        $primeras = Str::substr($palabra[0], 0, 3);
        $sigla = str_replace(" ", "", $primeras);
        return $sigla;
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
