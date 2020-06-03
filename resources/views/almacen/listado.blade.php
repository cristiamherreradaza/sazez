@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            ALMACENES &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_almacen()"><i class="fas fa-plus"></i> &nbsp; NUEVO ALMACEN</button>
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
                                <button type="button" class="btn btn-warning" title="Editar almacen"  onclick="editar('{{ $almacen->id }}', '{{ $almacen->nombre }}', '{{ $almacen->telefonos }}', '{{ $almacen->direccion }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar almacen"  onclick="eliminar('{{ $almacen->id }}', '{{ $almacen->nombre }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal nuevo almacen -->
<div id="nuevo_almacen" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO ALMACEN</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Almacen/guardar') }}" class="needs-validation" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="nombre_almacen" type="text" id="nombre_almacen" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Telefonos</label>
                                    <input name="telefonos_almacen" type="text" id="telefonos_almacen" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Dirección</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="direccion_almacen" type="text" id="direccion_almacen" class="form-control" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_almacen()">GUARDAR ALMACEN</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nuevo almacen -->

<!-- inicio modal editar almacen -->
<div id="editar_almacenes" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR ALMACEN</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Almacen/actualizar') }}" class="needs-validation" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre" type="text" id="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Telefonos</label>
                                <input name="telefonos" type="text" id="telefonos" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Dirección</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="direccion" type="text" id="direccion" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_almacen()">ACTUALIZAR ALMACEN</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar almacen -->

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

<script>
    function nuevo_almacen()
    {
        $("#nuevo_almacen").modal('show');
    }

    function guardar_almacen()
    {
        var nombre_almacen = $("#nombre_almacen").val();
        var telefonos_almacen = $("#telefonos_almacen").val();
        var direccion_almacen = $("#direccion_almacen").val();
        if(nombre_almacen.length>0 && direccion_almacen.length>0){
            Swal.fire(
                'Excelente!',
                'Un nuevo almacen fue registrado.',
                'success'
            )
        }
    }

    function editar(id, nombre, telefonos, direccion)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#telefonos").val(telefonos);
        $("#direccion").val(direccion);
        $("#editar_almacenes").modal('show');
    }

    function actualizar_almacen()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var telefonos = $("#telefonos").val();
        var direccion = $("#direccion").val();
        if(nombre.length>0 && direccion.length>0){
            Swal.fire(
                'Excelente!',
                'Almacen actualizado correctamente.',
                'success'
            )
        }
    }

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
                    'El almacen fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Almacen/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
