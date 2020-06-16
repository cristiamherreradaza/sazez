<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\User;
use App\Venta;
use App\Almacene;
use App\AlcancesUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlcanceController extends Controller
{
    public function index()
    {
    	$fecha = new \DateTime();//aqui obtenemos la fecha y hora actual
        $fecha_actual = $fecha->format('Y-m-d');//obtenes la fecha actual
        $mes = $fecha->format('m');//obtenes la fecha actual
        $anio = $fecha->format('Y');//obtenes la fecha actual
    	// $anual_mes = DB::select("SELECT YEAR(fecha) AS anio, MONTH(fecha) AS mes,  COUNT(fecha) AS total
     //                                FROM alcances_users
     //                                WHERE fecha BETWEEN '$anio_atras' AND '$fecha_actual'
     //                                GROUP BY YEAR(fecha) ASC, MONTH(fecha) ASC");

        $usuarios = User::where('rol','=','Empleado')
                	->get();
        $almacenes = Almacene::get();

        return view('alcance.index')->with(compact('usuarios', 'almacenes'));
    }

    public function ajax_alcance(Request $request)
    {
        $fecha = explode("-", $request->tipo);
        $anio = $fecha[0];
        $mes = $fecha[1];

        $usuarios = User::where('rol','=','Empleado') 
                ->select('id', 'almacen_id', 'name')
                ->get();
        foreach ($usuarios as $usu) {
        		$usuario_venta_mensual = DB::select("SELECT SUM(total) as total
	            								FROM ventas
                                                WHERE user_id = '$usu->id' 
                                                AND MONTH(fecha) = '$mes'
                                                AND YEAR(fecha) = '$anio'");
	            if (!empty($usuario_venta_mensual[0]->total)) {
	            	$alcance_user = DB::select("SELECT id, alcance_max
	            								FROM alcances_users
                                                WHERE user_id = '$usu->id' 
                                                AND mes = '$mes'
                                                AND anio = '$anio'");
	            	if (!empty($alcance_user)) {
	            		// dd($alcance_user[0]->id);
	            		$alcance_usuarios = AlcancesUser::find($alcance_user[0]->id);
				        $alcance_usuarios->total_vendido = $usuario_venta_mensual[0]->total;
				        $alcance_usuarios->save();
	            	} else {
                        $mes_li = $this->meses_literal($mes_resta);

	            		$alcance_usu = new AlcancesUser();
				        $alcance_usu->user_id = $usu->id;
				        $alcance_usu->alcance_max = 0;
				        $alcance_usu->mes = $mes;
                        $alcance_usu->mes_literal = $mes_li;
				        $alcance_usu->anio = $anio;
				        $alcance_usu->total_vendido = $usuario_venta_mensual[0]->total;
				        $alcance_usu->save();
	            	}
	            }
        }

        $alc_us = AlcancesUser::where("mes", $mes)
        			->where("anio", $anio)
                    ->get();
        // dd($alc_us);
        // if (count($alc_us) > 0){
        // 	echo 'si este lleno';
        // } else {
        // 	echo 'no esta vacio';
        // }
        return view('alcance.grafico')->with(compact('alc_us'));
    }

    public function ajax_ventas_meses(Request $request)
    {
    	$id = $request->tipo_id;
    	$name = $request->tipo_name;
        $fecha = explode("-", $request->tipo_fecha);
        $anio = $fecha[0];
        $mes = $fecha[1];
        $mes_resta = $mes;
        $anio_resta = $anio;

        for ($i=0; $i < 6 ; $i++) { 
            $usuario_venta_mensual = DB::select("SELECT SUM(total) as total
                                                FROM ventas
                                                WHERE user_id = '$id' 
                                                AND MONTH(fecha) = '$mes_resta'
                                                AND YEAR(fecha) = '$anio_resta'");
                if (!empty($usuario_venta_mensual[0]->total)) {
                    $alcance_user = DB::select("SELECT id, alcance_max
                                                FROM alcances_users
                                                WHERE user_id = '$id' 
                                                AND mes = '$mes_resta'
                                                AND anio = '$anio_resta'");
                    if (!empty($alcance_user)) {
                        // dd($alcance_user[0]->id);
                        $alcance_usuarios = AlcancesUser::find($alcance_user[0]->id);
                        $alcance_usuarios->total_vendido = $usuario_venta_mensual[0]->total;
                        $alcance_usuarios->save();
                    } else {
                        $mes_li = $this->meses_literal($mes_resta);

                        $alcance_usu = new AlcancesUser();
                        $alcance_usu->user_id = $id;
                        $alcance_usu->alcance_max = 0;
                        $alcance_usu->mes = $mes_resta;
                        $alcance_usu->mes_literal = $mes_li;
                        $alcance_usu->anio = $anio_resta;
                        $alcance_usu->total_vendido = $usuario_venta_mensual[0]->total;
                        $alcance_usu->save();
                    }
                }

            if ($mes_resta != 1) {
                $mes_resta -= 1;
            } else {
                $mes_resta = 12;
                $anio_resta -= 1;

            }
        }

        $grafico_mes = AlcancesUser::where("user_id", $id)
                    ->orderBy('anio','desc')
                    ->orderBy('mes','desc')
                    ->limit(6)
                    ->get();

        return view('alcance.grafico_meses')->with(compact('name', 'grafico_mes'));
    }

    public function meses_literal($num)
    {
        switch ($num) {
            case 1:
                return 'Ene';
                break;
            case 2:
                return 'Feb';
                break;
            case 3:
                return 'Mar';
                break;
            case 4:
                return 'Abr';
                break;
            case 5:
                return 'May';
                break;
            case 6:
                return 'Jun';
                break;
            case 7:
                return 'Jul';
                break;
            case 8:
                return 'Ago';
                break;
            case 9:
                return 'Sep';
                break;
            case 10:
                return 'Oct';
                break;
            case 11:
                return 'Nov';
                break;
            case 12:
                return 'Dic';
                break;
        }
    }

    public function guarda(Request $request)
    {
        $user_id = $request->tipo_user;
        dd($user_id);
        $alcance_max = $request->tipo_alcance;
        $fecha = explode("-", $request->tipo_fecha);
        $anio = $fecha[0];
        $mes = $fecha[1];

        $alcance_id = AlcancesUser::where("user_id", $user_id)
                    ->where("mes", $mes)
                    ->where("anio", $anio)
                    ->get();
        if (!empty($almacen_id[0]->id)) {
            dd('si');
        } else {
            dd('no');
        }

    }

    public function prueba()
    {
        $id = 18;
        $anio = 2020;
        $mes = 05;
        $mes_resta = $mes;
        $anio_resta = $anio;

        for ($i=0; $i < 6 ; $i++) { 
            $usuario_venta_mensual = DB::select("SELECT SUM(total) as total
                                                FROM ventas
                                                WHERE user_id = '$id' 
                                                AND MONTH(fecha) = '$mes_resta'
                                                AND YEAR(fecha) = '$anio_resta'");
                if (!empty($usuario_venta_mensual[0]->total)) {
                    $alcance_user = DB::select("SELECT id, alcance_max
                                                FROM alcances_users
                                                WHERE user_id = '$id' 
                                                AND mes = '$mes_resta'
                                                AND anio = '$anio_resta'");
                    if (!empty($alcance_user)) {
                        // dd($alcance_user[0]->id);
                        $alcance_usuarios = AlcancesUser::find($alcance_user[0]->id);
                        $alcance_usuarios->total_vendido = $usuario_venta_mensual[0]->total;
                        $alcance_usuarios->save();
                    } else {
                        $alcance_usu = new AlcancesUser();
                        $alcance_usu->user_id = $id;
                        $alcance_usu->alcance_max = 0;
                        $alcance_usu->mes = $mes_resta;
                        $alcance_usu->anio = $anio_resta;
                        $alcance_usu->total_vendido = $usuario_venta_mensual[0]->total;
                        $alcance_usu->save();
                    }
                }

            if ($mes_resta != 1) {
                $mes_resta -= 1;
            } else {
                $mes_resta = 12;
                $anio_resta -= 1;

            }
        }

        $prueba = AlcancesUser::where("user_id", $id)
                    ->orderBy('anio','desc')
                    ->orderBy('mes','desc')
                    ->limit(6)
                    ->get();
        $nro = count($prueba);
        for ($i=$nro-1; $i >= 0 ; $i--) { 
            echo $prueba[$i]->id;
            echo ', ';
            echo $prueba[$i]->alcance_max;
            echo ', ';
            echo $prueba[$i]->mes;
            echo ', ';
            echo $prueba[$i]->anio;
            echo ', ';
            echo $prueba[$i]->total_vendido;
            echo '****************';
        }
        // dd($nro);
        // // dd($prueba);
        // foreach ($prueba as $value) {
        //     echo $value->id;
        //     echo ', ';
        //     echo $value->alcance_max;
        //     echo ', ';
        //     echo $value->mes;
        //     echo ', ';
        //     echo $value->anio;
        //     echo ', ';
        //     echo $value->total_vendido;
        //     echo '****************';
        // }

    	// return view('alcance.mapa');
    }

}
