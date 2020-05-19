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
            PEDIDOS 
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Almacen a Solicitar</th>
                        <th>Fecha</th>
                        <th>Numero</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $key => $pedido)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>
                                @foreach($almacenes as $almacen)
                                    @if($pedido->almacene_id == $almacen->id)
                                        {{ $almacen->nombre }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $pedido->fecha }}</td>
                            <td>{{ $pedido->numero }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar pedido"  onclick="editar('{{ $pedido->id }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar pedido"  onclick="eliminar('{{ $pedido->id }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

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
    function editar(id, nombre)
    {
        $("#id").val(id);
        window.location.href = "{{ url('Pedido/pedido_productos') }}/"+id;
    }

    function eliminar(id)
    {
        Swal.fire({
            title: 'Estas seguro de eliminar este pedido?',
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
                    'El pedido fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Pedido/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
