@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endsection

@section('content')
<div class="card card-outline-info">
    <div class="card-header">
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
                <div class="modal-body">
                    <form action="{{ url('Marca/guardar') }}" method="POST">
                        @csrf
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <input name="nombre_marca" type="text" id="nombre_marca" class="form-control" required>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="guarda_marca()">GUARDAR MARCA</button>
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
                <div class="modal-body">
                    <form action="{{ url('Marca/actualizar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre</label>
                                    <input name="nombre" type="text" id="nombre" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="actualiza_marca()">ACTUALIZAR MARCA</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar marca -->

@stop

@section('js')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable();
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
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function nueva_marca()
    {
        $("#marca_id").val("");
        $("#nombre_marca").val("");
        $("#modal_marcas").modal('show');
    }

    function guarda_marca()
    {
        var nombre_marca = $("#nombre_marca").val();
        $.ajax({
            url: "{{ url('Marca/guardar') }}",
            method: "POST",
            data: {
                nombre_marca : nombre_marca
            },
            cache: false,
            success: function(data)
            {
                Swal.fire(
                    'Excelente!',
                    'Una nueva marca fue registrada.',
                    'success'
                ).then(function() {
                    //$("#modal_marcas").modal('hide');
                    location.reload();
                    //location.reload("#lista");
                    //$("#lista").load("#lista");
                });
            }
        });
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
        //alert(id);
        $.ajax({
            url: "{{ url('Marca/actualizar') }}",
            method: "POST",
            data: {
                id : id,
                nombre : nombre
            },
            cache: false,
            success: function(data)
            {
                Swal.fire(
                    'Excelente!',
                    'Marca actualizada correctamente.',
                    'success'
                ).then(function() {
                    //$("#editar_marcas").modal('hide');
                    location.reload();
                    //location.reload("#lista");
                    //$("#lista").load("#lista");
                });
            }
        });
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
                $.ajax({
                    url: "{{ url('Marca/eliminar') }}",
                    method: "POST",
                    data: {
                        id : id
                    },
                    cache: false,
                    success: function (data) {
                        Swal.fire(
                            'Excelente!',
                            'La marca fue eliminada',
                            'success'
                        ).then(function() {
                            location.reload();
                        });
                    }
                });
            }
        })
    }
</script>
@endsection
