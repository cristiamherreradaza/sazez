@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            PROVEEDORES &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_proveedor()"><i class="fas fa-plus"></i> &nbsp; NUEVO PROVEEDOR</button>
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
                    @foreach($proveedores as $key => $proveedor)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $proveedor->nombre }}</td>
                            <td>{{ $proveedor->direccion }}</td>
                            <td>{{ $proveedor->telefonos }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar proveedor"  onclick="editar('{{ $proveedor->id }}', '{{ $proveedor->nombre }}', '{{ $proveedor->telefonos }}', '{{ $proveedor->direccion }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar proveedor"  onclick="eliminar('{{ $proveedor->id }}', '{{ $proveedor->nombre }}')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal nuevo proveedor -->
<div id="nuevo_proveedor" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO PROVEEDOR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Proveedor/guardar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="nombre_proveedor" type="text" id="nombre_proveedor" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Telefonos</label>
                                    <input name="telefonos_proveedor" type="text" id="telefonos_proveedor" class="form-control">
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
                                    <input name="direccion_proveedor" type="text" id="direccion_proveedor" class="form-control" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_proveedor()">GUARDAR PROVEEDOR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nuevo proveedor -->

<!-- inicio modal editar proveedor -->
<div id="editar_proveedores" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR PROVEEDOR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Proveedor/actualizar') }}"  method="POST" >
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
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_proveedor()">ACTUALIZAR PROVEEDOR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar proveedor -->

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
    function nuevo_proveedor()
    {
        $("#nuevo_proveedor").modal('show');
    }

    function guardar_proveedor()
    {
        var nombre_proveedor = $("#nombre_proveedor").val();
        var telefonos_proveedor = $("#telefonos_proveedor").val();
        var direccion_proveedor = $("#direccion_proveedor").val();
        if(nombre_proveedor.length>0 && direccion_proveedor.length>0){
            Swal.fire(
                'Excelente!',
                'Un nuevo proveedor fue registrado.',
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
        $("#editar_proveedores").modal('show');
    }

    function actualizar_proveedor()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var telefonos = $("#telefonos").val();
        var direccion = $("#direccion").val();
        if(nombre.length>0 && direccion.length>0){
            Swal.fire(
                'Excelente!',
                'Proveedor actualizado correctamente.',
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
                    'El proveedor fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Proveedor/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
