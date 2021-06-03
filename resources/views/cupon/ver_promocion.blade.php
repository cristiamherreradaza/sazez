@extends('layouts.cupones')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/lobilist.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header" style="background-color: #2C2E7B;">
                <img src="{{ asset('assets/images/logoSmartZone.jpg') }}" alt="homepage" class="dark-logo" />
            </div>
            <!-- contenido de impresion -->
            <div class="card-body">
                @if ($cupon->producto_id == null)
                    {{-- datos del cupon cuando es promo --}}
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="text-primary text-center">DATOS DEL CUPON</h2>
                            <h4><span class="text-primary">PROMO: </span> {{ $promocion->nombre }}</h4>
                            <h4 class="text-center"><span class="text-primary">PRODUCTOS </span> </h4>
                            <div class="table-responsive m-t-40">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-primary">#</th>
                                            <th class="text-primary">Nombre</th>
                                            <th class="text-center text-primary">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $precioPromo = 0;
                                        @endphp
                                        @foreach ($productos_promocion as $key => $p)
                                        @php
                                            $precioPromo += $p->precio;
                                        @endphp
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td>{{ $p->producto->nombre }}</td>
                                                <td class="text-center">{{ $p->cantidad }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <h4><span class="text-primary">PRECIO: </span> {{ round($precioPromo, 0) }} Bolivianos</h4>
                            <hr />
                            <div id="bloqueRegistro">
                                <h3 class="text-center text-success">REGISTRATE PARA EL CUPON</h3>
                                <form action="#" id="formularioCupon">
                                    @csrf
                                    <input type="hidden" name="cupon_id" id="cupon_id" value="{{ $cupon->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    CARNET
                                                    <span class="text-danger">
                                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                                    </span>
                                                </label>
                                                <input type="number" class="form-control" name="ci" id="ci" autofocus required>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    NOMBRE
                                                    <span class="text-danger">
                                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" name="name" id="name" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    NIT
                                                </label>
                                                <input type="number" class="form-control" name="nit" id="nit">
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    RAZON SOCIAL
                                                </label>
                                                <input type="text" class="form-control" name="razon_social" id="razon_social">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <button class="btn btn-success btn-block" type="button" onclick="enviaFormularioCupon()">OBTENER CUPON</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- datos del cupon cuando es promo --}}
                @else
                    {{-- datos del cupon cuando es por producto --}}
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="text-primary text-center">DATOS DEL CUPON</h2>
                            <h4><span class="text-primary">PRODUCTO: </span> {{ $producto->nombre }}</h4>
                            <h5><span class="text-danger">PRECIO ANTES: </span> <span style="text-decoration:line-through;">{{ round($precio->precio, 0) }} Bs.</span></h5>
                            <h5><span class="text-info">PRECIO AHORA: </span> {{ round($cupon->monto_total, 0) }} Bs.</h5>
                            <hr />
                            <div id="bloqueRegistro">
                                <h3 class="text-center text-success">REGISTRATE PARA EL CUPON</h3>
                                <form action="#" id="formularioCupon">
                                    @csrf
                                    <input type="hidden" name="cupon_id" id="cupon_id" value="{{ $cupon->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    CARNET
                                                    <span class="text-danger">
                                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                                    </span>
                                                </label>
                                                <input type="number" class="form-control" name="ci" id="ci" autofocus required>
                                            </div>
                                        </div>
                    
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    NOMBRE
                                                    <span class="text-danger">
                                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" name="name" id="name" required>
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    NIT
                                                </label>
                                                <input type="number" class="form-control" name="nit" id="nit">
                                            </div>
                                        </div>
                    
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="nombre">
                                                    RAZON SOCIAL
                                                </label>
                                                <input type="text" class="form-control" name="razon_social" id="razon_social">
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="row">
                    
                                        <div class="col-md-12">
                                            <button class="btn btn-success btn-block" type="button" onclick="enviaFormularioCupon()">OBTENER
                                                CUPON</button>
                                        </div>
                    
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- datos del cupon cuando es por producto --}}
                @endif
                <h5 class="font-italic text-center">
                    @php
                        $utilidades = new App\librerias\Utilidades();
                        $fechaCuponValido = $utilidades->fechaHoraCastellano($cupon->fecha_final);
                    @endphp
                    Cupón valido hasta {{ $fechaCuponValido }}</h5>
                    Direccion:
                    @if ($cupon->almacene_id == null)
                        Todas las tiendas SmartZone
                    @else
                        {{ $cupon->almacen->nombre }}, {{ $cupon->almacen->direccion }}
                    @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
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

    function enviaFormularioCupon()
    {
        let datosFormularioCupon = $("#formularioCupon").serializeArray();

        if(document.getElementById('formularioCupon').checkValidity()){
            Swal.fire({
                    type: 'success',
                    title: 'Excelente',
                    text: 'Se realizo el registro'
                    });
            $.ajax({
                url: "{{ url('Cupon/registraClienteCupon') }}",
                data: datosFormularioCupon,
                type: 'POST',
                success: function(data) {
                    $("#bloqueRegistro").html(data);
                    /*if (data.errorVenta == 0) {

                        window.location.href = "{{ url('Venta/muestra') }}/"+data.ventaId;

                    } else {

                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No tienes las cantidades suficientes.'
                        })
                    }*/
                }
            });

        }else{
            document.getElementById('formularioCupon').reportValidity()
        }
    }
</script>
@endsection
