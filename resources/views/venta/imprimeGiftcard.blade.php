@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/lobilist.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card text-center">
            <div class="card-header">
                SAZEZ
            </div>
            <!-- contenido de impresion -->
            <div class="card-body">
                <h2>IMPRESION DE GIFTCARD</h2>
                <div class="row">
                    <h3><strong>VENTA : </strong>{{ $venta->id }}</h3>
                    <hr>
                    <h3><strong>CLIENTE : </strong>
                        {{ $venta->cliente != null? $venta->cliente->razon_social: "" }}
                        <br>
                        {{ $venta->cliente != null? $venta->cliente->celulares: "" }}
                    </h3>
                    <hr>
                    <h3><strong>FECHA VENTA: </strong>{{ $venta->fecha }}</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Garantia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;

                                $montoGC = 0;
                                $numeroTargejaGC ="";
                                $pinGC = "";
                                $fechaVencimientoGC = "";
                            @endphp
                            @foreach($productos_venta as $productos)
                                <tr>
                                    <td scope="row">{{ $productos->producto->codigo }}</td>
                                    <td>{{ $productos->producto->nombre }}</td>
                                    <td>{{ $productos->producto->marca->nombre }}</td>
                                    <td>{{ $productos->producto->tipo->nombre }}</td>
                                    <td>{{ $productos->cantidad }}</td>
                                    <td>{{ $productos->fecha_garantia }}</td>

                                    @php
                                        if ($productos->precio_cobrado_mayor>0) {
                                            $precio_costo = $productos->precio_cobrado_mayor;
                                        }else{
                                            $precio_costo = $productos->precio_cobrado;
                                        }
                                        $subTotal = $precio_costo * $productos->cantidad;
                                        $total = $total + $subTotal;

                                        $clienteNombre = $venta->cliente != null? $venta->cliente->razon_social: "";
                                        $clienteCelular = $venta->cliente != null? $venta->cliente->celulares: "";
                                        $montoGC = $precio_costo;

                                        $numeroTargejaGC = Str::upper(md5($venta->id . $clienteNombre .$clienteCelular));

                                        $numeroTargejaGC = md5(rand(1, 5) . microtime());
                                        $pinGC = $venta->id;
                                        $fechaVencimientoGC = $productos->fecha_garantia;

                                        $numeroTargejaQR = $venta->id ."|". $clienteNombre ."|".$clienteCelular ."|" . $fechaVencimientoGC;

                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>



            </div>



            @php


$DesdeLetra = "a";
$HastaLetra = "z";
$DesdeNumero = 0;
$HastaNumero = 9;

$letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
$numeroAleatorio = rand($DesdeNumero, $HastaNumero);
            //echo $letraAleatoria;
            //echo "<br>";
            //echo $numeroAleatorio;
            $facturador          = new App\librerias\Utilidades();
            //echo $facturador::generarGC(4);

            $numeroTargejaGCOld =  $facturador::generarGC(4) . "-" . $facturador::generarGC(4) . "-" . $facturador::generarGC(4) . "-" . $venta->id;

            $numeroTargejaGC =  $gc_data->serial;

            @endphp

            <br>
            <div class="row" id="printableArea">
                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="d-flex align-item">
                                <div class=" border border-dark border-bold " style="padding: 10px">
                                <div class="text-left">
                                    <h3>Monto Bs.- <b>{{ $montoGC }}</b></h3>
                                    <br>
                                </div>
                                <div class="text-left">
                                    <h6>Número de tarjeta:</h6>
                                </div>
                                <div>
                                    <h2 class="text-center">
                                        {{ $numeroTargejaGC }}
                                    </h2>
                                </div>
                                <div>
                                    {{-- <center>
                                        <div id="qrcode"></div>
                                    </center> --}}
                                    <br>
                                </div>
                                <div  class="text-right">
                                    {{-- <h5>Pin: <b>{{ $pinGC }}</b></h5> --}}
                                    <h5>Vencimiento: <b>{{ $fechaVencimientoGC }}</b></h5>
                                </div>
                                <div class="text-center" style="font-size: 8px">
                                    ©{{ date('Y') }} Sazez.net
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <button id="botonImprimir" class="btn btn-success btn-block col-md-8 print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button>
</div>
@stop

@section('js')
<script src="{{ asset('assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved.js') }}"></script>
<script src="{{ asset('assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script src="{{ asset('dist/js/qrcode.min.js') }}"></script>
<script>
    $("#botonImprimir2").click(function() {
		var mode = 'iframe'; //popup
		var close = mode == "popup";
		var options = {
				mode: mode,
				popClose: close
		};
		$("div#printableArea").printArea(options);
	});

    function immprimirArea(){
		var mode = 'iframe'; //popup
		var close = mode == "popup";
		var options = {
				mode: mode,
				popClose: close
		};
		$("div#printableArea").printArea(options);
    }

    $(document).on('click', '#botonImprimir', function(e) {

        e.preventDefault
        let ventaId = '{{ $venta->id }}';
        let productoId = '{{ $producto_id }}';
        let gcImpreso = '{{ $gc_impreso }}';

        if( (gcImpreso) && ((Number(gcImpreso) === 1) || (gcImpreso === "1"))){

            Swal.fire({
                    type: "warning",
                    title: "GIFTCARD YA IMPRESO!",
                    text: "No puede IMPRIMIR La tarjeta GIFTCARD."
            }).then((result) => {

                window.location.href = "{{ url('Venta/muestra') }}/"+ventaId;

            });
        }else{

            Swal.fire({
                title: '¿Confirma IMPRIMIR la tarjeta GIFTCARD? , confirme',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, imprimir la GIFTCARD!',
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.value) {

                    gcImpreso = 1;

                    $.ajax({
                        url: "{{ url('Venta/imprimirGiftcard')}}/"+ventaId+'/'+productoId+'/'+gcImpreso,
                        success: function(data) {

                            if(data.estado == true){
                                immprimirArea();
                                Swal.fire({
                                        type: data.type,
                                        title: data.title,
                                        text: data.mensaje
                                }).then((result) => {
                                    window.location.href = "{{ url('Venta/muestra') }}/"+ventaId;
                                });
                            }else{

                                Swal.fire({
                                        type: data.type,
                                        title: data.title,
                                        text: data.mensaje
                                }).then((result) => {

                                    window.location.href = "{{ url('Venta/muestra') }}/"+ventaId;

                                });

                            }
                        }
                    });
                }
            });
        }

    });





    //let cadenaQr = "{{ $numeroTargejaQR }}";
    // console.log(cadenaQr);
    // var qrcode = new QRCode("qrcode", {
    //     text: cadenaQr,
    //     width: 150,
    //     height: 150,
    //     colorDark : "#000000",
    //     colorLight : "#ffffff",
    //     correctLevel : QRCode.CorrectLevel.H
    // });


    function generarGC(x) {
		    if(!x) { x = 16; }
		    var chars = "1234567890";
		    //var chars = "{{ $venta->id }}";
		    var no = "";
		    for (var i=0; i<x; i++) {
		       var rnum = Math.floor(Math.random() * chars.length);
		       no += chars.substring(rnum,rnum+1);
		   }
		   return no;
		}
        //console.log(Math.floor(Math.random() * 10));
        var ngc = "{{ $venta->id }}" + "-" + generarGC(4)+ "-" + generarGC(4)+ "-" + generarGC(4);

        $("#nroGC").html(ngc);


</script>
@endsection
