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
                        <th>Cantidad Minima</th>
                        <th>Cantidad Maxima</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($escalas as $key => $escala)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $escala->nombre }}</td>
                            <td>{{ $escala->minimo }}</td>
                            <td>{{ $escala->maximo }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar escala"  onclick="editar('{{ $escala->id }}', '{{ $escala->nombre }}')"><i class="fas fa-edit"></i></button>
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
            <form action="{{ url('Escala/guardar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input name="nombre_escala" type="text" id="nombre_escala" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad Minima</label>
                                <input name="minimo" type="number" id="minimo" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad Maxima</label>
                                <input name="maximo" type="number" id="maximo" class="form-control" min="1" required>
                            </div>
                        </div>
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
            <form action="{{ url('Escala/actualizar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input name="nombre" type="text" id="nombre" class="form-control" required>
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
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
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
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar el campo Nombre',
                'error'
            )
        }
        
    }

    function editar(id, nombre)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
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
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar el campo Nombre',
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
