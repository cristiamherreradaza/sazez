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
    {{-- <div class="card-header">
        <h4 class="mb-0 text-white">
            PEDIDO NUEVO
        </h4>        
    </div> --}}
    <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">ENTREGA DE PRODUCTOS</h4>
                        
                        <form class="floating-labels mt-5">
                            <div class="row">
                                <div class="form-group has-success col-4 mb-5">
                                    <input type="text" class="form-control" id="input11" value="{{ $pedidos[0]->nombre }}" required>
                                    <label for="input11">Almacen</label>
                                </div>
                                <div class="form-group has-success col-4 mb-5">
                                    <input type="text" class="form-control" id="input11" value="{{ $pedidos[0]->solicitante_id }}" required>
                                    <label for="input11">Encargado del Almacen</label>
                                </div>
                                <div class="form-group has-success col-2 mb-5">
                                    <input type="text" class="form-control" id="input11" value="{{ $pedidos[0]->numero }}" required>
                                    <label for="input11">Numero de Pedido</label>
                                </div>
                                <div class="form-group has-success col-2 mb-5">
                                    <input type="date" class="form-control" id="input11" value="{{ $pedidos[0]->fecha }}" required>
                                    <label for="input11">Fecha de Pedido</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-primary">                                
                <div class="card-header">
                    <h4 class="mb-0 text-white">PRODUCTOS</h4>
                </div>
                <br /> 
                <form action="{{ url('Entrega/store') }}" method="POST">
                @csrf
                <input type="text" class="form-control" id="pedido_id" name="pedido_id" value="{{ $pedidos[0]->id }}" hidden>
                <input type="text" class="form-control" id="almacene_id" name="almacene_id" value="{{ $pedidos[0]->almacene_solicitante_id }}" hidden>
                <div class="table-responsive m-t-40">
                    <table id="config-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th>Colores</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $n = 1;
                            @endphp
                            @foreach ($productos as $prod)
                                <tr>
                                    <td>{{ $n++ }}</td>
                                    <td>{{ $prod->codigo }}</td>
                                    <td>{{ $prod->nombre }}</td>
                                    <td>{{ $prod->nombre_marca }}</td>
                                    <td>{{ $prod->nombre_tipo }}</td>
                                    <td>{{ $prod->modelo }}</td>
                                    <td>{{ $prod->colores }}</td>
                                    @php
                                         $total = DB::select("SELECT (SUM(ingreso) - SUM(salida))as total
                                                                FROM movimientos
                                                                WHERE producto_id = '$prod->producto_id'
                                                                AND almacene_id = 1
                                                                GROUP BY producto_id");
                                         $cantidad_disponible = $total[0]->total;
                                    @endphp
                                    @php
                                        if ($prod->cantidad <= $cantidad_disponible) {
                                    @endphp
                                    <td><input type="text" class="form-control col-sm-2" style="text-align: center; color: green;" onchange="calcula( {{ $prod->id }} )" id="cantidad_{{ $prod->id }}" name="cantidad_{{ $prod->id }}" data-disponible="{{ $cantidad_disponible }}" value="{{ $prod->cantidad }}"> &nbsp;&nbsp;({{ $cantidad_disponible }})</td>
                                    @php
                                        } else {
                                    @endphp
                                    <td><input type="text" class="form-control col-sm-2" style="text-align: center; color: red;" onchange="calcula( {{ $prod->id }} )" id="cantidad_{{ $prod->id }}" name="cantidad_{{ $prod->id }}" data-disponible="{{ $cantidad_disponible }}" value="{{ $prod->cantidad }}"> &nbsp;&nbsp;({{ $cantidad_disponible }})</td>
                                    @php
                                        }
                                    @endphp
                                </tr>
                            @endforeach
                            
                        </tbody>

                    </table>
                    <div class="modal-footer">
                            <button type="submit" onclick="enviar()" class="btn waves-effect waves-light btn-block btn-success">ENTREGAR PRODUCTOS</button>
                    </div>
                </div>
                </form> 
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
    $(function () {
        $('#config-table').DataTable({
            responsive: true,
            "order": [
                [0, 'asc']
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });
</script>
<script>
    function calcula(id)
    {
        //e.preventDefault();     // Evita que la página se recargue
        //tenemos que enviar el id de la nota que se esta modificando y los valores insertados, ó que se encuentran en ese momento en los campos
        var identificador = id;
        var asistencia = $("#cantidad_"+id).val();
        var nombre = $("#cantidad_"+id).data("disponible");
        // alert(nombre);
    }

    function enviar()
    {
        //e.preventDefault();     // Evita que la página se recargue
        //tenemos que enviar el id de la nota que se esta modificando y los valores insertados, ó que se encuentran en ese momento en los campos
        var identificador = id;
        var asistencia = $("#cantidad_"+id).val();
        var nombre = $("#cantidad_"+id).data("disponible");
        // alert(nombre);
    }
    
</script>

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
