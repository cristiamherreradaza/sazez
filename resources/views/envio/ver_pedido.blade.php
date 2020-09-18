@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/lobilist.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/jquery-ui.min.css') }}">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">ADICIONA UN PRODUCTO</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <form action="{{ url('Envio/adicionaProducto') }}" method="POST" class="form-horizontal col-md-12" >
                @csrf
                <div class="row">
                    <div class="col-md-3">
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
                    <input type="hidden" name="producto_id" id="producto_id" value="">
                    <input type="hidden" name="numero_pedido" id="numero_pedido" value="{{ $datos->numero }}">
                    <input type="hidden" name="almacen_origen" id="almacen_origen" value="{{ $datos->almacen_origen_id }}">
                    <input type="hidden" name="almacen_destino" id="almacen_destino" value="{{ $datos->almacene_id }}">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Nombre de Producto</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="producto_nombre" name="producto_nombre" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="control-label">Stock</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="producto_stock" name="producto_stock" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="control-label">Cantidad a Enviar</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="producto_cantidad" name="producto_cantidad" value="1" min="1" max="" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn btn-block btn-primary">ADICIONAR</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
        <div class="row">
            <div class="col-md-12" id="listadoProductosAjax">
        
            </div>
        </div>
    </div>
</div>

<div class="card border-primary">
    <div class="card-header bg-primary">
        <h4 class="mb-0 text-white">DETALLE DE ENVIO</h4>
    </div>
    <div class="card-body" id="printableArea">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td><h4><span class="text-info">Numero:</span> {{ $datos->numero }}</h4></td>
                            <td><h4><span class="text-info">Desde:</span> {{ $datos->almacen_origen->nombre }}</h4></td>
                            <td><h4><span class="text-info">Hasta:</span> {{ $datos->almacen->nombre }}</h4></td>
                            <td><h4><span class="text-info">Fecha:</span> {{ $datos->fecha }}</h4></td>
                        </tr>
                    </table>
                    
                    <table class="table table-hover">
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $key => $producto)
                            @php
                                $total = 0;
                            @endphp
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $producto->producto->codigo }}</td>
                                    <td>{{ $producto->producto->nombre }}</td>
                                    <td>{{ $producto->producto->marca->nombre }}</td>
                                    <td>{{ $producto->producto->tipo->nombre }}</td>
                                    <td>{{ $producto->producto->modelo }}</td>
                                    <td class="text-center">{{ round($producto->ingreso) }}</td>
                                    @php
                                        $stock = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                                                ->where('producto_id', $producto->producto_id)
                                                ->where('almacene_id', $datos->almacen->id)
                                                ->first();
                                        $stock=intval($stock->total - $producto->ingreso);
                                    @endphp
                                    <td class="text-center">{{ $stock }}</td>
                                    <td class="text-right">
                                        <h4>
                                        @php
                                            $total = $stock + round($producto->ingreso);
                                            echo $total;
                                        @endphp
                                        </h4>
                                    </td>
                                    <td>
                                        @if(auth()->user()->perfil_id == 1)
                                            <button type="button" class="btn btn-danger" title="Eliminar producto" onclick="eliminar('{{ $producto->id }}', '{{ $producto->producto->nombre }}')"><i class="fas fa-trash-alt"></i></button>
                                        @endif
                                        @if($total > 0)
                                            <button class="btn btn-dark" onclick="reporta_producto('{{ $producto->producto->id }}', '{{ $producto->producto->nombre }}')" title="Reportar producto"><i class="fas fa-sort-amount-down"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-footer">
        <div class="row">
            <div class="col">
                <!-- <button id="botonImprimir" class="btn btn-inverse btn-block print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button> -->
                <a class="btn btn-inverse btn-block " href="{{ url('Envio/vista_previa_envio/'.$datos->numero) }}" target="_blank"><span><i class="fa fa-print"></i> VISTA PREVIA IMPRESION </span></a>
            </div>
            @if(auth()->user()->perfil_id == 1)
                <div class="col-md-6">
                        <button class="btn btn-danger btn-block" onclick="elimina_envio()" type="button"> <span><i class="fa fa-print"></i> ELIMINAR ENVIO </span></button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- inicio modal reportar producto -->
<div id="reportar_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-dark">
                <h4 class="modal-title text-white" id="myModalLabel">REPORTAR PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Movimiento/reportar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_producto_a_reportar" id="id_producto_a_reportar" value="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input name="nombre_producto_a_reportar" type="text" id="nombre_producto_a_reportar" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Cantidad</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="cantidad_producto_a_reportar" type="number" id="cantidad_producto_a_reportar" min="1" class="form-control" value="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="descripcion_producto_a_reportar" type="text" id="descripcion_producto_a_reportar" minlength="4" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-dark" onclick="reportar()">ENVIAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal reportar producto -->
@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>

<script src="{{ asset('assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved.js') }}"></script>
<script src="{{ asset('assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        $('#config-table').DataTable({
            responsive: true,
            "order": [
                [0, 'asc']
            ],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

    $("#botonImprimir").click(function() {
		var mode = 'iframe'; //popup
		var close = mode == "popup";
		var options = {
				mode: mode,
				popClose: close
		};
		$("div#printableArea").printArea(options);
	});

    function eliminar(id, nombre)
    {
        Swal.fire({
            title: 'Quieres borrar ' + nombre + '?',
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
                    'El producto fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Envio/eliminaProducto') }}/"+id;
                });
            }
        })
    }

    function elimina_envio()
    {
        let numero_pedido = $('#numero_pedido').val();
        Swal.fire({
            title: 'Quieres borrar el envio # ' + numero_pedido + '?',
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
                    'El envio fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Envio/eliminaEnvio') }}/"+numero_pedido;
                });
            }
        })
    }

    $(document).on('keyup', '#termino', function(e) {
        almacen_origen = $('#almacen_origen').val();
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 3) {
            $.ajax({
                url: "{{ url('Envio/ajaxBuscaProducto') }}",
                data: {
                    almacen_origen: almacen_origen,
                    termino: termino_busqueda
                    },
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }

    });

    function reporta_producto(id_producto, nombre)
    {
        $("#id_producto_a_reportar").val(id_producto);
        $("#nombre_producto_a_reportar").val(nombre);
        $("#reportar_producto").modal('show');
    }

    function reportar()
    {
        //var cantidad = $("#cantidad_producto_a_reportar").val();
        var descripcion = $("#descripcion_producto_a_reportar").val();
        if(descripcion.length>3){
            Swal.fire(
                'Excelente!',
                'Producto reportado correctamente.',
                'success'
            )
        }
    }
</script>
@endsection
