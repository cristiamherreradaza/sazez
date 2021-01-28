@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            META &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nueva_meta()"><i class="fas fa-plus"></i> &nbsp; NUEVA META</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="row">
            <div class="col-md-4"><h3><span class="text-info"> Usuario:</span> {{ $datosUsuario->name }}</h3></div>
            <div class="col-md-4"><h3><span class="text-info"> Tienda:</span> {{ $datosUsuario->almacen->nombre }}</h3></div>
            <div class="col-md-4"><h3><span class="text-info"> Email:</span> {{ $datosUsuario->email }}</h3></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%; height:" aria-valuenow="25"
                        aria-valuemin="0" aria-valuemax="100">25%</div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Meta</th>
                        <th>Alcanzado</th>
                        <th>Porcentaje</th>
                        <th>Mes</th>
                        <th>Gestion</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metasUsuario as $key => $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>{{ $m->meta }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ $m->mes }}</td>
                            <td>{{ $m->gestion }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar meta" onclick="editar('{{ $m->id }}', '{{ $m->meta }}', '{{ $m->mes }}', '{{ $m->gestion }}' )"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar meta" onclick="eliminar('{{ $m->id }}', '{{ $m->meta }}', '{{ $m->mes }}', '{{ $m->gestion }}')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal marca nueva -->
<div id="modal_meta" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVA META</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('User/guardaMeta') }}" method="POST" id="formularioMeta">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ $datosUsuario->id }}">
                <input type="hidden" name="almacen_id" id="almacen_id" value="{{ $datosUsuario->almacen_id }}">
                <input type="hidden" name="meta_id" id="meta_id" value="">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Mes</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <select name="mes" id="mes" class="form-control">
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Meta</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="meta" type="number" id="meta" class="form-control" min="1" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Gestion</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="gestion" type="number" id="gestion" class="form-control" value="{{ date('Y') }}" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guarda_marca()">GUARDA META</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal marca nueva -->


@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            "order": [[ 0, "desc" ]],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

</script>
<script>
    function nueva_meta()
    {
        $("#meta").val("");
        $("#meta_id").val("");
        $("#mes").val("Enero");
        $("#modal_meta").modal('show');
    }

    function guarda_marca()
    {
        // var nombre_marca = $("#nombre_marca").val();
        if ($("#formularioMeta")[0].checkValidity()) {
            $("#formularioMeta").submit();
            Swal.fire(
                'Excelente!',
                'Una nueva marca fue registrada.',
                'success'
            )
        }else{
            $("#formularioMeta")[0].reportValidity();
        }
    }

    function editar(id, meta, mes, gestion)
    {
        $("#meta_id").val(id);
        $("#meta").val(meta);
        $("#mes").val(mes);
        $("#gestion").val(gestion);
        $("#modal_meta").modal('show');
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

    function eliminar(id, meta, mes, gestion)
    {
        Swal.fire({
            title: 'Quieres borrar la meta de ' + meta + ' ' + mes + ', ' +gestion+ '?',
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
                    window.location.href = "{{ url('User/eliminaMeta') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
