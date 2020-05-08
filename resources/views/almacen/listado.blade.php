@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endsection

@section('content')
<div class="card card-outline-info">
    <div class="card-header">
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
            <form action="{{ url('Almacen/guardar') }}" method="POST">
                @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
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
            <form action="{{ url('Almacen/actualizar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
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
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable();
        // responsive table
        $('#config-table').DataTable({
            responsive: true
        });
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });

        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    });

</script>
<!-- Sweet-Alert  -->
<script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweet-alert.init.js') }}"></script>

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
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar los campos Nombre y Direccion',
                'error'
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
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar los campos Nombre y Direccion',
                'error'
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
