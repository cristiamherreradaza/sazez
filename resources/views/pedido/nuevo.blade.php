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
            PEDIDO NUEVO
        </h4>        
    </div>
    <div class="card-body">
        <form action="{{ url('Combo/guarda') }}" method="POST">
            @csrf
            <div class="row">         
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Numero</label>
                        <input type="text" name="numero_pedido" id="numero_pedido" class="form-control" readonly>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $pedido->fecha }}" readonly>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-success"  onclick="guardar_pedido()">CONFIRMAR</button>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn waves-effect waves-light btn-block btn-danger"  onclick="guardar_pedido()">DESCARTAR</button>
                    </div>                    
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline-primary">                                
                <div class="card-header">
                    <h4 class="mb-0 text-white">PRODUCTOS DISPONIBLES</h4>
                </div>
                <br />  
                <div class="table-responsive m-t-40">
                    <table id="tabla_productos" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Nombre de venta</th>
                                <th>Marca</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="productos_en_pedido">
            <!-- Contenido del datatable productos_combo -->
        </div>
    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
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

    // DataTable ajax de Productos
    var table = $('#tabla_productos').DataTable( {
        "iDisplayLength": 10,
        "processing": true,
        // "scrollX": true,
        "serverSide": true,
        "ajax": "{{ url('Combo/ajax_listado_producto') }}",
        "columns": [
            {data: 'nombre'},
            {data: 'nombre_venta'},
            {data: 'marca_id'},
            {data: 'action'},
        ],
    } );

    function guardar_pedido()
    {
        var numero_pedido = $("#numero_pedido").val();
        var fecha = $("#fecha").val();

        if(nombre_combo.length>0 && fecha_inicio.length>0 && fecha_final.length>0){
            Swal.fire(
                'Excelente!',
                'Generando lista de Productos.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar todos los campos.',
                'error'
            )
        }
    }
</script>
@endsection
