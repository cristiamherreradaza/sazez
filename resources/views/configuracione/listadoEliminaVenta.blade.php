@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            MOTIVOS ELIMINACION DE VENTA &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="modalConfiguracion()"><i class="fas fa-plus"></i> &nbsp; FORMULARIO MOTIVOS</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descripcion</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($configuraciones as $key => $c)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $c->valor }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar motivo"  onclick="editar('{{ $c->id }}', '{{ $c->valor }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar motivo"  onclick="eliminar('{{ $c->id }}', '{{ $c->valor }}')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- inicio modal motivos eliminacion venta -->
<div id="modal_tipos" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">FORMULARIO MOTIVOS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Configuracione/guardarEliminaVenta') }}" method="POST" id="formularioConfiguraciones">
                @csrf
                <div class="modal-body">        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input type="hidden" name="configuracionId" id="configuracionId" value="">
                                    <input name="motivo" type="text" id="motivo" class="form-control" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn waves-effect waves-light btn-block btn-success" onclick="guardaConfiguracion()">GUARDAR</a>
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
    function modalConfiguracion()
    {
        $("#configuracionId").val("");
        $("#motivo").val("");
        $("#modal_tipos").modal('show');
    }

    function guardaConfiguracion()
    {
        if ($("#formularioConfiguraciones")[0].checkValidity()) {
            $("#formularioConfiguraciones").submit();
            Swal.fire(
                'Excelente!',
                'Un nuevo motivo fue registrado.',
                'success'
            )
        }else{
            $("#formularioConfiguraciones")[0].reportValidity();
        }

    }

    function editar(id, nombre)
    {
        $("#configuracionId").val(id);
        $("#motivo").val(nombre);
        $("#modal_tipos").modal('show');
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
                window.location.href = "{{ url('Configuracione/eliminarEliminaVenta') }}/"+id;
                Swal.fire(
                    'Excelente!',
                    'El tipo fue eliminado',
                    'success'
                );
            }
        })
    }
</script>
@endsection