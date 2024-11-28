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

<!-- inicio modal imagen producto -->
<div id="imagen_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">IMAGEN PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body" id="muestraImagenProducto">

            </div>
        </div>
    </div>
</div>
<!-- fin modal imagen producto -->

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
                                <small id="msgValidaEmail" class="badge badge-default badge-danger form-text text-white float-left" style="display: none;">Ingrese un correo o el correo ya existe, el cliente ya esta registrado</small>
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">
                                    CLIENTE
                                    <small id="tag_nuevo_cliente" class="badge badge-default badge-success form-text text-white" onclick="nuevoCliente()">NUEVO</small>
                                    <small id="tag_edita_cliente" class="badge badge-default badge-info form-text text-white" onclick="editaCliente()" style="display: none;"><span id="tagCliente"></span></small>
                                </label>
                                <div id="ajaxComboClienteNuevo">
                                    <select name="cliente_id" id="cliente_id" class="select2 form-control custom-select"
                                        style="width: 100%; height:36px;" onchange="seleccionaCliente()">
                                        {{-- <option value="2" data-tipo="Cliente" data-nit="" data-razon="" data-select2-id="2"> Publico General </option> --}}
                                        @foreach($clientes as $c)
                                            <option value="{{ $c->id }}" data-tipo="{{ $c->rol }}" data-nit="{{ $c->nit }}" data-razon="{{ $c->razon_social }}"> {{ $c->nit }} - {{ $c->razon_social }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1" style="display: none" id="bloqueEnvioMayorista">
                            <div class="form-group">
                                <label class="control-label">ENVIO</label>
                                <div class="input-group mb-3">
                                    <select name="envioMayorista" id="envioMayorista" class="form-control">
                                        <option value="No"> No </option>
                                        <option value="Si"> Si </option>
                                    </select>
                                {{-- <input name="envioMayorista" id="envioMayorista" type="checkbox" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" data-width="80"> --}}
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
                                <label class="control-label">TIPO</label>
                                <div class="input-group mb-3">
                                    <select name="venta_tipo_id" id="venta_tipo_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" onchange="filtraTipo();">
                                        <option value=""> Selecione una </option>
                                        @foreach($tipos as $t)
                                            <option value="{{ $t['id'] }}"> {{ $t['nombre'] }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label class="control-label">MARCA</label>
                                <div class="input-group mb-3">
                                    <select name="venta_marca_id" id="venta_marca_id" class="select2 form-control custom-select" style="width: 100%; height:36px;" onchange="filtraMarca();">
                                        <option value=""> Selecione una </option>
                                        @foreach($marcas as $m)
                                            <option value="{{ $m['id'] }}"> {{ $m['nombre'] }} </option>
                                        @endforeach
                                    </select>
                                </div>
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

                        <div class="col-md-2">
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
                                            <th></th>
                                            <th>CODIGO</th>
                                            <th>NOMBRE</th>
                                            <th>MARCA</th>
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
                                            <th></th>
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
                                            id="resultadoSubTotales" style="width: 100%;" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">GIFTCARD</td>
                                    <td>
                                        <input name="chkbGiftcard" id="chkbGiftcard" type="checkbox" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" data-width="120" onchange="csGiftcard(this)">
                                    </td>
                                </tr>
                                <tr id="areaGiftcard" style="display: none;">
                                    <td class="text-right" style="display: ;">CODIGO</td>
                                    <td>
                                        <select name="codigo_gc" id="codigo_gc" class="select2 form-control custom-select"
                                            style="width: 150px; height:36px; border-color: slateblue"  onchange="calculaTotalEfectivoMenosMontoGC()" placeholder="Seleccione 1">
                                            <option value="" placeholder="Seleccione 1">seellll</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr style="width: 100%; font-size: 18px;font-weight: bold">
                                    <td class="text-right">TOTAL_PAGAR</td>
                                    <td><input type="text" class="form-control text-right" name="totalCompra"
                                            id="resultadoTotales" style="width: 100%;" readonly>
                                    </td>
                                </tr>
                                <tr style="width: 100%; font-size: 22px;font-weight: bold">
                                    <td class="text-right">EFECTIVO</td>
                                    <td><input type="number" name="efectivo" id="efectivo" style="width: 100%; font-size: 22px;font-weight: bold; border-color: #299e3d"
                                            class="form-control text-right text-right" step="any" value="0"  min="0"></td>
                                </tr>
                                <tr>
                                    <td class="text-right"><span id="saldoOCambio">CAMBIO</span></td>
                                    <td><input type="number" name="cambioVenta" id="cambioVenta"
                                            class="form-control text-right text-right" step="any" value="0"
                                            style="width: 100%;" readonly></td>
                                </tr>

                                @php
                                    $parametrosFactura = App\Parametros::where('almacene_id', auth()->user()->almacen_id)
                                                            ->latest()
                                                            ->first();
                                @endphp
                                @if ($parametrosFactura != null && $parametrosFactura->estado == 'Activo')
                                    <tr>
                                        <td class="text-right">FACTURA</td>
                                        <td>
                                            <input name="factura" id="factura" type="checkbox" disabled data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" data-width="120" onchange="csfactura(this)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">NIT/CI(*)</td>
                                        <td><input type="number" name="nit_cliente" id="nit_cliente" class="form-control text-right text-right" step="any" style="width: 100%; border-color: slateblue" value="0" required disabled></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">NOMBRE(*)</td>
                                        <td><input type="text" name="razon_social_cliente" id="razon_social_cliente" class="form-control text-left" style="width: 100%; border-color: slateblue" required></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">NRO.CEL.</td>
                                        <td><input type="text" name="celulares_cliente" id="celulares_cliente" class="form-control text-right" style="width: 100%; border-color: slateblue"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">CORREO</td>
                                        <td><input type="email" name="email_cliente" id="email_cliente" class="form-control text-left" style="width: 100%; border-color: slateblue"></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="2">
                                        <h6 class="text-info" id="montoLiteral"></h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <a class="btn waves-effect waves-light btn-block btn-success text-white" onclick="validaItems()" id="btnEnviaVenta">REGISTRAR VENTA</a>
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

        //ASignacion de cliente por defecto cuando usuario es Admin o Almacen
        let = clientePorDefecto = "";
        if("{{ auth()->user()->rol }}" == "Administrador" || "{{ auth()->user()->rol }}" == "Almacen"){
            clientePorDefecto = "Roger Gonzalo Sanchez Alvarez";
        }
        selectCliente(clientePorDefecto);

        mostrarOcultaCamposSegunTotalVenta();

        selectCodigoGC();

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
            //let totalVenta = Number($("#resultadoSubTotales").val());
            let totalVenta = Number($("#resultadoTotales").val()); //@walvarez
            let efectivo = Number($("#efectivo").val());
            let cambio = efectivo - totalVenta;
            let numeroSinSigno = Math.abs(cambio);
            $("#cambioVenta").val(numeroSinSigno);
        });

    });


    function selectCliente(txtBusqueda = '') {

        let clienteIdSelect2 = $("#cliente_id").select2({
            placeholder: "Seleccione Cliente...",
            allowClear: true,
            minimumInputLength: 3,
            width: '100%',
            dropdownAutoWidth : true,
            ajax: {
                url: "{{ url('Venta/ajaxBuscaCliente') }}",
                dataType: 'json',
                type: 'GET',
                quietMillis: 200,
                data: function (term, page) {
                    return {
                        term: term, //buscar por "term"
                        page: page // numeor de pagina
                    };
                },
                results: function (data, page) {
                    //objetoCLiente = JSON.parse(data.datosCliente);
                    var more = (page * 10) < data.total;
                    return {results: data.datosCliente, more: more};
                },

                processResults: function (data) {
                    return {
                        results: $.map(data.datosCliente, function (item, index) {
                            let title = 'ROL: ' + item.rol + '\n' +
                                        'NOMBRE: ' + item.nombre + '\n' +
                                        'RAZON SOCIAL: ' + item.razon_social + '\n' +
                                        'NIT: ' + item.nit + '\n' +
                                        'CELULAR: ' + item.celulares + '\n' +
                                        'CORREO: ' + item.email;
                            return {
                                text: (index+1) + '. [NIT: ' + item.nit+'] <b>'+item.nombre + '</b> ('+ item.rol + ')',
                                text_result: '[NIT: ' + item.nit+'] '+item.nombre + ' ('+ item.rol + ')',
                                title: title,
                                id: item.id,
                                tipo: item.rol,
                                nit: item.nit,
                                razon_social: item.razon_social,
                                celulares: item.celulares,
                                email: item.email,
                            }
                        })
                    };
                },
                cache: false

            },
            formatResult: function (data, term) {
                return data;
            },
            formatSelection: function (data) {
                return data;
            },
            templateSelection: function (data, container) {
                $(data.element).attr('data-tipo', data.tipo);
                $(data.element).attr('data-nit', data.nit);
                $(data.element).attr('data-text_result', data.text_result);
                $(data.element).attr('title', data.title);
                $(data.element).attr('data-razon', data.razon_social);
                $(data.element).attr('data-celular', data.celulares);
                $(data.element).attr('data-email', data.email);
                return data.text_result;
            },
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });

        // Seleccionar automaticamente cliente cuando usuario es Admin o almacen
        if(txtBusqueda != ''){

            clienteIdSelect2.on("select2:open", function (e) {

                document.querySelector('.select2-search__field').focus();

                $('.select2-search__field').val(txtBusqueda);
                $('.select2-search__field').trigger("keyup");
                $('.select2-results__option').trigger("select");

                setTimeout(function() { $('.select2-results__option').trigger("mouseup"); }, 1000);

            });

            clienteIdSelect2.on('focus', function(){
                $(this).select2('open');
            });

            $('#cliente_id').trigger("focus");
            txtBusqueda = '';
        }
        // FIN - Seleccionar automaticamente cliente cuando usuario es Admin o almacen
    }

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

        mostrarOcultaCamposSegunTotalVenta(sum);

        $("#resultadoSubTotales").val(sum);


        // calculo con Giftcard
        sumEfectivo = calculaTotalEfectivoMenosMontoGC(sum);

        $("#resultadoTotales").val(sumEfectivo);

        $("#efectivo").val(sumEfectivo);
        $("#efectivo").attr({"min": sumEfectivo});
        montoALiteral(sumEfectivo);
    }

    function montoALiteral(sum){
        valorLiteral = numeroALetras(sum, {
            plural: 'Bolivianos',
            singular: 'Bolivianos',
            centPlural: 'Centavos',
            centSingular: 'Centavo'
        });
        $("#montoLiteral").html(valorLiteral);
    }

    $(document).on('keyup', '#termino', function(e) {
        let termino_busqueda = $('#termino').val();
        let tipo = $('#venta_tipo_id').val();
        let marca = $('#venta_marca_id').val();

        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('Venta/ajaxBuscaProductoTienda') }}",
                data: {termino: termino_busqueda, tipo: tipo, marca: marca},
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

                Swal.fire({
                    title: '¿Confirma el registro de la venta?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, realizar la venta!',
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.value) {

                        let datosFormularioVenta = $("#formularioVenta").serializeArray();
                        $("#btnEnviaVenta").hide();
                        console.log(datosFormularioVenta);
                        $.ajax({
                            url: "{{ url('Venta/guardaVenta') }}",
                            data: datosFormularioVenta,
                            type: 'POST',
                            success: function(data) {
                                if (data.errorVenta == 0 || data.errorVenta == '0') {

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Excelente',
                                        text: 'Se realizo la venta.'
                                    }).then((result) => {
                                        // Redirecciona a la ventana de opciones de la Venta
                                        window.location.href = "{{ url('Venta/muestra') }}/"+data.ventaId;
                                    })

                                } else if(data.errorVenta == 1 || data.errorVenta == '1') {

                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'No tienes las cantidades suficientes.'
                                    }).then((result) => {
                                        // Redirecciona a la ventana de ventas
                                        window.location.href = "{{ url('Venta/tienda') }}";
                                    })

                                } else {

                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: data.mensajeError
                                    }).then((result) => {
                                        $("#btnEnviaVenta").show();
                                    })
                                }
                            }
                        });
                    }
                })

            }else{
                $("#formularioVenta")[0].reportValidity();
            }
        } else {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Tienes que adicionar un producto a la venta!!!'
            })
        }
    }

    function seleccionaCliente()
    {
        let nombreCliente = $("#cliente_id").find(':selected').text();
        let nombreClienteResult = $("#cliente_id").find(':selected').data('text_result');
        let rolCliente    = $("#cliente_id").find(':selected').data('tipo');
        let nitCliente    = $("#cliente_id").find(':selected').data('nit');
        let razonCliente  = $("#cliente_id").find(':selected').data('razon');
        let celular  = $("#cliente_id").find(':selected').data('celular');
        let email  = $("#cliente_id").find(':selected').data('email');

        if (rolCliente == 'Mayorista') {
            $("#bloqueEnvioMayorista").show("slow");
        }else{
            $("#bloqueEnvioMayorista").hide("slow");
        }

        if(nombreCliente){
            // Muestra Boton de editar Cliente
            $("#tagCliente").html('EDITA -'+nombreClienteResult);
            $("#tag_edita_cliente").show();

            //Asignando resuiltados en campos de factura
            $("#nit_cliente").val(nitCliente);
            $("#razon_social_cliente").val(razonCliente);
            $("#celulares_cliente").val(celular);
            $("#email_cliente").val(email);

        }else{
            $("#tag_edita_cliente").hide();
        }
    }

    function nuevoCliente()
    {
        $("#ajaxFormEditaCliente").html("");
        $("#nombre_usuario").focus();
        $("#nombre_usuario").val('');
        $("#email_usuario").val('');
        $("#password_usuario").val('');
        $("#celular_usuario").val('');
        $("#razon_social_usuario").val('');
        $("#nit_usuario").val('');
        $("#msgValidaEmail").hide();
        $("#btnGuardaCliente").hide();
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
        if(correo_cliente == ""){
            $("#msgValidaEmail").show();
            $("#btnGuardaCliente").hide();

        }
        $("#msgValidaEmail").hide();
        $("#btnGuardaCliente").hide();
        $.ajax({
            url: "{{ url('Cliente/ajaxVerificaCorreo') }}",
            data: { correo: correo_cliente },
            type: 'POST',
            success: function(data) {
                if (data.valida == 1) {
                    $("#msgValidaEmail").show();
                    $("#btnGuardaCliente").hide();
                }else{
                    $("#msgValidaEmail").hide();
                    $("#btnGuardaCliente").show();
                }
            }
        });
        // console.log($("#email_usuario").val());
    }

    // ajax edita los datos del cliente
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
    // ajax guarda los datos del cliete
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
                        let razonSocialCliente = $("#razon_social_usuario").val();
                        $("#razon_social_cliente").val(razonSocialCliente);

                        Swal.fire({
                            type: 'success',
                            title: 'Excelente!',
                            text: 'Cliente registrado'
                        })
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
        //let montoTotalVenta = $('#resultadoSubTotales').val();
        let montoTotalVenta = $('#resultadoTotales').val(); //@walvarez
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

    //
    function csfactura(elem)
    {
        let isCheckedFactura = $('#'+elem.id).is(":checked");

        if(!isCheckedFactura){
            //$('#nit_cliente').attr({"readonly": true});
            $('#nit_cliente').attr("disabled", "disabled");
            $('#nit_cliente').val('0');
            $("#razon_social_cliente").val('');
            $("#celulares_cliente").val('');
            $("#email_cliente").val('');
        }else{
            //$('#nit_cliente').attr({"readonly": false});
            $('#nit_cliente').removeAttr("disabled");
        }
    }

    // Busqueda de datos de cliente en la base de datos
    $(document).on('focusout', '#nit_cliente', function(e) {

        let nitCliente = $('#nit_cliente').val();
        if(nitCliente == 0 || nitCliente == ''){
            document.getElementById('nit_cliente').value = "0";
            document.getElementById('razon_social_cliente').value = "S/N";
            document.getElementById('celulares_cliente').value = "0";
            document.getElementById('email_cliente').value = "cliente@notiene.com";
        }else{
            $.ajax({
                url: "{{ url('Venta/ajaxBuscaNitCliente') }}",
                data: {nitCliente: nitCliente},
                type: 'POST',
                success: function(data) {
                    objetoCLiente = JSON.parse(data.datosCliente);
                    console.log(objetoCLiente);
                    if(objetoCLiente.length === 0){
                        // console.log('es vacio');
                        document.getElementById('razon_social_cliente').value = "";
                        document.getElementById('celulares_cliente').value = "";
                        document.getElementById('email_cliente').value = "";
                    }else{
                        document.getElementById('razon_social_cliente').value = objetoCLiente.razon_social;
                        document.getElementById('celulares_cliente').value = objetoCLiente.celulares;
                        document.getElementById('email_cliente').value = objetoCLiente.email;
                    }
                }
            });
        }
    });

    function muestraImagenProducto(nombre){
        let imagen = `<img src="{{ asset('imagenesProductos')}}/`+nombre+`" width="100%">`;
        console.log(imagen);
        $("#muestraImagenProducto").html(imagen);
        $("#imagen_producto").modal("show");
    }

    function filtraTipo(){

        let termino_busqueda = $('#termino').val();
        let tipo = $("#venta_tipo_id").val();
        let marca = $("#venta_marca_id").val();

        $.ajax({
            url: "{{ url('Venta/ajaxBuscaProductoTienda') }}",
            data: {termino: termino_busqueda, tipo: tipo, marca: marca},
            type: 'POST',
            success: function(data) {
                $("#listadoProductosAjax").show('slow');
                $("#listadoProductosAjax").html(data);
            }
        });
    }

    function filtraMarca(){

        let termino_busqueda = $('#termino').val();
        let tipo = $("#venta_tipo_id").val();
        let marca = $("#venta_marca_id").val();

        $.ajax({
            url: "{{ url('Venta/ajaxBuscaProductoTienda') }}",
            data: {termino: termino_busqueda, tipo: tipo, marca: marca},
            type: 'POST',
            success: function(data) {
                $("#listadoProductosAjax").show('slow');
                $("#listadoProductosAjax").html(data);
            }
        });
    }

    function mostrarOcultaCamposSegunTotalVenta(totalVenta = '0'){
        //let totalVenta = Number($("#resultadoSubTotales").val());


        console.log(totalVenta);

        if(totalVenta == '0'){
            $("#efectivo").val(0);
            $("#efectivo").attr({"readonly": true});
            //$("#btnEnviaVenta").attr("style", "pointer-events: none");

            $("#btnEnviaVenta").addClass("disabled");

            //$("#btnEnviaVenta").hide();
            //$('#btnEnviaVenta').attr('disabled', 'disabled');
            //$('#btnEnviaVenta').removeAttr('href');

            //$("#factura").addClass("disabled");

            $("#factura").attr("disabled", 'disabled');
            $("#factura").parent('div').addClass("disabled");

            $("#chkbGiftcard").attr("disabled", 'disabled');
            $("#chkbGiftcard").parent('div').addClass("disabled");


        }else{
            $("#efectivo").attr({"readonly": false});
            $("#btnEnviaVenta").removeClass("disabled");
            //$("#btnEnviaVenta").attr({"display": block});
            //$("#btnEnviaVenta").show();
            //$('#btnEnviaVenta').removeAttr('style');

            //$("#factura").removeClass("disabled");

            $("#factura").parent('div').removeClass("disabled");
            $("#factura").removeAttr("disabled");

            $("#chkbGiftcard").parent('div').removeClass("disabled");
            $("#chkbGiftcard").removeAttr("disabled");
        }

    }

     // Busqueda de giftcard de cliente en la base de datos
    //  $(document).on('keyup', '#codigo_gcss', function(e) {

    //     let nitCliente = $('#codigo_gc').val();
    //     if(nitCliente == 0 || nitCliente == ''){
    //         document.getElementById('nit_cliente').value = "0";
    //         document.getElementById('razon_social_cliente').value = "S/N";
    //         document.getElementById('celulares_cliente').value = "0";
    //         document.getElementById('email_cliente').value = "cliente@notiene.com";
    //     }else{
    //         $.ajax({
    //             url: "{{ url('Venta/ajaxBuscaNitCliente') }}",
    //             data: {nitCliente: nitCliente},
    //             type: 'POST',
    //             success: function(data) {
    //                 objetoCLiente = JSON.parse(data.datosCliente);
    //                 console.log(objetoCLiente);
    //                 if(objetoCLiente.length === 0){
    //                     // console.log('es vacio');
    //                     document.getElementById('razon_social_cliente').value = "";
    //                     document.getElementById('celulares_cliente').value = "";
    //                     document.getElementById('email_cliente').value = "";
    //                 }else{
    //                     document.getElementById('razon_social_cliente').value = objetoCLiente.razon_social;
    //                     document.getElementById('celulares_cliente').value = objetoCLiente.celulares;
    //                     document.getElementById('email_cliente').value = objetoCLiente.email;
    //                 }
    //             }
    //         });
    //     }
    //     });



    // Mostrar/ocultar Entrada codigo GC
    function csGiftcard(elem){

        let isCheckedGiftcard = $('#'+elem.id).is(":checked");

        //$("#codigo_gc").find(':selected').data('monto_gc')



        if(isCheckedGiftcard){
            $('#areaGiftcard').show();

            // $("#codigo_gc").attr("required","required");
            // $("#codigo_gc").addClass("required");
            // $("#select2-codigo_gc-container").addClass("required");


        }else{
            $('#areaGiftcard').hide();

            // $("#codigo_gc").removeAttr("required");
            // $("#codigo_gc").removeClass("required");
            // $("#select2-codigo_gc-container").removeClass("required");

            $("#codigo_gc").empty().trigger('change');
        }
    }

    function selectCodigoGC() {

        let clienteIdSelect2 = $("#codigo_gc").select2({
            placeholder: "Seleccione...",
            allowClear: true,
            minimumInputLength: 2,
            //width: '50%',
            dropdownAutoWidth : false,
            ajax: {
                url: "{{ url('Venta/ajaxBuscaGiftcard') }}",
                dataType: 'json',
                type: 'GET',
                quietMillis: 200,
                data: function (term, page) {
                    return {
                        term: term, //buscar por "term"
                        page: page // numeor de pagina
                    };
                },
                results: function (data, page) {
                    //objetoCLiente = JSON.parse(data.datosCliente);
                    var more = (page * 10) < data.total;
                    return {results: data.datosCliente, more: more};
                },

                processResults: function (data) {
                    return {
                        results: $.map(data.datosCliente, function (item, index) {
                            let title = '[CODIGO GC]: ' + item.serial + '\n' +
                                        '[MONTO BS]: ' + item.monto_GC;
                            return {
                                text: (index+1) + '. [CODIGO GC] ' + item.serial+' <b>[MONTO BS] '+item.monto_GC + '</b>',
                                //text_result: '<b>Bs. '+item.monto_GC + '</b>' +' [CODIGO: ' + item.serial+']',
                                text_result: '<b>Bs. '+item.monto_GC + '</b>',
                                title: title,
                                venta_id: item.venta_id,
                                serial: item.serial,
                                id: item.id,
                                monto_gc: item.monto_GC,
                            }
                        })
                    };
                },
                cache: false

            },
            formatResult: function (data, term) {
                return data;
            },
            formatSelection: function (data) {
                return data;
            },
            templateSelection: function (data, container) {
                $(data.element).attr('data-venta_id', data.venta_id);
                $(data.element).attr('data-serial', data.serial);
                $(data.element).attr('data-text_result', data.text_result);
                $(data.element).attr('title', data.title);
                $(data.element).attr('data-monto_gc', data.monto_gc);
                return data.text_result;
            },
            dropdownCssClass: "bigdrop",
            escapeMarkup: function (m) { return m; }
        });

    }

    function calculaTotalEfectivoMenosMontoGC(sumaTotalImporteProductos = '0'){

        console.log("sumaTotalImporteProductos: "  + sumaTotalImporteProductos);

        let resultadoSubTotales;
        if(sumaTotalImporteProductos === '0'){
            resultadoSubTotales = Number($("#resultadoSubTotales").val());
        }else{
            resultadoSubTotales = sumaTotalImporteProductos;
        }

        let efectivo = Number($("#efectivo").val());
        let monto_gc = Number($("#codigo_gc").find(':selected').data('monto_gc'));
        monto_gc = (monto_gc)? monto_gc : 0;

        let totalAPagarEfectivo = resultadoSubTotales;

        console.log("resultadoSubTotales: " + resultadoSubTotales);
        console.log("efectivo: " + efectivo);
        console.log("monto_gc: " + monto_gc);

        if(monto_gc != 0 ){

            totalAPagarEfectivo = resultadoSubTotales - monto_gc;


            if(totalAPagarEfectivo < 0){

                Swal.fire({
                    type: 'warning',
                    title: 'Monto GiftCard Exedido!',
                    text: 'El monto del giftcard exede al total de los productos, ingrese otra Tarjeta'
                }).then((result) => {
                    totalAPagarEfectivo = resultadoSubTotales;
                    //csGiftcard("chkbGiftcard");
                    $("#codigo_gc").empty().trigger('change');
                    $("#chkbGiftcard").parent('div').removeClass('btn-success');
                    $("#chkbGiftcard").parent('div').addClass('btn-danger');
                    $("#chkbGiftcard").parent('div').addClass('off');
                    $('#areaGiftcard').hide();

                })

           }
        }


        console.log("totalAPagarEfectivo: " + totalAPagarEfectivo);
        $("#resultadoTotales").val(totalAPagarEfectivo);
        $("#efectivo").val(totalAPagarEfectivo);
        $("#efectivo").attr({"min": totalAPagarEfectivo});

        // CAMBIO
        let efectivoTotal = Number($("#efectivo").val());
        let cambio = efectivoTotal - totalAPagarEfectivo;
        let numeroSinSigno = Math.abs(cambio);
        $("#cambioVenta").val(numeroSinSigno);
        montoALiteral(totalAPagarEfectivo);

        return totalAPagarEfectivo;

        // let nombreClienteResult = $("#cliente_id").find(':selected').data('text_result');
        // let rolCliente    = $("#cliente_id").find(':selected').data('tipo');
        // let nitCliente    = $("#cliente_id").find(':selected').data('nit');
        // let razonCliente  = $("#cliente_id").find(':selected').data('razon');
        // let celular  = $("#cliente_id").find(':selected').data('celular');
        // let email  = $("#cliente_id").find(':selected').data('email');


        // let totalVenta = Number($("#resultadoSubTotales").val());
        // let efectivo = Number($("#efectivo").val());
        // let cambio = efectivo - totalVenta;
        // let numeroSinSigno = Math.abs(cambio);
        // $("#cambioVenta").val(numeroSinSigno);




    }



</script>
@endsection
