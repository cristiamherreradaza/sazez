@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            ESCALAS &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nueva_marca()"><i class="fas fa-plus"></i> &nbsp; NUEVA ESCALA</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        {{-- <th>Cantidad Maxima</th> --}}
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($escalas as $key => $escala)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $escala->nombre }}</td>
                            <td>{{ $escala->minimo }}</td>
                            {{-- <td>{{ $escala->maximo }}</td> --}}
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar escala"  onclick="editar('{{ $escala->id }}', '{{ $escala->nombre }}', '{{ $escala->minimo }}', '{{ $escala->maximo }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar escala"  onclick="eliminar('{{ $escala->id }}', '{{ $escala->nombre }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal nueva escala -->
<div id="nueva_escala" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVA ESCALA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Escala/guardar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_escala" type="text" id="nombre_escala" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="minimo" type="number" id="minimo" class="form-control" min="1" required>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad Maxima</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="maximo" type="number" id="maximo" class="form-control" min="1" required>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar_escala()">GUARDAR ESCALA</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nueva escala -->

<!-- inicio modal editar escala -->
<div id="editar_escalas" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR ESCALA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Escala/actualizar') }}"  method="POST" >
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad Minima</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="minimo_escala" type="number" id="minimo_escala" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad Maxima</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="maximo_escala" type="number" id="maximo_escala" class="form-control" min="1" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_escala()">ACTUALIZAR ESCALA</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar escala -->

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
        $("#nueva_escala").modal('show');
    }

    function guardar_escala()
    {
        var nombre_escala = $("#nombre_escala").val();
        if(nombre_escala.length>0){
            Swal.fire(
                'Excelente!',
                'Una nueva escala fue registrada.',
                'success'
            )
        }
    }

    function editar(id, nombre, minimo, maximo)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#minimo_escala").val(minimo);
        $("#maximo_escala").val(maximo);
        $("#editar_escalas").modal('show');
    }

    function actualizar_escala()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Escala actualizada correctamente.',
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
                    'La escala fue eliminada',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Escala/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
