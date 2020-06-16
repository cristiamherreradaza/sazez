<?php

namespace App\Imports;

use App\NotasPropuesta;
use Maatwebsite\Excel\Concerns\ToModel;

class NotasPropuestasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if( is_numeric($row[0]) ){
            $notapropuesta = NotasPropuesta::find($row[0]);
            if($notapropuesta->asignatura->codigo_asignatura == $row[1]){
                $notapropuesta->nota_asistencia = $row[3];
                $notapropuesta->nota_practicas = $row[4];
                $notapropuesta->nota_puntos_ganados = $row[5];
                $notapropuesta->nota_primer_parcial = $row[6];
                $notapropuesta->nota_examen_final = $row[7];
                $notapropuesta->fecha = date('Y-m-d H:i:s');
                $notapropuesta->save();
            }
        }
    }
}
