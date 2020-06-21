@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">
@endsection

@section('content')
<!-- inicio modal editar almacen -->
<div id="entrega_excel" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Importar Envio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-6">
                    <div class="card">
                        <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form float-left">
                            <div class="card-body">
                                @csrf
                                
                                {{-- <button type="submit" class="btn btn-rounded btn-success float-lg-right">Importar</button> --}}
                                {{-- <h4 class="card-title">Importar Envio</h4> --}}
                                <label for="input-file-disable-remove">Seleccione un archivo en formato EXCEL.</label>
                                <input type="file" name="select_file" id="select_file" class="dropify" data-show-remove="true" />
                                <input type="hidden" name="pedido_id" id="pedido_id">
                                <br>
                                <input type="submit" name="upload" id="upload" class="btn btn-rounded btn-success float-lg-right" value="Importar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fin modal editar almacen -->

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
                                <button type="button" class="btn btn-danger" title="Eliminar pedido"  onclick="eliminar('{{ $pedido->id }}')"><i class="fas fa-trash-alt"></i></button>
                                <button type="button" class="btn btn-dark" title="Entregar pedido"  onclick="entrega('{{ $pedido->id }}')"><i class="fas fa-reply"></i></button>
                                <button type="button" class="btn btn-success" title="Bajar pedido en Excel"  onclick="excel('{{ $pedido->id }}')"><i class="fas fa-file-excel"></i></button>
                                <button type="button" class="btn btn-secondary" title="Entregar pedido por Excel"  onclick="entrega_excel('{{ $pedido->id }}')"><i class="fas fa-shipping-fast"></i></button>

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
<script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweet-alert.init.js') }}"></script>
<script>
    function entrega_excel(id)
    {
        $("#pedido_id").val(id);
        // $("#nombre").val(nombre);
        // $("#telefonos").val(telefonos);
        // $("#direccion").val(direccion);
        $("#entrega_excel").modal('show');
    }
</script>
<script>
// Script de importacion de excel
$(document).ready(function() {
    $('.upload_form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ url('Entrega/importar_envio') }}",
            method: "POST",
            data: new FormData(this), pedido_id,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data)
            {
                if(data.sw == 1){
                    Swal.fire(
                    'Hecho',
                    data.message,
                    'success'
                    )
                    //.then(function() {
                    //     location.reload();
                    //     $('#select_file').val('');
                    // });
                }else{
                    Swal.fire(
                    'Oops...',
                    data.message,
                    'error'
                    )
                }
            }
        })
    });
});
</script>
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
    function entrega(id)
    {
        window.location.href = "{{ url('Entrega/entrega') }}/"+id;
    }
    function excel(id)
    {
        window.location.href = "{{ url('Entrega/excel') }}/"+id;
    }
</script>
<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour reemplazar',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>

@endsection
