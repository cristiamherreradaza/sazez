@extends('layouts.cupones')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/lobilist.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-center" id="printableArea">
            <div class="card-header">
                SAZEZ
            </div>
            <!-- contenido de impresion -->
            <div class="card-body">
                <h2>CUPÓN DE DESCUENTO</h2>
                <div class="row">
                    <ul class="text-left">
                        <li><strong> PRODUCTO : </strong><br> {{ $cupon->producto->nombre }}</li>
                        @php
                            $precio = App\Precio::where('producto_id', $cupon->producto->id)->where('escala_id', 1)->first();
                            $precio = round($precio->precio);
                        @endphp
                        <li><strong> PRECIO NORMAL : </strong><br> {{ $precio }} Bs.</li>
                        <li><strong> PRECIO DESCUENTO : </strong><br> {{ round($cupon->monto_total) }} Bs.</li>
                        <li>
                            <strong> TIENDA : </strong><br> 
                            @if($cupon->almacene_id)
                                {{ $cupon->almacen->nombre }}, ubicado en {{ $cupon->almacen->direccion }}
                            @else
                            <!-- <table>
                                    @foreach($almacenes as $almacen)
                                        <tr>
                                            <td>{{ $almacen->nombre }}, ubicado en {{ $almacen->direccion }}</td>
                                        </tr>
                                    @endforeach
                                </table> -->
                                Cualquier Sucursal
                            @endif
                        </li>
                    </ul>
                </div>
                <img src="{{ asset('qrs/' .$cupon->codigo. '.png') }}" alt="">
                <br>
                <p><strong>Cupón valido hasta {{ $cupon->fecha_final }}.</strong></p>
                <p>
                    Al momento de tu compra, muestra <br>
                    este ticket y se realizara el descuento.<br>
                    Visitanos y conoce nuestros ofertas.
                </p>
            </div>
            <!-- contenido de impresion -->
            <div class="card-footer">
                © 2015 - {{ date('Y') }} Sazez.net
            </div>
        </div>
    </div>
</div>
<div class="row">
<button id="botonImprimir" class="btn btn-success btn-block col-md-4 print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button>
</div>
@stop

@section('js')
<script src="{{ asset('assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved.js') }}"></script>
<script src="{{ asset('assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script>
    $("#botonImprimir").click(function() {
		var mode = 'iframe'; //popup
		var close = mode == "popup";
		var options = {
				mode: mode,
				popClose: close
		};
		$("div#printableArea").printArea(options);
	});
</script>
@endsection
