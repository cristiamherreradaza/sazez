@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            PERFILES &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_perfil()"><i class="fas fa-plus"></i> &nbsp; NUEVO PERFIL</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perfiles as $key => $perfil)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $perfil->nombre }}</td>
                            <td>{{ $perfil->descripcion }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar perfil"  onclick="editar('{{ $perfil->id }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar perfil"  onclick="eliminar('{{ $perfil->id }}', '{{ $perfil->nombre }}')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal nuevo perfil -->
<div id="nuevo_perfil" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO PERFIL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Perfil/guardar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_perfil" type="text" id="nombre_perfil" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <input name="descripcion_perfil" type="text" id="descripcion_perfil" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Menus</label>
                                @foreach($menus as $key => $menu)
                                    <div class="col-sm-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cajas" value="{{ $menu->id }}" id="customCheck{{$key}}" name="menus[]">
                                            <label for="customCheck{{$key}}" class="custom-control-label">{{ $menu->nombre }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_perfil()">GUARDAR PERFIL</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nuevo perfil -->

<!-- inicio modal editar perfil -->
<div id="editar_perfiles" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="editaPerfilAjax">
        
    </div>
</div>
<!-- fin modal editar perfil -->

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
    function nuevo_perfil()
    {
        $("#nuevo_perfil").modal('show');
    }

    function guardar_perfil()
    {
        var nombre_categoria = $("#nombre_categoria").val();
        if(nombre_categoria.length>0){
            Swal.fire(
                'Excelente!',
                'Una nuevo perfil fue registrado.',
                'success'
            )
        }
    }

    function editar(id)
    {
        $.ajax({
            url: "{{ url('Perfil/ajaxEditaPerfil') }}",
            data: {
                id: id
                },
            type: 'get',
            success: function(data) {
                //$("#muestraCuponAjax").show('slow');
                $("#editaPerfilAjax").html(data);
                $("#editar_perfiles").modal('show');
            }
        });
    }

    function actualizar_perfil()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Perfil actualizado correctamente.',
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
                    'El perfil fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Perfil/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
