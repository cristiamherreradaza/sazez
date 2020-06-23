@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            TIPOS &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_tipo()"><i class="fas fa-plus"></i> &nbsp; NUEVO TIPO</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipos as $key => $tipo)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $tipo->nombre }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar tipo"  onclick="editar('{{ $tipo->id }}', '{{ $tipo->nombre }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar tipo"  onclick="eliminar('{{ $tipo->id }}', '{{ $tipo->nombre }}')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal nuevo tipo -->
<div id="nuevo_tipo" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO TIPO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Tipo/guardar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_tipo" type="text" id="nombre_tipo" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_tipo()">GUARDAR TIPO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nuevo tipo -->

<!-- inicio modal editar tipo -->
<div id="editar_tipos" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR TIPO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Tipo/actualizar') }}"  method="POST" >
                @csrf
                <div class="modal-body">        
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="nombre" type="text" id="nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_tipo()">ACTUALIZAR TIPO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar tipo -->

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
    function nuevo_tipo()
    {
        $("#nuevo_tipo").modal('show');
    }

    function guardar_tipo()
    {
        var nombre_tipo = $("#nombre_tipo").val();
        if(nombre_tipo.length>0){
            Swal.fire(
                'Excelente!',
                'Un nuevo tipo fue registrado.',
                'success'
            )
        }
    }

    function editar(id, nombre)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#editar_tipos").modal('show');
    }

    function actualizar_tipo()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Tipo actualizado correctamente.',
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
                    'El tipo fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Tipo/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
