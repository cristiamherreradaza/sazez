@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" />
@endsection

@section('content')

<div class="card card-outline-info">
    <form action="{{ url('Pedido/guarda') }}" method="POST">
        @csrf
    
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-info">                                
                <div class="card-header">
                    <h4 class="mb-0 text-white">NUEVA VENTA</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <input type="hidden" name="cotizacione_id" id="cotizacione_id" value="">
                                <select class="select2 form-control" style="width: 100%" name="cliente_id" id="cliente_id">
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}"> {{ $c->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha</label>
                                <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control" value="{{ date("Y-m-d") }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Buscar Producto</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="termino" name="termino">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="listadoProductosAjax" style="display: none;">
                                <div class="table-responsive">
                                    <table class="table table-striped no-wrap" id="tablaProductosEncontrados">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Codigo</th>
                                                <th>Nombre</th>
                                                <th>Marca</th>
                                                <th>Tipo</th>
                                                <th>Modelo</th>
                                                <th>Colores</th>
                                                <th class="text-nowrap">Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline-warning">
                <div class="card-header">
                    <h4 class="mb-0 text-white">PRODUCTOS PARA VENTA</h4>
                </div>
                <div class="card-body">
                    <div id="ajaxProductosCotizacion">

                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

@stop

@section('js')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweet-alert.init.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>

<script>
    var t = $('#tablaPedido').DataTable();
    var tpe = $('#tablaProductosEncontrados').DataTable({
        "searching": false,
        "info": false,
        "paging": false
    });
    var itemsPedidoArray = [];
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function adicionaItemCotizacion(item_id)
    {
        let cotizacione_id = $("#cotizacione_id").val();
        let cliente_id = $("#cliente_id").val();
        $("#listadoProductosAjax").hide('slow');
        $("#termino").val("");
        $("#termino").focus();
        $.ajax({
            url: "{{ url('Venta/adicionaItem') }}",
            data: {
                cliente_id: cliente_id,
                producto_id: item_id,
                cotizacion_id: cotizacione_id
            },
            type: 'POST',
            success: function(data) {
                
                // console.log(JSON.parse(data));
                // $("#listadoProductosAjax").show('slow');
                // $("#cotizacione_id").val();
                $("#ajaxProductosCotizacion").html(data);
            }
        });

        // console.log(item_id);
    }

    $(document).ready(function () {
        $(".select2").select2();
        $('#tablaPedido tbody').on('click', '.btnElimina', function () {
            t.row($(this).parents('tr'))
                .remove()
                .draw();
            let itemBorrar = $(this).closest("tr").find("td:eq(0)").text();
            let pos = itemsPedidoArray.lastIndexOf(itemBorrar);
            itemsPedidoArray.splice(pos, 1);
        });
    });


    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('Venta/ajaxBuscaProducto') }}",
                data: {termino: termino_busqueda},
                type: 'POST',
                success: function(data) {
                    let pedido_id = data.pedido_id;
                    // console.log(pedido_id);
                    // let objetosProductos = JSON.parse(data.arrayProductos);
                    tpe.clear().draw();
                    for(item in data.arrayProductos){
                        producto_id = data.arrayProductos[item].id;
                        tpe.row.add( [
                            data.arrayProductos[item].id,
                            data.arrayProductos[item].codigo,
                            data.arrayProductos[item].nombre,
                            data.arrayProductos[item].marca,
                            data.arrayProductos[item].tipo,
                            data.arrayProductos[item].modelo,
                            data.arrayProductos[item].colores,
                            `<button type="button" class="btnSelecciona btn btn-success" title="Adiciona Item"
                                onclick="adicionaItemCotizacion('`+producto_id+`')"><i class="fas fa-plus"></i></button>`,
                         ] ).draw();
                    }
                    $("#listadoProductosAjax").show('slow');
                }
            });
            $("#listadoProductosAjax").show('slow');
        }

    });

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

</script>
@endsection