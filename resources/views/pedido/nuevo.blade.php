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
        <form action="{{ url('Pedido/guarda') }}" method="POST">
            @csrf
            <div class="row">
            <input type="hidden" name="id_pedido" id="id_pedido" value="{{ $pedido->id }}">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Numero</label>
                        <input type="text" name="numero_pedido" id="numero_pedido" class="form-control" value="{{ $pedido->numero }}" readonly>
                    </div>                    
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Fecha</label>
                        <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control" value="{{ $pedido->fecha }}" readonly>
                    </div>                    
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Almacen a solicitar</label>
                        <select name="almacen_a_pedir" id="almacen_a_pedir" class="form-control">
                            @foreach($almacenes as $almacen)
                                <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-success"  onclick="guardar_pedido()">CONFIRMAR</button>
                    </div>                    
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn waves-effect waves-light btn-block btn-danger"  onclick="eliminar_pedido()">DESCARTAR</button>
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
        "ajax": "{{ url('Pedido/ajax_listado_producto') }}",
        "columns": [
            {data: 'nombre'},
            {data: 'nombre_venta'},
            {data: 'marca_id'},
            {data: 'action'},
        ],
    } );

    function guardar_pedido()
    {
        var id_pedido = $("#id_pedido").val();
        var numero_pedido = $("#numero_pedido").val();
        var fecha = $("#fecha_pedido").val();
        var almacen_a_pedir = $("#almacen_a_pedir").val();

        if(numero_pedido.length>0 && fecha.length>0){
            Swal.fire(
                'Excelente!',
                'Su pedido fue realizado correctamente.',
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

    function eliminar_pedido()
    {
        var id = $("#id_pedido").val();
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
                    'El Pedido fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Pedido/eliminar') }}/"+id;
                });
            }
        })
    }

    function adicionar_producto_pedido(producto_id)
    {
        var pedido_id = $("#id_pedido").val();
        var producto_id = producto_id;
        $.ajax({
            url: "{{ url('Pedido/agregar_pedido_producto') }}",
            method: "POST",
            data: {
                pedido_id : pedido_id,
                producto_id : producto_id
            },
            cache: false,
            success: function(data)
            {
                $("#productos_en_pedido").load("{{ url('Pedido/lista_pedido_productos') }}/"+pedido_id);
            }
        })
    }

    function eliminar_pedido_producto(pedido_id, producto_id)
    {
        Swal.fire({
            title: 'Quieres retirar el producto del pedido?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, estoy seguro!',
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type:'GET',
                    url:"{{ url('Pedido/elimina_producto') }}/"+pedido_id+"/"+producto_id,
                    success:function(data){
                        $("#productos_en_pedido").load("{{ url('Pedido/lista_pedido_productos') }}/"+pedido_id);        
                        Swal.fire(
                            'Excelente!',
                            'El producto fue retirado del pedido',
                            'success'
                        );
                    }
                });
            }
        })
    }

    $(document).ready(function(){
        var id = $("#id_pedido").val();
        $("#productos_en_pedido").load("{{ url('Pedido/lista_pedido_productos') }}/"+id);
    }); 

    function checkCampos(numero) {
        if(numero.length <= 0){
            return 0;
        }else{
            return numero;
        }
    }

    function calcula(id)
    {
        var identificador = id;
        var cantidad = $("#cantidad-"+id).val();
        cantidad = checkCampos(cantidad);
        
        $.ajax({
            type:'POST',
            url:"{{ url('Pedido/actualiza_cantidad') }}",
            data: {
                id : identificador,
                cantidad : cantidad
            }
        });
    }
</script>
@endsection
