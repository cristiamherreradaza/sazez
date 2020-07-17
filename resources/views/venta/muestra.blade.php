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
                        <input type="hidden" value="{{ $datosVenta->id }}" name="ventaId">
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
            <div class="modal-header modal-colored-header bg-danger">
                <h4 class="modal-title text-white" id="danger-header-modalLabel">CAMBIA PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h4 class="card-title">Seleccione el motivo</h4>
                <form class="mt-3" action="{{ url('Venta/elimina') }}" method="POST" id="formularioEliminaVenta">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" value="{{ $datosVenta->id }}" name="ventaId">
                        <select name="opcion_elimina" id="opcion_elimina" class="form-control"
                            onchange="cambiaOpcionEliminaVenta()" required>
                            <option value="">Seleccione una opcion</option>
                            @foreach ($opcionesCambiaProductoVenta as $ocv)
                            <option value="{{ $ocv->valor }}">{{ $ocv->valor }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="form-group">
                        <textarea class="form-control" rows="3" maxlength=500 id="comentario_elimina" name="comentario_elimina" required></textarea>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-success"
                                onclick="enviaDatosEliminar()">CAMBIAR PRODUCTO</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-inverse"
                                onclick="cancelaElimnacion()">CANCELAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{{-- info modal cambia CambiaProductoo --}}

<div class="card card-body">
    <div class="invoice-123" id="printableArea">
        <div class="row pt-3">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4"><h2><span class="text-info">Venta #</span> {{ $datosVenta->id }}</h2></div>
                    <div class="col-md-4"><h2><span class="text-info">Cliente:</span> {{ $datosVenta->cliente->name }}</h2></div>
                    <div class="col-md-4"><h2><span class="text-info">Fecha: </span> {{ $datosVenta->fecha }}</h2></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive mt-5" style="clear: both;">
                    <table class="tablesaw table-striped table-hover table-bordered table no-wrap">
                        <thead>
                                <th class="text-center">#</th>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>MARCA</th>
                                <th>TIPO</th>
                                <th class="text-right"></th>
                                <th class="text-right">PRECIO</th>
                                <th class="text-right">CANTIDAD</th>
                                <th class="text-right">Total</th>
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
                                    <td class="text-right"><b>{{ ($pv->precio_cobrado_mayor>0)?$pv->escala->nombre:"" }}</b></td>
                                    <td class="text-right">{{ ($pv->precio_cobrado_mayor>0)?$pv->precio_cobrado_mayor:$pv->precio_cobrado }}</td>
                                    <td class="text-right"><b>{{ $pv->cantidad }}</td>
                                    @php
                                        if ($pv->precio_cobrado_mayor>0) {
                                            $precio_costo = $pv->precio_cobrado_mayor;
                                        }else{
                                            $precio_costo = $pv->precio_cobrado;
                                        }
                                        $subTotal = $precio_costo * $pv->cantidad;
                                        $sumaSubTotal += $subTotal;
                                    @endphp
                                    <td class="text-right">{{ $subTotal }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info" title="CAMBIA PRODUCTO" onclick="cambiaProducto()">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right">TOTAL</th>
                                <th class="text-right">{{ $sumaSubTotal }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn waves-effect waves-light btn-block btn-danger" onclick="muestraFormularioEliminaVenta()">ELIMINAR VENTA</button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url('Venta/listado') }}">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">CANCELAR</button>
                        </a>
                    </div>
                </div>
            </div>
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

        Swal.fire({
            type: 'success',
            title: 'Excelente!',
            text: 'Venta Eliminada'
        })
        $("#formularioEliminaVenta").submit();

        }else{
            $("#formularioEliminaVenta")[0].reportValidity();
        }
    }

    function cambiaOpcionEliminaVenta()
    {
        let opcionEliminaVenta = $("#opcion_elimina").val();
            $.ajax({
            url: "{{ url('Cliente/ajaxEditaCliente') }}",
            data: { clienteId: clienteId },
            type: 'POST',
            success: function(data) {
                $("#ajaxFormEditaCliente").html(data);
            }
        });

        // $("#comentario_elimina").val(opcionEliminaVenta);
        // console.log(opcionEliminaVenta);
    }

    function cancelaElimnacion()
    {
        $("#modalElimina").modal("hide");
    }

    function cambiaProducto()
    {
        $("#modalCambiaProducto").modal("show");
    }

</script>
@endsection