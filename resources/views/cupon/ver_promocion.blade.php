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
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-primary text-center">DATOS DEL CUPON</h2>
                        <h3><span class="text-primary">PROMOCIÓN: </span> {{ $promocion->nombre }}</h3>
                        <h3 class="text-center"><span class="text-primary">PRODUCTOS </span> </h3>
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
                        <h3><span class="text-primary">PRECIO: </span> {{ round($precioPromo, 0) }} Bolivianos</h3>
                        <hr />
                        <h3 class="text-center text-success">REGISTRATE PARA EL CUPON</h3>
                        <form action="{{ url('Cupon/registraClienteCupon') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">
                                            CARNET O NIT
                                            <span class="text-danger">
                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" name="ci" id="ci" autofocus required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">
                                            EMAIL
                                            <span class="text-danger">
                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" name="email" id="email" autofocus required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nombre">
                                            NOMBRE
                                            <span class="text-danger">
                                                <i class="mr-2 mdi mdi-alert-circle"></i>
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" name="name" id="name" autofocus required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button id="botonImprimir" class="btn btn-success btn-block" type="button">OBTENER CUPON</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <h5 class="font-italic text-center">
                    @php
                        function fechaCastellano ($fecha) {
                        $fecha = substr($fecha, 0, 10);
                        $numeroDia = date('d', strtotime($fecha));
                        $dia = date('l', strtotime($fecha));
                        $mes = date('F', strtotime($fecha));
                        $anio = date('Y', strtotime($fecha));
                        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
                        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
                        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
                        "Noviembre", "Diciembre");
                        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                        "November", "December");
                        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
                        return $nombredia.", ".$numeroDia." de ".$nombreMes." de ".$anio;
                        }

                        $fechaArray = explode(" ", $cupon->fecha_final);

                    @endphp

                    Cupón valido hasta {{ fechaCastellano($cupon->fecha_final) }} Horas: {{ $fechaArray[1] }}</h5>                

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
</script>
@endsection
