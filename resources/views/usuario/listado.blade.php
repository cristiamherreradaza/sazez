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
            USUARIOS &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_usuario()"><i class="fas fa-plus"></i> &nbsp; NUEVO USUARIO</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Almacen</th>
                        <th>Perfil</th>
                        <th>Nombre</th>
                        <th>Correo Electronico</th>
                        <th>CI</th>
                        <th>Celular</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $key => $usuario)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>
                                @if($usuario->almacen_id)
                                    {{ $usuario->almacen->nombre }}
                                @else
                                    {{ $usuario->almacen_id }}
                                @endif
                            </td>
                            <td>{{ $usuario->rol }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->ci }}</td>
                            <td>{{ $usuario->celulares }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar usuario"  onclick="editar('{{ $usuario->id }}', '{{ $usuario->name }}', '{{ $usuario->ci }}', '{{ $usuario->email }}', '{{ $usuario->celulares }}', '{{ $usuario->nit }}', '{{ $usuario->razon_social }}', '{{ $usuario->perfil_id }}', '{{ $usuario->almacen_id }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-primary" title="Editar permisos"  onclick="permisos('{{ $usuario->id }}', '{{ $usuario->perfil_id }}')"><i class="fas fa-list"></i></button>
                                <button type="button" class="btn btn-info" title="Cambiar contraseña"  onclick="contrasena({{ $usuario->id }})"><i class="fas fa-key"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar usuario"  onclick="eliminar('{{ $usuario->id }}', '{{ $usuario->name }}')"><i class="fas fa-trash-alt"></i></button>
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
                <h4 class="modal-title" id="myModalLabel">NUEVO USUARIO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('User/guardar') }}"  method="POST" >
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Perfil</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="perfil_usuario" id="perfil_usuario" class="form-control" required>
                                    <option value="" selected> Seleccione </option>
                                    @foreach($perfiles as $perfil)
                                        <option value="{{ $perfil->id }}">{{ $perfil->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="ventana_almacen_existente">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Almacen</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="almacen_usuario" id="almacen_usuario" class="form-control">
                                    <option value="" selected> Seleccione </option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row ventana_almacen_nuevo">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label">Nombre de Almacen</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_nuevo_almacen" type="text" id="nombre_nuevo_almacen" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Telefonos</label>
                                <input name="telefonos_nuevo_almacen" type="text" id="telefonos_nuevo_almacen" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row ventana_almacen_nuevo">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Dirección de Almacen</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="direccion_nuevo_almacen" type="text" id="direccion_nuevo_almacen" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" id="botonGuardaNuevoUsuario" onclick="guardar_usuario()">GUARDAR USUARIO</button>
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
                <h4 class="modal-title" id="myModalLabel">EDITAR USUARIO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('User/actualizar') }}"  method="POST" >
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Perfil</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="perfil" id="perfil" class="form-control" required>
                                    <option value="" selected> Seleccione </option>
                                    @foreach($perfiles as $perfil)
                                        <option value="{{ $perfil->id }}">{{ $perfil->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Almacen</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="almacen" id="almacen" class="form-control" required>
                                    <option value="" selected>Seleccione</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" id="botonGuardaEdicionUsuario" onclick="actualizar_usuario()">ACTUALIZAR USUARIO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar usuario -->

<!-- inicio modal editar perfil -->
<div id="editar_perfiles" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="editaPerfilAjax">
        
    </div>
</div>
<!-- fin modal editar perfil -->

<!-- inicio modal cambiar contrasena -->
<div id="password_usuarios" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">CAMBIAR CONTRASE&Ntilde;A</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('User/password') }}" class="needs-validation" method="POST" novalidate>
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
        $("#botonGuardaNuevoUsuario").prop("disabled", false);
        $("#botonGuardaEdicionUsuario").prop("disabled", false);

        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Funcion para ocultar/mostrar y validar datos dependiendo del Tipo de perfil seleccionado
    $( function() {
        $("#ventana_almacen_existente").hide();
        $(".ventana_almacen_nuevo").hide();
        $("#perfil_usuario").val("");
        $("#perfil_usuario").change( function() {
            if($(this).val() == "") {                               // Si el select de Tipo de Perfil esta vacio (Seleccione), todo se oculta y sus valores se limpian
                $("#almacen_usuario").val("");
                $("#nombre_nuevo_almacen").val("");
                $("#telefonos_nuevo_almacen").val("");
                $("#direccion_nuevo_almacen").val("");

                $("#almacen_usuario").prop('required',false);
                $("#nombre_nuevo_almacen").prop('required',false);
                $("#direccion_nuevo_almacen").prop('required',false);
                
                $("#ventana_almacen_existente").hide();
                $(".ventana_almacen_nuevo").hide();
            }
            if ($(this).val() != "" && $(this).val() != "4") {      // Si el select de Tipo de Perfil es diferente de "" y de 4, mostrara select de un almacen existente
                $("#almacen_usuario").prop('required',true);
                $("#nombre_nuevo_almacen").prop('required',false);
                $("#direccion_nuevo_almacen").prop('required',false);
                $(".ventana_almacen_nuevo").hide();
                $("#ventana_almacen_existente").show();
                //$("#guarda_cupon").prop("disabled", false);
            }
            if ($(this).val() == "4") {                             // Si el select de Tipo de Perfil esta con 4 (Mayorista), mostrara los detalles para nuevo almacen
                $("#nombre_nuevo_almacen").prop('required',true);
                $("#direccion_nuevo_almacen").prop('required',true);
                $("#almacen_usuario").prop('required',false);
                $("#ventana_almacen_existente").hide();
                $(".ventana_almacen_nuevo").show();
            }
        });
    });

    // Funcion para mostrar modal de nuevo usuario
    function nuevo_usuario()
    {
        $("#modal_usuarios").modal('show');
    }

    // Funcion que comprueba que existen ciertos valores en el formulario y si estan muestra una alerta de exito
    function guardar_usuario()
    {
        var nombre_usuario = $("#nombre_usuario").val();
        var email_usuario = $("#email_usuario").val();
        var password_usuario = $("#password_usuario").val();
        var perfil_usuario = $("#perfil_usuario").val();
        
        var almacen_usuario = $("#almacen_usuario").val();

        var nombre_nuevo_almacen = $("#nombre_nuevo_almacen").val();
        var telefonos_nuevo_almacen = $("#telefonos_nuevo_almacen").val();
        var direccion_nuevo_almacen = $("#direccion_nuevo_almacen").val();

        if(nombre_usuario.length>0 && email_usuario.length>0 && password_usuario.length>7 && perfil_usuario.length>0){
            if(perfil_usuario != 4){
                if(almacen_usuario.length>0){
                    Swal.fire(
                        'Excelente!',
                        'Una nuevo usuario fue registrado.',
                        'success'
                    )
                }
            }else{
                if(nombre_nuevo_almacen.length>0 && direccion_nuevo_almacen.length>0){
                    Swal.fire(
                        'Excelente!',
                        'Una nuevo usuario fue registrado.',
                        'success'
                    )
                }
            }
        }
    }

    // Funcion para validar el email en Nuevo usuario
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
                    $("#botonGuardaNuevoUsuario").prop("disabled", true);
                }else{
                    $("#botonGuardaNuevoUsuario").prop("disabled", false);
                    $("#msgValidaEmail").hide();
                }
            }
        });
    }

    // Funcion para validar el email en Edicion de usuario
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
                    $("#botonGuardaEdicionUsuario").prop("disabled", true);
                }else{
                    $("#botonGuardaEdicionUsuario").prop("disabled", false);
                    $("#msgValidaEmailEdicion").hide();
                }
            }
        });
    }

    // Funcion que muestra los datos referentes a los permisos de un usuario
    function permisos(usuario_id, perfil_id)
    {
        $.ajax({
            url: "{{ url('User/ajaxEditaPerfil') }}",
            data: {
                usuario_id: usuario_id,
                perfil_id: perfil_id
                },
            type: 'get',
            success: function(data) {
                //$("#muestraCuponAjax").show('slow');
                $("#editaPerfilAjax").html(data);
                $("#editar_perfiles").modal('show');
            }
        });
    }

    // Funcion que despliega el modal de edicion de usuario, mandando todos sus datos al mismo
    function editar(id, nombre, ci, email, celular, nit, razon_social, perfil, almacen)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#ci").val(ci);
        $("#email").val(email);
        $("#celular").val(celular);
        $("#nit").val(nit);
        $("#razon_social").val(razon_social);
        $("#perfil").val(perfil);
        $("#almacen").val(almacen);
        $("#editar_usuarios").modal('show');
    }

    // Funcion que emite una alerta de exito en caso de encontrarse ciertos valores
    function actualizar_usuario()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var perfil = $("#perfil").val();
        var email = $("#email").val();
        var almacen = $("#almacen").val();
        if(nombre.length>0 && perfil.length>0 && email.length>0 && almacen.length>0){
            Swal.fire(
                'Excelente!',
                'Usuario actualizado correctamente.',
                'success'
            )
        }
    }

    // Al presionar el boton de actualizar perfil emite una alerta de exito
    function actualizar_perfil()
    {
        Swal.fire(
            'Excelente!',
            'Permisos de perfil actualizados correctamente.',
            'success'
        )
    }

    // Funcion que muestra un modal para cambio de contrasena
    function contrasena(id)
    {
        $("#id_password").val(id);
        $("#password_usuarios").modal('show');
    }

    // Funcion que emite una alerta de exito al presionar el boton de actualizar el password
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

    // Funcion que elimina un usuario
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
                    'El usuario fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('User/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
