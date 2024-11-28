<?php

namespace App\librerias;
use DateTime;

class Utilidades{

    function fechaHoraCastellano($fecha)
    {
        $arrayFecha = explode(" ", $fecha);

        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
            "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
            "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);

        return $nombredia . ", " . $numeroDia . " de " . $nombreMes . " del " . $anio. ' - Hora: ' . $arrayFecha[1];
    }

    function fechaCastellano($fecha)
    {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
            "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
            "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);

        return $nombredia . ", " . $numeroDia . " de " . $nombreMes . " del " . $anio;
    }

    //Genera numeros aleratorios de un tamaño determinado
    public static function generarGC($x = 16) {

        $chars = "1234567890";

        $no = "";
        for ($i=0; $i<$x; $i++) {
            $rnum = rand(0, 9);
            $no .= substr($chars,$rnum-1,1);
       }

       return $no;
    }

    public function formatoFecha($fecha, $formato){

        $fechaFformato = new DateTime($fecha);
		$fecha = $fechaFformato->format($formato);

        return $fecha;

    }
}
