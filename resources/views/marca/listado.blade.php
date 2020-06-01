@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            MARCAS &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nueva_marca()"><i class="fas fa-plus"></i> &nbsp; NUEVA MARCA</button>
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
                    @foreach($marcas as $key => $marca)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $marca->nombre }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar marca"  onclick="editar('{{ $marca->id }}', '{{ $marca->nombre }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar marca"  onclick="eliminar('{{ $marca->id }}', '{{ $marca->nombre }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal marca nueva -->
<div id="modal_marcas" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVA MARCA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Marca/guardar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_marca" type="text" id="nombre_marca" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guarda_marca()">GUARDAR MARCA</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal marca nueva -->

<!-- inicio modal editar marca -->
<div id="editar_marcas" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR MARCA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Marca/actualizar') }}" method="POST">
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
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualiza_marca()">ACTUALIZAR MARCA</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar marca -->

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
    function nueva_marca()
    {
        $("#modal_marcas").modal('show');
    }

    function guarda_marca()
    {
        var nombre_marca = $("#nombre_marca").val();
        if(nombre_marca.length>0){
            Swal.fire(
                'Excelente!',
                'Una nueva marca fue registrada.',
                'success'
            )
        }
    }

    function editar(id, nombre)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#editar_marcas").modal('show');
    }

    function actualiza_marca()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Marca actualizada correctamente.',
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
                    'La marca fue eliminada',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Marca/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
