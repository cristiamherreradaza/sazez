<?php

namespace App\Http\Controllers;

use App\Pago;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function muestraPagos($ventaId)
    {
        $datosVenta = Venta::find($ventaId);
        $pagos = Pago::where('venta_id', $ventaId)->get();
        return view('pago.muestraPagos')->with(compact('pagos', 'datosVenta'));
    }

    public function guardaPago(Request $request)
    {
        // dd($request->all());
        $pago             = new Pago();
        $pago->user_id    = Auth::user()->id;
        $pago->cliente_id = $request->cliente_id;
        $pago->venta_id   = $request->venta_id;
        $pago->fecha      = $request->fecha;
        $pago->importe    = $request->importe;
        $pago->save();

        $datosVenta = Venta::find($request->venta_id);
        $nuevoSaldo = $datosVenta->saldo - $request->importe;

        // cambiamos el saldo de la tabla ventas
        $venta = Venta::find($request->venta_id);
        $venta->saldo = $nuevoSaldo;
        $venta->save();

        return redirect("Pago/muestraPagos/$request->venta_id");
    }

    public function eliminar(Request $request, $pagoId)
    {
        $datosPago = Pago::find($pagoId);
        $datosVenta = Venta::find($datosPago->venta_id);

        $nuevoSaldo = $datosVenta->saldo + $datosPago->importe;

        // cambiamos el saldo de la tabla ventas
        $venta = Venta::find($datosPago->venta_id);
        $venta->saldo = $nuevoSaldo;
        $venta->save();

        $pago = Pago::find($pagoId);
        $pago->delete();
        return redirect("Pago/muestraPagos/$datosPago->venta_id");
    }

    public function deudaTotal(Request $request, $clienteId)
    {
        $ventas = Venta::where('cliente_id', $clienteId)
                        ->where('saldo >', 0)
                        ->get();
        dd($ventas);
    }

}