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
            CLIENTES &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_cliente()"><i class="fas fa-plus"></i> &nbsp; NUEVO CLIENTE</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo Electronico</th>
                        <th>Celular</th>
                        <th>Razón Social</th>
                        <th>Nit</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $key => $cliente)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $cliente->name }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->celulares }}</td>
                            <td>{{ $cliente->razon_social }}</td>
                            <td>{{ $cliente->nit }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar cliente"  onclick="editar('{{ $cliente->id }}', '{{ $cliente->name }}', '{{ $cliente->email }}', '{{ $cliente->celulares }}', '{{ $cliente->nit }}', '{{ $cliente->razon_social }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar cliente"  onclick="eliminar('{{ $cliente->id }}', '{{ $cliente->name }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal nuevo usuario -->
<div id="modal_usuarios" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO CLIENTE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Cliente/guardar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input name="nombre_usuario" type="text" id="nombre_usuario" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <input name="email_usuario" type="email" id="email_usuario" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Celular(es)</label>
                                <input name="celular_usuario" type="text" id="celular_usuario" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input name="nit_usuario" type="text" id="nit_usuario" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Razón Social</label>
                                <input name="razon_social_usuario" type="text" id="razon_social_usuario" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Contraseña</label>
                                <input name="password_usuario" type="password" id="password_usuario" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Confirmar Contraseña</label>
                                <input name="confirm_password_usuario" type="password" id="confirm_password_usuario" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_usuario()">GUARDAR CLIENTE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nuevo usuario -->

<!-- inicio modal editar usuario -->
<div id="editar_usuarios" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR CLIENTE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Cliente/actualizar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input name="nombre" type="text" id="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <input name="email" type="email" id="email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Celular(es)</label>
                                <input name="celular" type="text" id="celular" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input name="nit" type="text" id="nit" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Razón Social</label>
                                <input name="razon_social" type="text" id="razon_social" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Contraseña</label>
                                <input name="password" type="password" id="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Confirmar Contraseña</label>
                                <input name="confirm_password" type="password" id="confirm_password" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_usuario()">ACTUALIZAR CLIENTE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar usuario -->

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
    function nuevo_cliente()
    {
        $("#modal_usuarios").modal('show');
    }

    function guardar_usuario()
    {
        var nombre_usuario = $("#nombre_usuario").val();
        var email_usuario = $("#email_usuario").val();
        var password_usuario = $("#password_usuario").val();
        var confirm_password_usuario = $("#confirm_password_usuario").val();
        var almacen_usuario = $("#almacen_usuario").val();

        if(nombre_usuario.length>0 && email_usuario.length>0 && password_usuario.length>0 && confirm_password_usuario.length>0 && password_usuario == confirm_password_usuario){
            Swal.fire(
                'Excelente!',
                'Una nuevo cliente fue registrado.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar los campos: <br>- Nombre <br>- Correo Electrónico <br>- Contraseña <br>- Confirmar Contraseña <br>Y que las contraseñas coincidan',
                'error'
            )
        }
        
    }

    function editar(id, nombre, email, celular, nit, razon_social)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#email").val(email);
        $("#celular").val(celular);
        $("#nit").val(nit);
        $("#razon_social").val(razon_social);
        $("#editar_usuarios").modal('show');
    }

    function actualizar_usuario()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        if(nombre.length>0 && email.length>0 && password.length>0 && confirm_password.length>0 && password==confirm_password){
            Swal.fire(
                'Excelente!',
                'Cliente actualizado correctamente.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar los campos: <br>- Nombre <br>- Correo Electrónico <br>- Contraseña <br>- Confirmar Contraseña <br>Y que las contraseñas coincidan',
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
                    'El cliente fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Cliente/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection