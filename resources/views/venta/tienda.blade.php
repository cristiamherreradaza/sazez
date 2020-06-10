@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')

{{-- modal promo --}}
<div id="danger-header-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="danger-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-danger">
                <h4 class="modal-title text-white" id="danger-header-modalLabel">DATOS DE LA PROMOCION</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="muestraAjaxPromo">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- fin modal promo --}}

{{-- modal promo --}}
<div id="warning-header-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="warning-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-warning">
                <h4 class="modal-title text-white" id="warning-header-modalLabel">EXISTENCIAS DEL PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="ajaxMuestraTotalesAlmacenes">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- fin modal promo --}}

<form action="{{ url('Venta/guardaVenta') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="mb-0 text-white">DATOS PARA LA VENTA</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>

                                <select name="cliente_id" id="cliente_id" class="select2 form-control custom-select"
                                    style="width: 100%; height:36px;">
                                    @foreach($clientes as $c)
                                    <option value="{{ $c->id }}"> {{ $c->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control"
                                    value="{{ date("Y-m-d") }}" required>
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
                            <div id="listadoProductosAjax"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-info">
                        <div class="card-header bg-info">
                            <h4 class="mb-0 text-white">PRODUCTOS POR UNIDAD</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="tablaPedido" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Marca</th>
                                            <th>Tipo</th>
                                            <th class="w-10 text-center text-info"><i class="fas fa-archive"></i></th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th class="w-10 text-center">Total</th>
                                            <th></th>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger">
                            <h4 class="mb-0 text-white">VENTAS AL POR MAYOR</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="tablaPedidoMayor" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Marca</th>
                                            <th class="text-center text-info"><i class="fas fa-archive"></i></th>
                                            <th>Unidad</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th class="w-10 text-center">Total</th>
                                            <th></th>
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
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <h4 class="mb-0 text-white">DETALLE</h4>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>TOTAL</td>
                                    <td><input type="text" class="form-control text-right" name="totalCompra"
                                            id="resultadoSubTotales" style="width: 120px;" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EFECTIVO</td>
                                    <td><input type="number" name="efectivo" id="efectivo"
                                            class="form-control text-right text-right" step="any" value="0"
                                            style="width: 120px;"></td>
                                </tr>
                                <tr>
                                    <td>CAMBIO</td>
                                    <td><input type="number" name="cambioVenta" id="cambioVenta"
                                            class="form-control text-right text-right" step="any" value="0"
                                            style="width: 120px;" readonly></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h6 class="text-info" id="montoLiteral"></h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <button class="btn waves-effect waves-light btn-block btn-success" onclick="validaItems()">REGISTRAR
                            VENTA</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/NumeroALetras.js') }}"></script>
<script>
    // tabla de pedidos por unidad
    var t = $('#tablaPedido').DataTable({
        paging: false,
        searching: false,
        ordering:  false,
        info: false,
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
    });

    // tabla de pedidos al por mayor
    var tm = $('#tablaPedidoMayor').DataTable({
        paging: false,
        searching: false,
        ordering:  false,
        info: false,
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
    });

    // array para controlar la cantidad de items en pedido unitario
    var itemsPedidoArray = [];
    var itemsPedidoArrayMayor = [];

    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {

        $(".select2").select2();

        // elimina productos de la tabla por unidad
        $('#tablaPedido tbody').on('click', '.btnElimina', function () {
            t.row($(this).parents('tr'))
                .remove()
                .draw();
            let itemBorrar = $(this).closest("tr").find("td:eq(0)").text();
            let pos = itemsPedidoArray.lastIndexOf(itemBorrar);
            itemsPedidoArray.splice(pos, 1);
            sumaSubTotales();
        });

        // elimina productos de la tabla por mayor
        $('#tablaPedidoMayor tbody').on('click', '.btnEliminaMayor', function () {
            tm.row($(this).parents('tr'))
                .remove()
                .draw();
            let itemBorrarMayor = $(this).closest("tr").find("td:eq(0)").text();
            let posMayor = itemsPedidoArrayMayor.lastIndexOf(itemBorrarMayor);
            itemsPedidoArrayMayor.splice(posMayor, 1);
            sumaSubTotales();
        });


        $(document).on('keyup change', '#efectivo', function () {
            // alert("cambio");
            let totalVenta = Number($("#resultadoSubTotales").val());
            // console.log(totalVenta);
            let efectivo = Number($("#efectivo").val());
            let cambio = efectivo - totalVenta; 
            if (cambio > 0) {
                $("#cambioVenta").val(cambio);
            }else{
                $("#cambioVenta").val(0);
            }
        });

    });

    // calcula el precio en funcion al cambio de precios tabla unidades
    $(document).on('keyup change', '.precio', function(e){
        let precio = Number($(this).val());
        let id = $(this).data("id");
        let cantidad = Number($("#cantidad_"+id).val());
        let subtotal = precio*cantidad;
        $("#subtotal_"+id).val(subtotal);
        sumaSubTotales();
    });

    // calcula el precio en funcion a la cantidad tabla unidades
    $(document).on('keyup change', '.cantidad', function(e){
        // alert("cambio");
        let cantidad = Number($(this).val());
        let id = $(this).data("id");
        let precio = Number($("#precio_"+id).val());
        let subtotal = precio*cantidad;
        console.log(precio);
        $("#subtotal_"+id).val(subtotal);
        sumaSubTotales();
    });

    // calcula el precio en funcion al cambio de precios tabla mayores
    $(document).on('keyup change', '.precioMayor', function(e){
        let precioMayor = Number($(this).val());
        let idm = $(this).data("idm");
        let cantidadMayor = Number($("#cantidad_m_"+idm).val());
        let subtotalMayor = precioMayor*cantidadMayor;
        $("#subtotal_m_"+idm).val(subtotalMayor);
        sumaSubTotales();
    });

    // calcula el precio en funcion al cambio de cantidad tabla mayores
    $(document).on('keyup change', '.cantidadMayor', function(e){
        // alert("cambio");
        let cantidadMayor = Number($(this).val());
        let idm = $(this).data("idm");
        // console.log(idm);
        let precioMayor = Number($("#precio_m_"+idm).val());
        let subtotalMayor = precioMayor*cantidadMayor;
        $("#subtotal_m_"+idm).val(subtotalMayor);
        sumaSubTotales();
    });

    function sumaSubTotales()
    {
        let sum = 0;

        $('.subtotal, .subtotalMayor').each(function(){
            sum += parseFloat(this.value);
        });
        // sumaVisible = sum.toLocaleString('en', {useGrouping:true});
        
        $("#resultadoSubTotales").val(sum);
        valorLiteral = numeroALetras(sum, {
            plural: 'Bolivianos',
            singular: 'Bolivianos',
            centPlural: 'Centavos',
            centSingular: 'Centavo'
        });
        $("#montoLiteral").html(valorLiteral);
        // console.log(valor);
    }

    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 3) {
            $.ajax({
                url: "{{ url('Venta/ajaxBuscaProductoTienda') }}",
                data: {termino: termino_busqueda},
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }

    });

    function adicionaPedido(item)
    {
        /*var item = $("#item_"+item).closest("tr").find('td').each(function(){
    console.log(this.text);
            });*/
        var item = $("#item_" + item).closest("tr").find('td').text();
        console.log(item);
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

    function muestraPromo(promoId)
    {
        // console.log(promoId);
        $.ajax({
            url: "{{ url('Combo/ajaxMuestraPromo') }}",
            data: {combo_id: promoId},
            type: 'POST',
            success: function(data) {
                // $("#listadoProductosAjax").show('slow');
                $("#muestraAjaxPromo").html(data);
            }
        });

        $("#danger-header-modal").modal("show");
    // alert(promoId);
    }

    function muestraExistencias(productoId)
    {
        $.ajax({
            url: "{{ url('Movimiento/ajaxMuestraTotalesAlmacen') }}",
            data: {producto_id: productoId},
            type: 'POST',
            success: function(data) {
                $("#ajaxMuestraTotalesAlmacenes").html(data);
            }
        });

        // $("#danger-header-modal").modal("show");

        $("#warning-header-modal").modal("show");
        //ajaxMuestraTotalesAlmacenes
    }

    function validaItems()
    {
        // alert("entro");
        if (itemsPedidoArray.length > 0) {
            alert("bien carajo");
        } else {
            alert("llena carajo");
        }
    }

</script>
@endsection