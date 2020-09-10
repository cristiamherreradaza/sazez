@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/lobilist.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/jquery-ui.min.css') }}">
@endsection

@section('content')
<div class="card border-primary">
    <div class="card-header bg-primary">
        <h4 class="mb-0 text-white">DETALLE DE ENVIO</h4>
    </div>
    <div class="card-body" id="printableArea">
        <div class="row">
            <form class="form-horizontal col-md-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="fname2" class="col-sm-3 text-right control-label col-form-label text-dark-primary">Sucursal de Salida</label>
                                <div class="col-sm-9">
                                    <h4 class="form-control">{{ $datos->almacen_origen->nombre }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="lname2" class="col-sm-3 text-right control-label col-form-label text-dark-primary">Usuario que Envia</label>
                                <div class="col-sm-9">
                                    <h4 class="form-control">{{ $datos->user->name }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="uname1" class="col-sm-3 text-right control-label col-form-label text-dark-primary">Sucursal de Ingreso</label>
                                <div class="col-sm-9">
                                    <h4 class="form-control">{{ $datos->almacen->nombre }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nname" class="col-sm-3 text-right control-label col-form-label text-dark-primary">Fecha de Emision</label>
                                <div class="col-sm-9">
                                    <h4 class="form-control">{{ $datos->fecha }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="uname1" class="col-sm-3 text-right control-label col-form-label text-dark-primary">Numero de Pedido</label>
                                <div class="col-sm-9">
                                    <h4 class="form-control">{{ $datos->numero }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nname" class="col-sm-3 text-right control-label col-form-label text-dark-primary">Cantidad de productos</label>
                                <div class="col-sm-9">
                                    <h4 class="form-control">{{ count($productos) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Saldo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $key => $producto)
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $producto->producto->codigo }}</td>
                                    <td>{{ $producto->producto->nombre }}</td>
                                    <td>{{ $producto->producto->marca->nombre }}</td>
                                    <td>{{ $producto->producto->tipo->nombre }}</td>
                                    <td>{{ $producto->producto->modelo }}</td>
                                    <td class="text-center">{{ round($producto->ingreso) }}</td>
                                    @php
                                        $stock = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                                                ->where('producto_id', $producto->producto_id)
                                                ->where('almacene_id', $datos->almacen->id)
                                                ->first();
                                        $stock=intval($stock->total);
                                    @endphp
                                    <td class="text-center">{{ $stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-footer">
        <button id="botonImprimir" class="btn btn-inverse btn-block col-md-12 print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>

<script src="{{ asset('assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved.js') }}"></script>
<script src="{{ asset('assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script>
    $(function () {
        $('#config-table').DataTable({
            responsive: true,
            "order": [
                [0, 'asc']
            ],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

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
