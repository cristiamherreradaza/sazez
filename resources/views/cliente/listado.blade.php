@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            CLIENTES &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_cliente()"><i class="fas fa-plus"></i> &nbsp; NUEVO CLIENTE</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input name="nombre_busqueda" type="text" id="nombre_busqueda" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Cedula</label>
                        <input name="cedula_busqueda" type="number" id="cedula_busqueda" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Nit</label>
                        <input name="nit_busqueda" type="number" id="nit_busqueda" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <p style="padding-top: 17px;"></p>
                    <button class="btn btn-block btn-success" onclick="ajaxListadoCliente()">Buscar</button>
                </div>
            </div>
            <div id="ajax_listado">

            </div>
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
            <form action="{{ url('Cliente/guardar') }}"  method="POST"  id="formularioAjaxNuevoCliente">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_usuario" type="text" id="nombre_usuario" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cedula de Identidad</label>
                                <input name="ci_usuario" type="text" id="ci_usuario" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="email_usuario" type="email" id="email_usuario" onchange="validaEmail()" class="form-control" required>
                                <small id="msgValidaEmail" class="badge badge-default badge-danger form-text text-white float-left" style="display: none;">El correo ya existe, introduzca otro.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Celular(es)</label>
                                <input name="celular_usuario" type="text" id="celular_usuario" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input name="nit_usuario" type="text" id="nit_usuario" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Razón Social</label>
                                <input name="razon_social_usuario" type="text" id="razon_social_usuario" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Contraseña</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="password_usuario" type="password" id="password_usuario" class="form-control" minlength="8" placeholder="Debe tener al menos 8 digitos" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" id="botonGuardaNuevoCliente" onclick="guardar_usuario()">GUARDAR CLIENTE</button>
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
            <form action="{{ url('Cliente/actualizar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre" type="text" id="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cedula de Identidad</label>
                                <input name="ci" type="text" id="ci" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="email" type="email" id="email" onchange="validaEmailEdicion()" class="form-control" required>
                                <small id="msgValidaEmailEdicion" class="badge badge-default badge-danger form-text text-white float-left" style="display: none;">El correo ya existe, introduzca otro.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Celular(es)</label>
                                <input name="celular" type="text" id="celular" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input name="nit" type="text" id="nit" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Razón Social</label>
                                <input name="razon_social" type="text" id="razon_social" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" id="botonGuardaEdicionCliente" onclick="actualizar_usuario()">ACTUALIZAR CLIENTE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar usuario -->

<!-- inicio modal cambiar contrasena -->
<div id="password_usuarios" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">CAMBIAR CONTRASE&Ntilde;A</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Cliente/password') }}" class="needs-validation" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_password" id="id_password" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Contraseña</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="password" type="password" id="password" class="form-control" minlength="8" placeholder="Debe tener al menos 8 digitos" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_password()">ACTUALIZAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal cambiar contrasena -->
@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    $(function () {
        $("#botonGuardaNuevoCliente").prop("disabled", false);
        $("#botonGuardaEdicionCliente").prop("disabled", false);

        ajaxListadoCliente();

    });

    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function ajaxListadoCliente(){
        console.log($('#nombre_busqueda').val())
        console.log($('#cedula_busqueda').val())
        console.log($('#nit_busqueda').val())

        let nombre = $('#nombre_busqueda').val();
        let cedula = $('#cedula_busqueda').val();
        let nit = $('#nit_busqueda').val();

        $.ajax({
            url: "{{ url('Cliente/ajaxListadoCliente') }}",
            data: {
                nombre: nombre,
                cedula: cedula,
                nit: nit
             },
            type: 'GET',
            success: function(data) {
                $('#ajax_listado').html(data)
            }
        });
    }

    function nuevo_cliente()
    {
        $("#modal_usuarios").modal('show');
    }

    function guardar_usuario()
    {
        var nombre_usuario = $("#nombre_usuario").val();
        var email_usuario = $("#email_usuario").val();
        var password_usuario = $("#password_usuario").val();
        var almacen_usuario = $("#almacen_usuario").val();

        if(nombre_usuario.length>0 && email_usuario.length>0 && password_usuario.length>7){
            Swal.fire(
                'Excelente!',
                'Una nuevo cliente fue registrado.',
                'success'
            )
        }
    }

    function editar(id, nombre, ci, email, celular, nit, razon_social)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#ci").val(ci);
        $("#email").val(email);
        $("#celular").val(celular);
        $("#nit").val(nit);
        $("#razon_social").val(razon_social);
        $("#editar_usuarios").modal('show');
    }

    function validaEmail()
    {
        let correo_cliente = $("#email_usuario").val();
        $.ajax({
            url: "{{ url('Cliente/ajaxVerificaCorreo') }}",
            data: { correo: correo_cliente },
            type: 'POST',
            success: function(data) {
                if (data.valida == 1) {
                    $("#msgValidaEmail").show();
                    $("#botonGuardaNuevoCliente").prop("disabled", true);
                }else{
                    $("#botonGuardaNuevoCliente").prop("disabled", false);
                    $("#msgValidaEmail").hide();
                }
            }
        });
    }

    function validaEmailEdicion()
    {
        let correo_cliente = $("#email").val();
        $.ajax({
            url: "{{ url('Cliente/ajaxVerificaCorreo') }}",
            data: { correo: correo_cliente },
            type: 'POST',
            success: function(data) {
                if (data.valida == 1) {
                    $("#msgValidaEmailEdicion").show();
                    $("#botonGuardaEdicionCliente").prop("disabled", true);
                }else{
                    $("#botonGuardaEdicionCliente").prop("disabled", false);
                    $("#msgValidaEmailEdicion").hide();
                }
            }
        });
    }

    function actualizar_usuario()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var email = $("#email").val();
        if(nombre.length>0 && email.length>0){
            Swal.fire(
                'Excelente!',
                'Cliente actualizado correctamente.',
                'success'
            )
        }
    }

    function contrasena(id)
    {
        $("#id_password").val(id);
        $("#password_usuarios").modal('show');
    }

    function actualizar_password()
    {
        var password = $("#password").val();
        if(password.length>7){
            Swal.fire(
                'Excelente!',
                'Contraseña cambiada.',
                'success'
            )
        }
    }

    function eliminar(id, nombre)
    {
        Swal.fire({
            title: 'Quieres borrar a ' + nombre + '?',
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
