@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
            {{-- <div class="row">
                <div class="col-lg-12 col-md-6">
                    <div class="card">
                        <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form float-left dropzone">
                            <div class="card-body">
                                @csrf
                                <label for="input-file-disable-remove">Seleccione un archivo en formato EXCEL.</label>
                                <input type="file" name="select_file" id="select_file" class="dropify" data-show-remove="true" />
                                <input type="hidden" name="pedido_id" id="pedido_id">
                                <br>
                                <input type="submit" name="upload" id="upload" class="btn btn-rounded btn-success float-lg-right" value="Importar">
                            </div>
                        </form>

                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Seleccione un archivo en formato EXCEL.</h4>
                            <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">ARCHIVO</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="select_file" id="select_file" class="custom-file-input"  accept=".xlsx" required>
                                                    <input type="hidden" name="pedido_id" id="pedido_id">
                                                    <label class="custom-file-label" for="inputGroupFile01">Seleccione...</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 m-auto">
                                        <div class="form-group">
                                            <input type="submit" name="upload" id="upload" class="btn btn-rounded btn-success float-lg-right" value="Importar">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">

                                    <div class="col-md-10">
                                        <button type="submit" id="btnEnviaExcel" onclick="enviaExcel();"
                                            class="btn waves-effect waves-light btn-block btn-success">Importar archivo
                                            excel</button>
                                        <button class="btn btn-primary btn-block" type="button" id="btnTrabajandoExcel"
                                            disabled style="display: none;">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            &nbsp;&nbsp;Estamos trabajando, ten pasciencia ;-)
                                        </button>

                                    </div>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- fin modal editar almacen -->

<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <!-- Column -->

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">LISTA DE ENVIOS </h4>
                {{-- <div class="table-responsive m-t-40"> --}}
                <table id="tabla-usuarios" class="table table-bordered table-striped no-wrap">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Almacen Enviado</th>
                            <th>Personal </th>
                            <th>Fecha y Hora de Envio</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                {{-- </div> --}}
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
<script>
$(document).ready(function() {

    // DataTable
    var table = $('#tabla-usuarios').DataTable( {
        "iDisplayLength": 10,
        "processing": true,
        // "scrollX": true,
        "serverSide": true,
        "ajax": "{{ url('Envio/ajax_listados') }}",
        "columns": [
            {data: 'id', name: 'id'},
            {data: 'nombre', name: 'nombre'},
            {data: 'name', name: 'name'},
            {data: 'fecha', name: 'fecha'},
            {data: 'estado', name: 'estado'},
            {data: 'action'},
        ],
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
    } );

} );

</script>
<script>
    function entrega_excel(id)
    {
        $("#pedido_id").val(id);
        $("#entrega_excel").modal('show');
    }

    function ver_pedido(id)
    {
        window.location.href = "{{ url('Envio/ver_pedido') }}/"+id;
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
                    .then(function() {
                        window.location.href = "{{ url('Pedido/listado') }}";
                    });
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
@endsection