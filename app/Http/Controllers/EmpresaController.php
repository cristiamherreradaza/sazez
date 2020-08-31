<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Parametros;
use CodigoControlV7;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function formulario()
    {
        $datosEmpresa = Empresa::first();
        $datosParametros = Parametros::where('estado', 'Activo')->get();

        // dd($datosEmpresa);
        // $facturador = new CodigoControlV7();
        // $numero_autorizacion = '29040011007';
        // $numero_factura = '1503';
        // $nit_cliente = '4189179011';
        // $fecha_compra = '20070702';
        // $monto_compra = '2500';
        // $clave = '9rCB7Sv4X29d)5k7N%3ab89p-3(5[A';
        // dd(CodigoControlV7::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave));
        // dd($facturador::generar($numero_autorizacion, $numero_factura, $nit_cliente, $fecha_compra, $monto_compra, $clave));

        return view('empresa.formulario')->with(compact('datosEmpresa', 'datosParametros'));
    }

    public function guarda(Request $request)
    {
        if($request->id){
            $empresa = Empresa::find($request->id);
        }else{
            $empresa = new Empresa();
        }
        $empresa->nombre             = $request->nombre;
        $empresa->direccion          = $request->direccion;
        $empresa->actividad          = $request->actividad;
        $empresa->leyenda_consumidor = $request->derechos;
        $empresa->telefono           = $request->telefono;
        $empresa->fax                = $request->fax;
        $empresa->telefono_fijo      = $request->telefono_fijo;
        $empresa->nit                = $request->nit;
        $empresa->save();
        return redirect('Empresa/formulario');
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
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
