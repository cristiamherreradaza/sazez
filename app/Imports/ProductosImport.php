<?php

namespace App\Imports;

use App\Tipo;
use App\Marca;
use App\Precio;
use App\Almacene;
use App\Producto;
use App\Categoria;
use App\Movimiento;
use App\Proveedore;
use App\Caracteristica;
use App\Configuracione;
use App\CategoriasProducto;
use Illuminate\Support\Str;
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
        $configuraciones = Configuracione::where('descripcion', 'generacionCodigos')->first();

        if ($configuraciones->valor == 'Si') {
            // quitamos los espacios de la marca
            $marcaExcel = trim($row[5]);
            $busca_marca = Marca::where('nombre', 'like', "%$marcaExcel%")
                ->first();

            if ($busca_marca == null) {
                $marca = new Marca();
                $marca->user_id = Auth::user()->id;
                $marca->nombre = $marcaExcel;
                $marca->save();
                $marca_id = $marca->id;
                $sigla_marca = $this->extraeCodigo($marcaExcel);
            } else {
                $marca_id = $busca_marca->id;
                $sigla_marca = $this->extraeCodigo($busca_marca->nombre);
            }

            $tipoExcel = trim($row[4]);
            $busca_tipo = Tipo::where('nombre', 'like', "%$tipoExcel%")
                ->first();

            if ($busca_tipo == null) {
                $tipo = new Tipo();
                $tipo->user_id = Auth::user()->id;
                $tipo->nombre = $tipoExcel;
                $tipo->save();
                $tipo_id = $tipo->id;
                $sigla_tipo = $this->extraeCodigo($tipoExcel);
            } else {
                $tipo_id = $busca_tipo->id;
                $sigla_tipo = $this->extraeCodigo($busca_tipo->nombre);
            }

            $categoriaExcel = trim($row[3]);
            $busca_categoria = Categoria::where('nombre', 'like', "%$categoriaExcel%")
                ->first();

            if ($busca_categoria == null) {
                $categoria = new Categoria();
                $categoria->user_id = Auth::user()->id;
                $categoria->nombre = $categoriaExcel;
                $categoria->save();
                $categoria_id = $categoria->id;
                $sigla_categoria = $this->extraeCodigo($categoriaExcel);
            } else {
                $categoria_id = $busca_categoria->id;
                $sigla_categoria = $this->extraeCodigo($busca_categoria->nombre);
            }
            $nombreExcel = trim($row[1]);
            $sigla_nombre = $this->extraeCodigo($nombreExcel);

            $codigoGenerado = $sigla_marca . '-' . $sigla_tipo . '-' . $sigla_nombre;

        } else {

            $marcaExcel = trim($row[5]);
            $busca_marca = Marca::where('nombre', 'like', "%$marcaExcel%")
                ->first();

            if ($busca_marca == null) {
                $marca = new Marca();
                $marca->user_id = Auth::user()->id;
                $marca->nombre = $marcaExcel;
                $marca->save();
                $marca_id = $marca->id;
            } else {
                $marca_id = $busca_marca->id;
            }

            $tipoExcel = trim($row[4]);
            $busca_tipo = Tipo::where('nombre', 'like', "%$tipoExcel%")
                ->first();

            if ($busca_tipo == null) {
                $tipo = new Tipo();
                $tipo->user_id = Auth::user()->id;
                $tipo->nombre = $tipoExcel;
                $tipo->save();
                $tipo_id = $tipo->id;
            } else {
                $tipo_id = $busca_tipo->id;
            }

            $categoriaExcel = trim($row[3]);
            $busca_categoria = Categoria::where('nombre', 'like', "%$categoriaExcel%")
                ->first();

            if ($busca_categoria == null) {
                $categoria = new Categoria();
                $categoria->user_id = Auth::user()->id;
                $categoria->nombre = $categoriaExcel;
                $categoria->save();
                $categoria_id = $categoria->id;
            } else {
                $categoria_id = $busca_categoria->id;
            }

            $nombreExcel = trim($row[1]);
            $codigoGenerado = $row[26];
        }

        $producto                  = new Producto();
        $producto->user_id         = Auth::user()->id;
        $producto->marca_id        = $marca_id;
        $producto->tipo_id         = $tipo_id;
        $producto->codigo          = $codigoGenerado;
        $producto->nombre          = $nombreExcel;
        $producto->nombre_venta    = ($row[2] == "") ? null : $row[2];
        $producto->modelo          = ($row[6] == "") ? null : $row[6];
        $producto->precio_compra   = ($row[7] == "") ? 0 : $row[7];
        $producto->cantidad_minima = ($row[10] == "") ? 1 : $row[10];
        $producto->largo           = ($row[13] == "") ? 0 : $row[13];
        $producto->ancho           = ($row[14] == "") ? 0 : $row[14];
        $producto->alto            = ($row[15] == "") ? 0 : $row[15];
        $producto->peso            = ($row[16] == "") ? 0 : $row[16];
        $producto->colores         = ($row[17] == "") ? null : $row[17];
        $producto->descripcion     = ($row[23] == "") ? null : $row[23];
        $producto->url_referencia  = ($row[24] == "") ? null : $row[24];
        $producto->video           = ($row[25] == "") ? null : $row[25];
        $producto->save();
        $producto_id = $producto->id;

        if($configuraciones->valor == 'Si')
        {
            // se le asigna un numero al producto creado
            $cambia_codigo = Producto::find($producto_id);
            $numeroProducto = str_pad($producto_id, 5, "0", STR_PAD_LEFT);
            $cambia_codigo->codigo = $codigoGenerado.'-'.$numeroProducto;
            $cambia_codigo->save();
        }

        $busca_proveedor = Proveedore::where('nombre', 'like', "%$row[12]%")
            ->first();
        if ($busca_proveedor == null) {
            $proveedorId = 1;
        } else {
            $proveedorId = $busca_proveedor->id;
        }

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
        $movimiento->proveedor_id  = $proveedorId;
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

        if ($row[18] != '') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[18];
            $caracteristicas->save();
        }

        if ($row[19] != '') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[19];
            $caracteristicas->save();
        }
        
        if ($row[20] != '') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[20];
            $caracteristicas->save();
        }

        if ($row[21] != '') {
            $caracteristicas = new Caracteristica();
            $caracteristicas->user_id = Auth::user()->id;
            $caracteristicas->producto_id = $producto_id;
            $caracteristicas->descripcion = $row[21];
            $caracteristicas->save();
        }

        if ($row[22] != '') {
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
        $siglaMayusculas = strtoupper($sigla);
        return $siglaMayusculas;
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