@extends('layouts.app')

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
                <h2>GARANTIA DE VENTA</h2>
                <div class="row">
                    <h3><strong>Venta : </strong>{{ $venta->id }}</h3>
                    <hr>
                    <h3><strong>Cliente : </strong>{{ $venta->cliente->name }}</h3>
                    <hr>
                    <h3><strong>Fecha : </strong>{{ $venta->fecha }}</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Garantia</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($productos_venta as $productos)
                                <tr>
                                    <td scope="row">{{ $productos->producto->codigo }}</td>
                                    <td>{{ $productos->producto->nombre }}</td>
                                    <td>{{ $productos->producto->marca->nombre }}</td>
                                    <td>{{ $productos->producto->tipo->nombre }}</td>
                                    <td>{{ ($productos->precio_cobrado_mayor>0)?$productos->precio_cobrado_mayor:$productos->precio_cobrado }}</td>
                                    <td>{{ $productos->cantidad }}</td>
                                    <td>30/08/2020</td>
                                    @php
                                        if ($productos->precio_cobrado_mayor>0) {
                                            $precio_costo = $productos->precio_cobrado_mayor;
                                        }else{
                                            $precio_costo = $productos->precio_cobrado;
                                        }
                                        $subTotal = $precio_costo * $productos->cantidad;
                                        $total = $total + $subTotal;
                                    @endphp
                                    <td class="text-right">{{ $subTotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right" colspan="8">Total : {{ $total }}</td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
                <img src="{{ asset('qrs/1UTB-9V3T-6B96.png') }}" alt="">
                <br>
                <p><strong>Codigo de garantia generado en QR</strong></p>
                <p>
                    Puedes consultar sobre la garantia del producto<br>
                    con esta boleta.<br>
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
