@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
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

{{-- modal edita cliente --}}
<div id="modalEditaCliete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="warning-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title text-white" id="warning-header-modalLabel">EDITA CLIENTE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="ajaxFormEditaCliente">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- fin modal promo --}}

{{-- modal nuevo cliente --}}
<div id="success-header-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="warning-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title text-white" id="warning-header-modalLabel">NUEVO CLIENTE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="#" method="POST" id="formularioAjaxNuevoCliente">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_usuario" type="text" id="nombre_usuario" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="email_usuario" type="email" id="email_usuario" onchange="validaEmail()" class="form-control" required>
                                <small id="msgValidaEmail" class="badge badge-default badge-danger form-text text-white float-left" style="display: none;">El correo ya existe, el cliente ya esta registrado</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Contraseña</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="password_usuario" type="password" id="password_usuario" class="form-control" minlength="8" placeholder="Debe tener al menos 8 digitos" required>
                            </div>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Celular(es)</label>
                                <input name="celular_usuario" type="text" id="celular_usuario" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                    
                        <div class="col-md-12">
                            <label class="control-label">Categorias:&nbsp;&nbsp;</label>
                            @foreach ($grupos as $g)
                            <div class="form-check form-check-inline">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="grupos[]" class="custom-control-input" id="grupo_{{ $g->id }}"
                                        value="{{ $g->id }}">
                                    <label class="custom-control-label" for="grupo_{{ $g->id }}">{{ $g->nombre }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Razón Social</label>
                                <input name="razon_social_usuario" type="text" id="razon_social_usuario" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input name="nit_usuario" type="text" id="nit_usuario" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <a class="btn waves-effect waves-light text-white btn-block btn-success" onclick="guardaAjaxCLiente()" id="btnGuardaCliente" style="display: none;">GUARDAR CLIENTE</a>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- fin modal nuevo cliente --}}

<form action="{{ url('Venta/guardaVenta') }}" id="formularioVenta" method="POST">
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
                                <label class="control-label">
                                    CLIENTE 
                                    <small id="tag_nuevo_cliente" class="badge badge-default badge-success form-text text-white" onclick="nuevoCliente()">NUEVO</small>
                                    <small id="tag_edita_cliente" class="badge badge-default badge-info form-text text-white" onclick="editaCliente()" style="display: none;"><span id="tagCliente"></span></small>
                                </label>
                                <div id="ajaxComboClienteNuevo">
                                    <select name="cliente_id" id="cliente_id" class="select2 form-control custom-select"
                                        style="width: 100%; height:36px;" onchange="seleccionaCliente()">
                                        @foreach($clientes as $c)
                                        <option value="{{ $c->id }}" data-tipo="{{ $c->rol }}"> {{ $c->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1" style="display: none" id="bloqueEnvioMayorista">
                            <div class="form-group">
                                <label class="control-label">ENVIO</label>
                                <div class="input-group mb-3">
                                <input name="envioMayorista" id="envioMayorista" type="checkbox" data-toggle="toggle" data-on="SI" data-off="NO"
                                    data-onstyle="success" data-offstyle="danger" data-width="80">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control"
                                    value="{{ date("Y-m-d") }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">PRODUCTO (NOM/COD)</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="termino" name="termino">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="ti-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">PROMOCIONES</label>
                                <div class="input-group mb-3">
                                    <select name="promocione_id" id="promocione_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" >
                                        <option value=""> Selecione una </option>
                                        @foreach($arrayPromociones as $p)
                                            <option value="{{ $p['id'] }}" data-precio="{{ $p['total'] }}"> {{ $p['nombre'] }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <div class="input-group mb-3">
                                    <a onclick="muestraPromocionCombo()" class="btn btn-info text-white"><i class="fas fa-eye"></i> </a>
                                    &nbsp;
                                    <a onclick="adicionaPromocionCombo()" class="btn btn-success text-white"><i class="fas fa-plus"></i> </a>
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
        {{-- ventas la por unidad --}}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12" id="bloqueProductosUnidad" style="display: none;">
                    <div class="card border-info">
                        <div class="card-header bg-info">
                            <h4 class="mb-0 text-white">PRODUCTOS POR UNIDAD</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="tablaPedido" class="tablesaw table-striped table-hover table-bordered table no-wrap">
                                    <thead>
                                        <tr>
                                            <th>CODIGO</th>
                                            <th>NOMBRE</th>
                                            <th>MARCA</th>
                                            <th>Tipo</th>
                                            <th class="w-10 text-center text-info"><i class="fas fa-archive"></i></th>
                                            <th>CANTIDAD</th>
                                            <th class="w-10 text-center">PRECIO</th>
                                            <th class="w-10 text-center">IMPORTE</th>
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

            {{-- venta de promociones --}}
            <div class="row">
                <div class="col-md-12" id="bloquePromociones" style="display: none;">
                    <div class="card border-success">
                        <div class="card-header bg-success">
                            <h4 class="mb-0 text-white">PROMOCIONES</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="tablaPromos" class="tablesaw table-striped table-hover table-bordered table no-wrap">
                                    <thead>
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>PRECIO</th>
                                            <th>CANTIDAD</th>
                                            <th>IMPORTE</th>
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

            {{-- ventas al por mayor --}}
            <div class="row">
                <div class="col-md-12" id="bloqueProductosMayor" style="display: none;">
                    <div class="card border-danger">
                        <div class="card-header bg-danger">
                            <h4 class="mb-0 text-white">VENTAS AL POR MAYOR</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-40">
                                <table id="tablaPedidoMayor" class="tablesaw table-striped table-hover table-bordered table no-wrap">
                                    <thead>
                                        <tr>
                                            <th>CODIGO</th>
                                            <th>NOMBRE</th>
                                            <th>MARCA</th>
                                            <th class="text-center text-info"><i class="fas fa-archive"></i></th>
                                            <th class="w-10 text-center">UNIDAD</th>
                                            <th>CANTIDAD</th>
                                            <th class="w-10 text-center">PRECIO</th>
                                            <th class="w-10 text-center">IMPORTE</th>
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
                                    <td class="text-right">PAGO</td>
                                    <td colspan="2">
                                        <input name="pagoContado" type="checkbox" data-toggle="toggle" data-on="CONTADO" data-off="CREDITO" data-onstyle="success" data-offstyle="danger" data-width="120" checked onchange="cambiaASaldo()">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right">TOTAL</td>
                                    <td><input type="text" class="form-control text-right" name="totalCompra"
                                            id="resultadoSubTotales" style="width: 120px;" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">EFECTIVO</td>
                                    <td><input type="number" name="efectivo" id="efectivo"
                                            class="form-control text-right text-right" step="any" value="0" style="width: 120px;" min="0"></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><span id="saldoOCambio">CAMBIO</span></td>
                                    <td><input type="number" name="cambioVenta" id="cambioVenta"
                                            class="form-control text-right text-right" step="any" value="0"
                                            style="width: 120px;" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-right">NIT</td>
                                    <td><input type="number" name="nit_cliente" id="nit_cliente" class="form-control text-right text-right" step="any" style="width: 160px;" required></td>
                                </tr>
                                <tr>
                                    <td class="text-right">NOMBRE</td>
                                    <td><input type="text" name="razon_social_cliente" id="razon_social_cliente" class="form-control text-right text-right" style="width: 180px;" required></td>
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
                        <a class="btn waves-effect waves-light btn-block btn-success text-white" onclick="validaItems()">REGISTRAR VENTA</a>
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
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
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

    // tabla de promociones
    var tp = $('#tablaPromos').DataTable({
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
    var itemsPromosArray = [];

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

        // elimina productos de la tabla promociones
        $('#tablaPromos tbody').on('click', '.btnEliminaPromo', function () {
            tp.row($(this).parents('tr'))
                .remove()
                .draw();
            let itemBorrarPromo = $(this).closest("tr").find("td:eq(0)").text();
            let posPromo = itemsPromosArray.lastIndexOf(itemBorrarPromo);
            itemsPromosArray.splice(posPromo, 1);
            sumaSubTotales();
        });


        $(document).on('keyup change', '#efectivo', function () {
            let totalVenta = Number($("#resultadoSubTotales").val());
            let efectivo = Number($("#efectivo").val());
            let cambio = efectivo - totalVenta; 
            let numeroSinSigno = Math.abs(cambio);
            $("#cambioVenta").val(numeroSinSigno);
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
        let cantidad = Number($(this).val());
        let id = $(this).data("id");
        let precio = Number($("#precio_"+id).val());
        let subtotal = precio*cantidad;
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
        let cantidadMayor = Number($(this).val());
        let idm = $(this).data("idm");
        let precioMayor = Number($("#precio_m_"+idm).val());
        let subtotalMayor = precioMayor*cantidadMayor;
        $("#subtotal_m_"+idm).val(subtotalMayor);
        sumaSubTotales();
    });

    // calcula el precio en funcion a la unidad de la tabla promociones
    $(document).on('keyup change', '.cantidadPromocion', function(e){
        let cantidadPromocion = Number($(this).val());
        let idp = $(this).data("idp");
        let precioPromocion = Number($("#precioPromocion_"+idp).val());
        let subtotalPromocion = precioPromocion*cantidadPromocion;
        $("#subtotalPromocion_"+idp).val(subtotalPromocion);
        sumaSubTotales();
    });

    function sumaSubTotales()
    {
        let sum = 0;

        $('.subtotal, .subtotalMayor, .subtotalPromocion').each(function(){
            sum += parseFloat(this.value);
        });
        
        $("#resultadoSubTotales").val(sum);
        $("#efectivo").attr({"min": sum});
        valorLiteral = numeroALetras(sum, {
            plural: 'Bolivianos',
            singular: 'Bolivianos',
            centPlural: 'Centavos',
            centSingular: 'Centavo'
        });
        $("#montoLiteral").html(valorLiteral);
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
        var item = $("#item_" + item).closest("tr").find('td').text();
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
        // verificamos que la venta tengan productos
        if (itemsPedidoArray.length > 0 || itemsPedidoArrayMayor.length > 0 || itemsPromosArray.length > 0) {
            // verificamos que las cantidades sean las correctas si es asi enviamos el formulario
            if ($("#formularioVenta")[0].checkValidity()) {

                let datosFormularioVenta = $("#formularioVenta").serializeArray();

                $.ajax({
                    url: "{{ url('Venta/guardaVenta') }}",
                    data: datosFormularioVenta,
                    type: 'POST',
                    success: function(data) {
                        if (data.errorVenta == 0) {

                            Swal.fire({
                                type: 'success',
                                title: 'Excelente',
                                text: 'Se realizo la venta'
                            });

                            window.location.href = "{{ url('Venta/muestra') }}/"+data.ventaId;

                        } else {

                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'No tienes las cantidades suficientes.'
                            })

                            window.location.href = "{{ url('Venta/tienda') }}";
                            
                        }
                        // console.log(data);
                        // $("#ajaxMuestraTotalesAlmacenes").html(data);
                    }
                });

            }else{
                $("#formularioVenta")[0].reportValidity();
            }
        } else {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Tienes que adicionar un producto a la venta!!!'
            })
            // alert("llena carajo");
        }
    }

    function seleccionaCliente()
    {
        let nombreCliente = $("#cliente_id").find(':selected').text();
        let rolCliente = $("#cliente_id").find(':selected').data('tipo');
        if (rolCliente == 'Mayorista') {
            $("#bloqueEnvioMayorista").show("slow");
        }else{
            $("#bloqueEnvioMayorista").hide("slow");
        }
        console.log(rolCliente);
        $("#tagCliente").html('EDITA -'+nombreCliente);
        $("#tag_edita_cliente").show();
    }

    function nuevoCliente()
    {
        $("#nombre_usuario").focus();
        $("#nombre_usuario").val('');
        $("#email_usuario").val('');
        $("#password_usuario").val('');
        $("#celular_usuario").val('');
        $("#razon_social_usuario").val('');
        $("#nit_usuario").val('');
        $("#success-header-modal").modal("show");
    }

    function guardaAjaxCLiente()
    {
        let datosFormularioAjaxCliente = $("#formularioAjaxNuevoCliente").serializeArray();
        if ($("#formularioAjaxNuevoCliente")[0].checkValidity()) {
            $.ajax({
                url: "{{ url('Cliente/ajaxGuardaCliente') }}",
                data: datosFormularioAjaxCliente,
                type: 'POST',
                success: function (data) {
                    if (data.validaEmail == 1) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'El email ya existe!!!'
                        })
                    } else {

                        $("#ajaxComboClienteNuevo").load('{{ url("Cliente/ajaxComboClienteNuevo") }}/'+data.clienteId);
                        $("#success-header-modal").modal("hide");

                        Swal.fire({
                            type: 'success',
                            title: 'Excelente!',
                            text: 'Cliente registrado'
                        })
                        // console.log(data.clienteId);
                        // $("#cliente_id").val(data.clienteId);

                    }
                    // $("#ajaxMuestraTotalesAlmacenes").html(data);
                }
            });
        }else{
            $("#formularioAjaxNuevoCliente")[0].reportValidity();
        }
    }

    function validaEmail()
    {
        let correo_cliente = $("#email_usuario").val();
        $.ajax({
            url: "{{ url('Cliente/ajaxVerificaCorreo') }}",
            data: { correo: correo_cliente },
            type: 'POST',
            success: function(data) {
                if (data.valida == 1) {
                    $("#msgValidaEmail").show();
                    // $("#btnGuardaCliente").hide();
                }else{
                    $("#msgValidaEmail").hide();
                    $("#btnGuardaCliente").show();
                }
            }
        });
        // console.log($("#email_usuario").val());
    }

    function editaCliente()
    {
        let clienteId = $("#cliente_id").find(':selected').val();
        $.ajax({
            url: "{{ url('Cliente/ajaxEditaCliente') }}",
            data: { clienteId: clienteId },
            type: 'POST',
            success: function(data) {
                $("#ajaxFormEditaCliente").html(data);
            }
        });

        $("#modalEditaCliete").modal("show");

    }

    function guardaAjaxCLienteEdicion()
    {
        // capturamos lo datos del formulario
        let datosFormularioAjaxEditaCliente = $("#formularioAjaxEditaCliente").serializeArray();

        // verificamos que no existan errores en el formulario
        if ($("#formularioAjaxEditaCliente")[0].checkValidity()) {
            $.ajax({
                url: "{{ url('Cliente/guardaAjaxClienteEdicion') }}",
                data: datosFormularioAjaxEditaCliente,
                type: 'POST',
                success: function (data) {
                    if (data.msg != 1) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se puedo realizar la edicion'
                        })
                    } else {

                        $("#ajaxComboClienteNuevo").load('{{ url("Cliente/ajaxComboClienteNuevo") }}/'+data.clienteId);
                        // $("#success-header-modal").modal("hide");

                        Swal.fire({
                            type: 'success',
                            title: 'Excelente!',
                            text: 'Cliente registrado'
                        })
                        // console.log(data.clienteId);
                        // $("#cliente_id").val(data.clienteId);

                    }
                    $("#modalEditaCliete").modal("hide");
                    // $("#ajaxMuestraTotalesAlmacenes").html(data);
                }
            });
        }else{
            $("#formularioAjaxNuevoCliente")[0].reportValidity();
        }
    }

    function muestraPromocionCombo()
    {
        let promocionId = $("#promocione_id").val();
        if(promocionId == "")
        {
            alert("Selecciona una promocion")
        }else{
            muestraPromo(promocionId);
        }
    }

    function adicionaPromocionCombo()
    {
        let promocionId = $("#promocione_id").val();
        let nombre      = $("#promocione_id").find(':selected').text();
        let precio      = $("#promocione_id").find(':selected').data('precio');
        adicionaPromocion(promocionId, nombre, precio);
    }

    function adicionaPromocion(promocionId = null, nombre = null, precio = null)
    {
        // cerramos el modal de las promociones
        $("#danger-header-modal").modal("hide");

        // mostramos el bloque de la tabla promociones
        $("#bloquePromociones").show();

        // buscamos a la promocion en el array
        let buscaItemPromo = itemsPromosArray.lastIndexOf(promocionId);
        if (buscaItemPromo < 0)
        {
            if(promocionId != "")
            {
                // adicionamos la promo al array
                itemsPromosArray.push(promocionId);

                // adicionamos la fila a la tabla
                tp.row.add([
                    nombre + ' <small id="tags_promos" class="badge badge-default badge-danger form-text text-white" onclick="muestraPromo('+promocionId+')">VER</small>',
                    precio,
                    `<input type="number" class="form-control text-right cantidadPromocion" name="cantidadPromo[`+promocionId+`]" data-idp="`+promocionId+`" id="cantidadPromo[`+promocionId+`]" value="1" min="1" style="width: 100px;">
                    <input type="hidden" name="promoId[`+promocionId+`]" id="promoId_`+promocionId+`" value="`+promocionId+`">
                    <input type="hidden" name="precioPromocion[`+precio+`]" id="precioPromocion_`+promocionId+`" value="`+precio+`">`,
                    `<input type="number" class="form-control text-right subtotalPromocion" name="subtotalPromocion[`+promocionId+`]" id="subtotalPromocion_`+promocionId+`" value="`+precio+`" step="any" style="width: 120px;" readonly>`,
                    '<button type="button" class="btnEliminaPromo btn btn-danger" title="Elimina Promocion"><i class="fas fa-trash-alt"></i></button>'
                ]).draw(false);

                // calculamos el valor a totales
                sumaSubTotales();
            }else{
                alert("Selecciona una promocion")
            }
        }
    }

    function cambiaASaldo()
    {
        let texto = $('#saldoOCambio').text();
        let montoTotalVenta = $('#resultadoSubTotales').val();
        $("#saldoOCambio").text(
            texto == "CAMBIO" ? "SALDO" : "CAMBIO"
        );
        if (texto == "CAMBIO") {
            $("#efectivo").attr({"max": montoTotalVenta});
            $("#efectivo").removeAttr("min");
        }else{
            $("#efectivo").removeAttr("max");
            $("#efectivo").attr({"min": montoTotalVenta});
        }
    }


    $(document).on('focusout', '#nit_cliente', function(e) {

        let nitCliente = $('#nit_cliente').val();
        if(nitCliente == 0){
            document.getElementById('razon_social_cliente').value = "S/N";
        }else{
            $.ajax({
                url: "{{ url('Venta/ajaxBuscaNitCliente') }}",
                data: {nitCliente: nitCliente},
                type: 'POST',
                success: function(data) {
                    objetoCLiente = JSON.parse(data.datosCliente);
                    document.getElementById('razon_social_cliente').value = objetoCLiente.razon_social;
                }
            });
        }
    });



</script>
@endsection