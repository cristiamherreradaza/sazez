@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">DEUDAS CLIENTE</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">
                            <h4><span class="text-info">CLIENTE: </span> {{ $ventas[0]->cliente->name }}</h4>
                        </th>
                        <th scope="col">
                            <h4><span class="text-info">DEUDA TOTAL: </span> {{ $saldoTotal }}</h4>
                        </th>
                        <th scope="col">
                            <h4><span class="text-info">VENTAS CON SALDOS: </span> {{ $cantidadVentas }}</h4>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12">
            <div class="table-responsive m-t-40">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Venta</th>
                            <th>Tienda</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                            <th>Opciones</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas as $key => $v)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->almacen->nombre }}</td>
                            <td>{{ $v->user->name }}</td>
                            <td>{{ $v->total }}</td>
                            <td>{{ $v->saldo }}</td>
                            <td>{{ $v->fecha }}</td>
                            <td>
                                <a href="{{ url("Venta/muestra/$v->id") }}" class="btn btn-info" title="Ver detalle"><i class="fas fa-eye"></i></a>
                                <a href="{{ url("Pago/muestraPagos/$v->id") }}" class="btn btn-success text-white" title="Pagos Venta"><i class="fas fa-donate"></i> </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
@endsection