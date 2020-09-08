<?php

namespace App\Http\Controllers;

use App\Factura;
use CodigoControlV7;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function examenImpuestos()
    {
        // generamos el codigo de control
        // prueba 1
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '133401600000357';
        $numero_factura      = '1656';
        $nit_cliente         = '1017235022';
        $fecha_compra        = '20151222';
        $monto_compra        = round(72251.64, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'f6IIY=8MY9zEnEJS(#HpXK%*#$FZ2)bsE6u@=Q\DGdB9T9PQiKQ)5pCMAT6N-V$C';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "1- ".$codigoControl."</br>";

        // prueba 2
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '121401600000482';
        $numero_factura      = '9077';
        $nit_cliente         = '855095';
        $fecha_compra        = '20151230';
        $monto_compra        = round(1671.01, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = '7NH(np4Iy#zCC25T4@TK8Eb[%@U=VVV(EEX$EJdC(CBuVaL@z9Q8#AA@-{JndxCt';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "2- ".$codigoControl."</br>";

        // prueba 3
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '105401600000641';
        $numero_factura      = '7430';
        $nit_cliente         = '4244384';
        $fecha_compra        = '20160218';
        $monto_compra        = round(16285.16, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'nkJM6SR78YuxCZT]RZDJ#pV@MzMC6*{D+{s6UX[P*w865XUA=D99899FGN6L%z4Q';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "3- ".$codigoControl."</br>";

        // prueba 4
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '254401600000262';
        $numero_factura      = '614';
        $nit_cliente         = '1002649022';
        $fecha_compra        = '20160401';
        $monto_compra        = round(696.43, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'B9jjRBXDQAE72M=X6EbTAJ6i#N6(7V#QQ8q5e)Sn+BTA(DD{8@dDmW=G{Q-kCTKu';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "4- ".$codigoControl."</br>";

        // prueba 5
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '165401600000416';
        $numero_factura      = '6470';
        $nit_cliente         = '131585022';
        $fecha_compra        = '20160404';
        $monto_compra        = round(7296.81, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'PVZ642\HqJIB)yDBQ3$[5]$pyBaB#25{]CD{GM\[8@VYPAyCZQHW@NzFzdp3W6W5';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "5- ".$codigoControl."</br>";


        // prueba 6
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '357401600000412';
        $numero_factura      = '1882';
        $nit_cliente         = '1886721';
        $fecha_compra        = '20151125';
        $monto_compra        = round(7777.11, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'jDAv8n7@zm6V#E+3tfZ-9(PKc548M%WhR#K)U3+ixQmA_y(584JTu5=GSC-G[k+E';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "6- ".$codigoControl."</br>";

        // prueba 7
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '109801600000486';
        $numero_factura      = '5606';
        $nit_cliente         = '2339640';
        $fecha_compra        = '20160222';
        $monto_compra        = round(58087.32, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = '8%y*H62*MQ[%%Y*#W\)7Gk6AAbTxZ6E#RrABXB3W8DZuH{ZTKegy_G4AQ(aEZGu+';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "7- ".$codigoControl."</br>";

        // prueba 8
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '424601600000454';
        $numero_factura      = '8439';
        $nit_cliente         = '3192962';
        $fecha_compra        = '20160222';
        $monto_compra        = round(18468.35, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = '6C2-2=93N9[A%DnIjJ#H8WaGT%H=eZs@9_%5QC7I8MI6sirC2@S_5ud[QS)MUedk';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "8- ".$codigoControl."</br>";

        // prueba 9
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '228601600000103';
        $numero_factura      = '4968';
        $nit_cliente         = '4903602';
        $fecha_compra        = '20160115';
        $monto_compra        = round(88694.14, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'Y*36XZ_74mSG+DDn%FY3NFLe6*(cf)D#NR[NSNE[XJfS)JZPtJ(uY#BL$Xr[gLuj';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "9- ".$codigoControl."</br>";

        // prueba 10
        $facturador          = new CodigoControlV7();
        $numero_autorizacion = '151801600000423';
        $numero_factura      = '6799';
        $nit_cliente         = '1009373022';
        $fecha_compra        = '20160328';
        $monto_compra        = round(1815.26, 0, PHP_ROUND_HALF_UP);
        // $monto_compra        = 905;
        $clave               = 'NY{*M96+RIWDKNGGEBCzU+\ZI5H5QDX_5H)8UZWZDe34YUgWnkYhEU84b@2d2r#(';
        $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);
        echo "10- ".$codigoControl."</br>";


        // dd($codigoControl);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }
}
