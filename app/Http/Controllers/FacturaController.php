<?php

namespace App\Http\Controllers;

use DataTables;
use App\Empresa;
use App\Factura;
use App\Almacene;
use App\Ventasfac;
use App\Parametros;
use CodigoControlV7;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function almacenes()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('factura.listado')->with(compact('almacenes'));   
    }

    public function formulario_empresa($id)
    {
        $almacen = Almacene::whereNull('estado')
                            ->where('id', $id)
                            ->first();
        $empresa = Empresa::where('almacene_id', $id)->first();
        $parametros = Parametros::where('estado', 'Activo')
                                ->where('almacene_id', $id)
                                ->whereNull('deleted_at')
                                ->get();
                                //->first();
        if(!$empresa){                      // Si no tiene empresa
            $empresa = new Empresa();       // La creamos
            $empresa->almacene_id = $almacen->id;
            $empresa->nombre = $almacen->nombre;
            $empresa->direccion = $almacen->direccion;
            $empresa->telefono = $almacen->telefonos;
            $empresa->save();
        }
        return view('factura.formulario_empresa')->with(compact('empresa', 'parametros'));
    }

    public function guarda_formulario(Request $request)
    {
        // Guardamos datos de la empresa
        $empresa = Empresa::find($request->empresa_id);
        $empresa->almacene_id        = $request->almacene_id;
        $empresa->nombre             = $request->nombre;
        $empresa->direccion          = $request->direccion;
        $empresa->actividad          = $request->actividad;
        $empresa->leyenda_consumidor = $request->leyenda_consumidor;
        $empresa->telefono           = $request->telefono;
        $empresa->fax                = $request->fax;
        $empresa->email              = $request->email;
        $empresa->telefono_fijo      = $request->telefono_fijo;
        $empresa->nit                = $request->nit;
        $empresa->save();
        // Guardamos parametros de la factura
        if($request->numero_autorizacion && $request->llave_dosificacion && $request->numero_factura && $request->fecha_limite && $request->validador_autorizacion == 1 && $request->validador_dosificacion == 1 && $request->validador_inicial == 1 && $request->validador_emision == 1)
        {
            $parametro = Parametros::where('almacene_id', $request->almacene_id)
                                    ->first();
            if(!$parametro){
                $parametro = new Parametros();
            }
            $parametro->almacene_id = $request->almacene_id;
            $parametro->numero_autorizacion = $request->numero_autorizacion;
            $parametro->llave_dosificacion = $request->llave_dosificacion;
            $parametro->numero_factura = $request->numero_factura;
            $parametro->fecha_limite = $request->fecha_limite;
            $parametro->estado = 'Activo';
            $parametro->save();
        }
        //return redirect('Empresa/formulario');
    }

    public function reporte()
    {
        $almacenes = Almacene::whereNull('estado')->get();
        return view('factura.reporte')->with(compact('almacenes'));
    }

    public function ajax_listado(Request $request)
    {
        $facturas = DB::table('facturas')
                        ->whereNull('facturas.deleted_at')
                        ->where('facturas.almacene_id', $request->almacen_id)
                        ->whereBetween('facturas.fecha_compra', [$request->fecha_inicial, $request->fecha_final])
                        ->leftJoin('almacenes', 'facturas.almacene_id', '=', 'almacenes.id')
                        ->leftJoin('users', 'facturas.cliente_id', '=', 'users.id')
                        ->select(
                            'almacenes.nombre as tienda',
                            'users.name as cliente',
                            'facturas.numero_factura as numero_factura',
                            'facturas.nit_cliente as nit_cliente',
                            'facturas.fecha_compra as fecha_compra',
                            'facturas.monto_compra as monto_compra'
                        );
        return Datatables::of($facturas)->make(true);
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
    
    public function formulario()
    {
        return view('factura.formulario');
    }

    public function guardaVenta(Request $request)
    {
        $fechaHora = date('Y-m-d H:i:s');
        $fecha = date('Y-m-d');

        $ultimoParametro = Parametros::where('almacene_id', Auth::user()->almacen_id)
            ->latest()
            ->first();

        // preguntamos si la venta ya tiene una factura creada
        if($ultimoParametro != null && $ultimoParametro->estado == 'Activo')
        {
            // tramemos los parametros de la facturacion
            $parametrosFactura = Parametros::where('estado', 'Activo')->first();

            // obtenemos el ultimo numero de factura
            $ultimoNumeroFactura = Factura::latest()->first();

            if($ultimoNumeroFactura == null){
                $nuevoNumeroFactura = $parametrosFactura->numero_factura;
            }else{
                $nuevoNumeroFactura = $ultimoNumeroFactura->numero_factura+1;
            }

            $fechaParaCodigo = str_replace("-", "", $fecha);

            // generamos el codigo de control
            $facturador          = new CodigoControlV7();
            $numero_autorizacion = $parametrosFactura->numero_autorizacion;
            $numero_factura      = $nuevoNumeroFactura;
            $nit_cliente         = $request->nit;
            $fecha_compra        = $fechaParaCodigo;
            $monto_compra        = round($request->totalVenta, 0, PHP_ROUND_HALF_UP);
            $clave               = $parametrosFactura->llave_dosificacion;
            $codigoControl       = $facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave);

            // creamos la factura
            $nuevaFactura                      = new Factura();
            $nuevaFactura->user_id             = Auth::user()->id;
            $nuevaFactura->almacene_id         = Auth::user()->almacen_id;
            $nuevaFactura->numero_autorizacion = $parametrosFactura->numero_autorizacion;
            $nuevaFactura->numero_factura      = $nuevoNumeroFactura;
            $nuevaFactura->nit_cliente         = $request->nit;
            $nuevaFactura->fecha_compra        = $fechaHora;
            $nuevaFactura->fecha_limite        = $parametrosFactura->fecha_limite;
            $nuevaFactura->monto_compra        = round($request->totalVenta, 0, PHP_ROUND_HALF_UP);
            $nuevaFactura->clave               = $parametrosFactura->llave_dosificacion;
            $nuevaFactura->codigo_control      = $codigoControl;
            $nuevaFactura->save();
            $facturaId = $nuevaFactura->id;

        }

        $cantidadItems = count($request->cantidad);
        for ($i=0; $i < $cantidadItems; $i++) { 

            echo $request->cantidad[$i].' - '.$request->precio[$i].' - '.$request->subtotal[$i]."<br />";
            $venta                  = new Ventasfac();
            $venta->user_id         = Auth::user()->id;
            $venta->almacene_id     = Auth::user()->almacen_id;
            $venta->factura_id      = $facturaId;
            $venta->nombre          = $request->nombre;
            $venta->nit             = $request->nit;
            $venta->nombre          = $request->producto[$i];
            $venta->producto        = $request->producto[$i];
            $venta->precio_unitario = $request->precio[$i];
            $venta->subtotal        = $request->subtotal[$i];
            $venta->fecha           = date("Y-m-d");
            $venta->save();
        }
        echo $cantidadItems;
        dd($request->all());

        return redirect('Venta/imprimeFactura/15');
    }
}
