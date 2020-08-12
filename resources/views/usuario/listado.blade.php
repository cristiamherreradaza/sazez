@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
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
                        <th>Nombre</th>
                        <th>Correo Electronico</th>
                        <th>Celular</th>
                        <th>Perfil</th>
                        <th>Almacen</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $key => $usuario)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->celulares }}</td>
                            <td>{{ $usuario->perfil['nombre'] }}</td>
                            <td>
                                @if($usuario->almacen_id)
                                    {{ $usuario->almacen->nombre }}
                                @else
                                    {{ $usuario->almacen_id }}
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar usuario"  onclick="editar('{{ $usuario->id }}', '{{ $usuario->name }}', '{{ $usuario->email }}', '{{ $usuario->celulares }}', '{{ $usuario->nit }}', '{{ $usuario->razon_social }}', '{{ $usuario->perfil_id }}', '{{ $usuario->almacen_id }}')"><i class="fas fa-edit"></i></button>
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
                                <label class="control-label">Correo Electrónico</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Almacen</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="almacen_usuario" id="almacen_usuario" class="form-control" required>
                                    <option value="" selected> Seleccione </option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                    @endforeach
                                </select>
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
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_usuario()">GUARDAR USUARIO</button>
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
                                <label class="control-label">Correo Electrónico</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
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
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_usuario()">ACTUALIZAR USUARIO</button>
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
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

</script>
<script>
    function nuevo_usuario()
    {
        $("#modal_usuarios").modal('show');
    }

    function guardar_usuario()
    {
        var nombre_usuario = $("#nombre_usuario").val();
        var perfil_usuario = $("#perfil_usuario").val();
        var email_usuario = $("#email_usuario").val();
        var password_usuario = $("#password_usuario").val();
        var almacen_usuario = $("#almacen_usuario").val();

        if(nombre_usuario.length>0 && perfil_usuario.length>0 && almacen_usuario.length>0 &&email_usuario.length>0 && password_usuario.length>7){
            Swal.fire(
                'Excelente!',
                'Una nuevo usuario fue registrado.',
                'success'
            )
        }
    }

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

    function editar(id, nombre, email, celular, nit, razon_social, perfil, almacen)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#email").val(email);
        $("#celular").val(celular);
        $("#nit").val(nit);
        $("#razon_social").val(razon_social);
        $("#perfil").val(perfil);
        $("#almacen").val(almacen);
        $("#editar_usuarios").modal('show');
    }

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

    function actualizar_perfil()
    {
        Swal.fire(
            'Excelente!',
            'Permisos de perfil actualizados correctamente.',
            'success'
        )
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
