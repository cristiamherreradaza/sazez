<?php

namespace App\Imports;

use App\Producto;
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
        if ($row == 0) {
        }
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
        // echo $row[2] . "<br />";
        /*return new Producto([
            'numero'=>$row[0],
            'codigo'=>$row[1],
            'nombre'=>$row[2],
        ]);*/
    }

    public function startRow(): int
    {
        return 2;
    }

}
