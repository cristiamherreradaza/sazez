@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">FORMULARIO DE PAGOS</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"><h4><span class="text-info">VENTA:</span>  {{ $datosVenta->id }}</h4></th>
                        <th scope="col"><h4><span class="text-info">CLIENTE:</span> {{ $datosVenta->cliente->name }}</h4></th>
                        <th scope="col"><h4><span class="text-info">FECHA:</span> {{ $datosVenta->fecha }}</h4></th>
                        <th scope="col"><h4><span class="text-info">TOTAL:</span> {{ $datosVenta->total }}</h4></th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="row">
            <div class="col-md-3">
                <form action="{{ url('Pago/guardaPago') }}" method="POST">
                    @csrf
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Importe</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input type="hidden" name="cliente_id" value="{{ $datosVenta->cliente_id }}">
                                    <input type="hidden" name="venta_id" value="{{ $datosVenta->id }}">
                                    <input name="importe" type="number" id="importe" class="form-control" min="1" max="{{ $datosVenta->saldo }}" step="any" autofocus required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="fecha" type="date" id="fecha" value="{{ date('Y-m-d') }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn waves-effect waves-light text-white btn-block btn-success" id="btnGuardaCliente">GUARDAR PAGO</button>
                </form>
            </div>
            <div class="col-md-9">
                <div class="card border-primary">
                    <div class="card-header bg-primary">
                        <h4 class="mb-0 text-white">PAGOS REALIZADOS</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive m-t-40">
                            <table id="myTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th class="text-center">Importe</th>
                                        <th class="text-center">Fecha</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalImportes = 0;
                                    @endphp
                                    @foreach($pagos as $key => $p)
                                    @php
                                        $totalImportes += $p->importe;
                                    @endphp
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{ $p->user->name }}</td>
                                        <td class="text-right">{{ $p->importe }}</td>
                                        <td class="text-center">{{ $p->fecha }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger" title="Elimina Pago" onclick="eliminar('{{ $p->id }}', '{{ $p->importe }}')"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col"><h3>TOTAL: <span class="text-info">{{ $datosVenta->total }}</span></h3></th>
                                            <th scope="col"><h3>IMPORTE TOTAL: <span class="text-success">{{ $totalImportes }}</span></h3></th>
                                            <th scope="col"><h3>SALDO: <span class="text-danger">{{ $datosVenta->total - $totalImportes }}</span></h3></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
function eliminar(id, nombre)
{
    Swal.fire({
        title: 'Quieres borrar ' + nombre + '?',
        text: "Luego no podras recuperarlo!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, estoy seguro!',
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.value) {
            Swal.fire(
                'Excelente!',
                'El importe fue eliminado',
                'success'
            );
            window.location.href = "{{ url('Pago/eliminar') }}/"+id;
        }
    })
}
</script>
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
@endsection