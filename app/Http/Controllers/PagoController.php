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

        return redirect("Pago/muestraPagos/$request->venta_id");
    }

}
