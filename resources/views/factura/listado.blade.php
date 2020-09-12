@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            ALMACENES &nbsp;&nbsp;
            <!-- <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_almacen()"><i class="fas fa-plus"></i> &nbsp; NUEVO ALMACEN</button> -->
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfonos</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($almacenes as $key => $almacen)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $almacen->nombre }}</td>
                            <td>{{ $almacen->direccion }}</td>
                            <td>{{ $almacen->telefonos }}</td>
                            <td>
                                <a type="button" class="btn btn-primary" title="Formulario de Empresa" href="{{ url('Factura/formulario_empresa/'.$almacen->id) }}"><i class="fas fa-edit"></i></a>
                                <!-- <button type="button" class="btn btn-warning" title="Editar almacen"  onclick="editar('{{ $almacen->id }}', '{{ $almacen->nombre }}', '{{ $almacen->telefonos }}', '{{ $almacen->direccion }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar almacen"  onclick="eliminar('{{ $almacen->id }}', '{{ $almacen->nombre }}')"><i class="fas fa-trash-alt"></i></button> -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
@endsection
