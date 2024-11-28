@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')

{{-- modal elimina venta --}}
<div id="modalElimina" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="danger-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-danger">
                <h4 class="modal-title text-white" id="danger-header-modalLabel">ELIMINAR VENTA</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h4 class="card-title">Seleccione el motivo</h4>
                <form class="mt-3" action="{{ url('Venta/elimina') }}" method="POST" id="formularioEliminaVenta">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" value="{{ $datosVenta->id }}" name="ventaId" id="ventaId">
                        <select name="opcion_elimina" id="opcion_elimina" class="form-control" onchange="cambiaOpcionEliminaVenta()" required>
                            <option value="">Seleccione una opcion</option>
                            @foreach ($opcionesEliminaVenta as $oev)
                            <option value="{{ $oev->valor }}">{{ $oev->valor }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="form-group">
                        <textarea class="form-control" rows="3" maxlength=500 id="comentario_elimina" name="comentario_elimina" required></textarea>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="enviaDatosEliminar()">ELIMINAR VENTA</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-inverse" onclick="cancelaElimnacion()">CANCELAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- fin modal elimina venta --}}

{{-- modal cambia producto --}}
<div id="modalCambiaProducto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="info-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title text-white" id="danger-header-modalLabel">CAMBIA PRODUCTO </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form class="mt-3" action="" method="POST" id="formularioCambiaProducto">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" value="{{ $datosVenta->id }}" name="ventaModificaId" id="ventaModificaId">
                                <input type="hidden" value="" name="productoModificaId" id="productoModificaId">
                                <input type="hidden" value="" name="cantidadCambio" id="cantidadCambio">
                                <input type="hidden" value="" name="ventaProductoCambia" id="ventaProductoCambia">
                                <label>PRODUCTO </label>
                                <input type="text" class="form-control" name="nombre_producto_cambio" id="nombre_producto_cambio" readonly>
                                <small id="tagDiasGarantia" class="badge badge-default badge-primary form-text text-white float-left"></small>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>COMP</label>
                                <input type="text" class="form-control" name="cantidad_producto" id="cantidad_producto" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>A CAMBIAR</label>
                                <input type="number" class="form-control" name="cantidad_producto_a_cambiar" min="1" id="cantidad_producto_a_cambiar">
                                <small id="nombrePaqueteCambio" class="badge badge-default badge-info form-text text-white float-left"></small>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>MOTIVO</label>
                                <select name="opcion_elimina" id="opcion_cambia" class="form-control" required>
                                    <option value="">Seleccione una opcion</option>
                                    @foreach ($opcionesCambiaProductoVenta as $ocv)
                                    <option value="{{ $ocv->valor }}">{{ $ocv->valor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>

                    {{-- <div class="form-group">
                        <textarea class="form-control" rows="3" maxlength=500 id="comentario_elimina" name="comentario_elimina" required></textarea>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-success"
                                onclick="enviaDatosCambia()" id="btnCambiaProducto">CAMBIAR PRODUCTO</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-inverse"
                                onclick="cancelaCambio()">CANCELAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- info modal cambia CambiaProductoo --}}
@php  $utilidades = new App\librerias\Utilidades();  @endphp
<div class="card card-body">
    <div class="invoice-123" id="printableArea">
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4"><h2><span class="text-info">Venta #</span> {{ $datosVenta->id }}</h2></div>
                    <div class="col-md-4"><h2><span class="text-info">Cliente:</span> {{ $datosVenta->cliente != null? $datosVenta->cliente->razon_social: "" }}</h2></div>
                    <div class="col-md-4"><h2><span class="text-info">Fecha: </span> {{ $utilidades->formatoFecha($datosVenta->fecha, "d-m-Y") }}</h2></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive mt-5" style="clear: both;">
                    <table class="tablesaw table-striped table-hover table-bordered table no-wrap">
                        <thead  class="table-info">
                                <th class="text-center">#</th>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>MARCA</th>
                                <th>TIPO</th>
                                <th class="text-center">GARANTIA</th>
                                <th class="text-right">CANTIDAD</th>
                                <th class="text-right">PRECIO</th>
                                <th class="text-right">IMPORTE</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumaSubTotal = 0;
                            @endphp
                            @foreach ($productosVenta as $con => $pv)
                                <tr>
                                    <td class="text-center">{{ ++$con }}</td>
                                    <td>{{ $pv->producto->codigo }}</td>
                                    <td>{{ $pv->producto->nombre }}</td>
                                    <td>{{ $pv->producto->marca->nombre }}</td>
                                    <td>{{ $pv->producto->tipo->nombre }}</td>
                                    @php
                                        $fechaGarantia = Illuminate\Support\Carbon::createFromDate($pv->fecha_garantia);
                                        $fechaHoy = Illuminate\Support\Carbon::now();
                                        $diferenciaDias = $fechaGarantia->diffInDays($fechaHoy);
                                        $fechaHoyMayorFechaGarantia = $fechaHoy->isBefore($fechaGarantia);
                                       // echo $pv->fecha_garantia. " | " . $fechaHoy;
                                        //echo "<br>";
                                        //echo $fechaHoyMayorFechaGarantia ." | ". $diferenciaDias ." | ". $pv->producto->dias_garantia;
                                        if( ($diferenciaDias < $pv->producto->dias_garantia) && $fechaHoyMayorFechaGarantia)
                                        {
                                            $classText = "text-success";
                                            $mensajeGarantia = $diferenciaDias ." dias vigente";

                                        }else{
                                            $classText = "text-danger";
                                            $mensajeGarantia = "Expirada";
                                        }
                                    @endphp
                                    <td class="text-primary text-center"><b>{{ $utilidades->formatoFecha($pv->fecha_garantia, "d-m-Y") }}
                                        <br><span class="{{ $classText }}">({{ $mensajeGarantia }})</span></b></td>
                                    <td class="text-right">
                                        <span class="text-info"><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></span>
                                        <span class="text-success"><b>{{ ($pv->combo_id != null)?$pv->combo->nombre:"" }}</b></span>
                                        &nbsp;&nbsp;&nbsp; <b>{{ intval($pv->cantidad) }}</td>
                                    <td class="text-right">
                                        {{ ($pv->precio_cobrado_mayor>0)?$pv->precio_cobrado_mayor:$pv->precio_cobrado }}
                                    </td>
                                    @php
                                        if ($pv->precio_cobrado_mayor>0) {
                                            $precio_costo = $pv->precio_cobrado_mayor;
                                        }else{
                                            $precio_costo = $pv->precio_cobrado;
                                        }
                                        $subTotal = $precio_costo * $pv->cantidad;
                                        $sumaSubTotal += $subTotal;
                                    @endphp
                                    <td class="text-right"><b>{{ number_format($subTotal, 2, '.', '') }}</b></td>
                                    <td>
                                        @php
                                            //$fechaGarantia = Illuminate\Support\Carbon::createFromDate($pv->fecha_garantia);
                                            //$fechaHoy = Illuminate\Support\Carbon::now();
                                            //$diferenciaDias = $fechaGarantia->diffInDays($fechaHoy);
                                            if($diferenciaDias < $pv->producto->dias_garantia && $fechaHoyMayorFechaGarantia):
                                                $precio_cobrado_mayor = ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"";
                                        @endphp
                                                <button type="button" class="btn btn-info" title="CAMBIA PRODUCTO" onclick="cambiaProducto('{{ $pv->producto->id }}', '{{ $pv->id }}', '{{ $pv->producto->nombre }}', '{{ $pv->cantidad }}', '{{ $precio_cobrado_mayor }}', '{{ $pv->fecha_garantia }}')">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                        @php
                                                // Mostrar boton impresion TARJETA GIFTCARD si producto venta es de tipo GIFTCARD
                                                if($pv->tipo->nombre == "GIFTCARD"):
                                        @endphp
                                                    <button type="button" class="btn btn-primary" title="IMPRIMIR GIFTCARD" onclick="imprimirGiftcard('{{ $datosVenta->id }}','{{ $pv->producto->id }}', {{ $pv->gc_impreso }})">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                        @php
                                                endif;
                                            endif;
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach
                            @php if($verificaVentaConGC):
                                   $sumaSubTotal = $sumaSubTotal - $verificaVentaConGC->monto_GC;
                            @endphp;
                                <tr class="table-warning">
                                    <td colspan="6" class="text-right">Venta Canjeada con tarjeta GIFTCARD Nro: <b>{{ $verificaVentaConGC->serial }}</b> - Fecha Vencimiento GC: <b>{{ $utilidades->formatoFecha($verificaVentaConGC->fecha_final, "d-m-Y") }}</b></td>
                                    <td colspan="2" class="text-right"><b>MONTO GC (BS.)</b></td>
                                    <td class="text-right"><b>{{ number_format($verificaVentaConGC->monto_GC, 2, '.', '') }}</b></td>
                                    <td></td>
                                </tr>
                            @php endif; @endphp
                        </tbody>
                        <tfoot  class="table-info">
                            <tr>
                                <th colspan="8" class="text-right">TOTAL COBRADO (BS.)</th>
                                <th class="text-right">{{ number_format($sumaSubTotal, 2, '.', '') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- {{ dd($cambiados->count()) }} --}}
                    @if ($cambiados->count() >0 )
                        <h2 class="text-info">PRODUCTOS CAMBIADOS</h2>
                        <table class="tablesaw table-striped table-hover table-bordered table no-wrap">
                            <thead>
                                <tr>
                                <th class="text-center">#</th>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>MARCA</th>
                                <th>TIPO</th>
                                <th class="text-right"></th>
                                <th class="text-right">PRECIO</th>
                                <th class="text-right">CANTIDAD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cambiados as $con => $pc)
                                <tr>
                                    <td class="text-center">{{ ++$con }}</td>
                                    <td>{{ $pc->producto->codigo }}</td>
                                    <td>{{ $pc->producto->nombre }}</td>
                                    <td>{{ $pc->producto->marca->nombre }}</td>
                                    <td>{{ $pc->producto->tipo->nombre }}</td>
                                    @if ($pc->escala)
                                        @php
                                            $nombreEscala = $pc->escala->nombre
                                        @endphp
                                    @else
                                        @php
                                            $nombreEscala = ""
                                        @endphp
                                    @endif
                                    <td class="text-right"><b>{{ $nombreEscala }}</td>
                                    <td class="text-right">{{ $pc->precio_venta }}</td>
                                    <td class="text-right"><b>{{ $pc->ingreso }}</b></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
            @php
                $ultimoParametro = App\Parametros::where('almacene_id', Auth::user()->almacen_id)
                ->latest()
                ->first();

                $fechaVenta = Illuminate\Support\Carbon::createFromDate($datosVenta->fecha);
                $fechaHoy = Illuminate\Support\Carbon::now();
                $fechaExpiracionFactura = $fechaVenta->addDay(30);
                $fechaFacturaMayorFechaHoy = $fechaExpiracionFactura->isAfter($fechaHoy);
                //echo "fechaExpiracionFactura: ". $fechaExpiracionFactura;
                //echo "<br>fechaFacturaMayorFechaHoy: ". $fechaFacturaMayorFechaHoy;
            @endphp
            @if ($ultimoParametro != null && $ultimoParametro->estado == 'Activo' && $fechaFacturaMayorFechaHoy)
                @php
                    $tagImprimir = "IMPRIMIR FACTURA";
                    $generarImprimirFactura = "imprimir-factura";
                    if($datosVenta->factura_id == ""){
                        $tagImprimir = "GENERAR E IMPRIMIR FACTURA";
                        $generarImprimirFactura = "generar-factura";
                    }
                @endphp
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ url("Venta/imprimeFactura/$datosVenta->id") }}" target="_blank" id="{{ $generarImprimirFactura }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-info">{{ $tagImprimir }}</button>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url("Venta/imprimir/$datosVenta->id") }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-primary">IMPRIMIR GARANTIA</button>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url('Venta/listado') }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">VOLVER</button>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-danger"
                                onclick="muestraFormularioEliminaVenta()">ELIMINAR VENTA</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ url("Venta/imprimir/$datosVenta->id") }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-primary">IMPRIMIR GARANTIA</button>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('Venta/listado') }}">
                                <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">VOLVER</button>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-danger"
                                onclick="muestraFormularioEliminaVenta()">ELIMINAR VENTA</button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>

<!--This page JavaScript -->
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

    function muestraFormularioEliminaVenta()
    {
        $("#modalElimina").modal("show");
    }

    function enviaDatosEliminar()
    {
        if ($("#formularioEliminaVenta")[0].checkValidity()) {

            let ventaId = $("#ventaId").val();
            let opcionElimina = $("#opcion_elimina").val();

            $.ajax({
                url: "{{ url('Venta/elimina') }}",
                data: {"_token": "{{ csrf_token() }}",
                        "ventaId": ventaId,
                        "opcion_elimina": opcionElimina,
                        },
                type: 'POST',
                success: function(data) {
                    if(data.respuesta == "success"){
                        Swal.fire({
                            type: 'success',
                            title: 'Excelente!',
                            text: 'Venta Eliminada'
                        }).then((result) => {
                            window.location.href = "{{ url('Venta/listado') }}";
                        });
                    }else{
                        Swal.fire({
                            type: 'error',
                            title: 'Atención!',
                            text: data.mensaje
                        }).then((result) => {
                            $("#modalElimina").modal("hide");
                        });
                    }
                }
            });



        // Swal.fire({
        //     type: 'success',
        //     title: 'Excelente!',
        //     text: 'Venta Eliminada'
        // })
        // $("#formularioEliminaVenta").submit();

        }else{
            $("#formularioEliminaVenta")[0].reportValidity();
        }
    }

    function cambiaOpcionEliminaVenta(){}

    function cancelaElimnacion()
    {
        $("#modalElimina").modal("hide");
    }

    function cancelaCambio()
    {
        $("#modalCambiaProducto").modal("hide");
    }

    function cambiaProducto(productoId, ventaId, nombreProducto, cantidad, nombrePaquete, diasGarantia)
    {
        $("#nombre_producto_cambio").val(nombreProducto);
        $("#cantidad_producto").val(parseInt(cantidad));
        $("#cantidad_producto_a_cambiar").val(parseInt(cantidad));
        $("#ventaProductoCambia").val(ventaId);
        $("#nombrePaqueteCambio").html(nombrePaquete);

        $("#cantidad_producto_a_cambiar").attr({"max":parseInt(cantidad)});
        $("#nombreCambiaProducto").html(nombreProducto);
        $("#productoModificaId").val(productoId);
        $("#modalCambiaProducto").modal("show");
    }

    function enviaDatosCambia()
    {
        let productoCambiaId = $("#productoModificaId").val();
        let ventaCambiaId = $("#ventaModificaId").val();
        let opcionCambia = $("#opcion_cambia").val();
        let cantidadCambio = $("#cantidad_producto_a_cambiar").val();
        let ventaProductoId = $("#ventaProductoCambia").val();

        if ($("#formularioCambiaProducto")[0].checkValidity()) {

            $.ajax({
                url: "{{ url('Venta/ajaxCambiaProducto') }}",
                data: {"_token": "{{ csrf_token() }}",
                        "productoId": productoCambiaId,
                        "ventaId": ventaCambiaId,
                        "opcionCambia": opcionCambia,
                        "cantidad": cantidadCambio,
                        "ventaProductoId": ventaProductoId
                        },
                type: 'POST',
                success: function(data) {
                    // $("#ajaxFormEditaCliente").html(data);
                    window.location.href = "{{ url('Venta/muestra') }}/"+data.ventaId;
                }
            });

        // $("#formularioEliminaVenta").submit();

        }else{
            $("#formularioCambiaProducto")[0].reportValidity();
        }

    }

    // Generar e imprimir Factura
    $('#generar-factura').on('click', function(e) {
        e.preventDefault();
        console.log($(this).attr("href"));

        Swal.fire({
            title: '¿Desea Generar e Imprimir la Factura de esta venta?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI, facturar',
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                window.open($(this).attr("href"), '_blank');
                return false;
            }
        })


    });

    function imprimirGiftcard(ventaId,productoId, gcImpreso){

        //gcImpreso = 0;

        if ( !gcImpreso || gcImpreso == false || gcImpreso == 0 ){

            Swal.fire({
                    title: '¿Confirma IMPRIMIR la tarjeta GIFTCARD?',
                    type: 'warning',
                    text: 'Entrara a la ventana de impresion, una vez que imprima la GIFTCARD no podra imprimir nuevamente.',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, entrar a la ventana!',
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.value) {
                        window.location.href = "{{ url('Venta/imprimirGiftcard') }}/"+ventaId+'/'+productoId+'/'+gcImpreso;
                    }
                });

        }else{
            Swal.fire({
                    type: 'warning',
                    title: 'GiftCard Impreso!',
                    text: 'El tarjeta giftcard ya fue impreso, no puede volver a imprimir.'
            });
        }
    }

</script>
@endsection
