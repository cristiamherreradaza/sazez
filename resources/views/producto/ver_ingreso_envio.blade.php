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
            <form action="{{ url('Producto/adicionaProducto') }}" method="POST" class="form-horizontal col-md-12" >
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
                    <input type="hidden" name="numero_ingreso" id="numero_ingreso" value="{{ $datos->numero_ingreso }}">
                    <input type="hidden" name="proveedor_id" id="proveedor_id" value="{{ $datos->proveedor_id }}">
                    <input type="hidden" name="almacen_ingreso" id="almacen_ingreso" value="{{ $datos->almacene_id }}">
                    <input type="hidden" name="numero_ingreso_envio" id="numero_ingreso_envio" value="{{ $datos_envio->numero_ingreso_envio }}">
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
                            <label class="control-label">Cantidad a Ingresar</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="producto_cantidad" name="producto_cantidad" value="1" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn btn-block btn-primary">INGRESAR</button>
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
        <h4 class="mb-0 text-white">DETALLE DE INGRESO - ENVIO</h4>
    </div>
    <div class="card-body" id="printableArea">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsve">
                    <table class="table table-hover">
                        <thead>
                            <h4><span class="text-danger">PRODUCTOS PARA INGRESO</span></h4>
                            <tr>
                                <td colspan="2"><h4><span class="text-info">Numero de ingreso:</span> {{ $datos->numero_ingreso }}</h4></td>
                                <td colspan="2"><h4><span class="text-info">Sucursal:</span> {{ $datos->almacen->nombre }}</h4></td>
                                <td colspan="2"><h4><span class="text-info">Proveedor:</span> {{ $datos->proveedor->nombre }}</h4></td>
                                <td colspan="2"><h4><span class="text-info">Fecha:</span> {{ $datos->fecha }}</h4></td>
                            </tr>
                        </thead>
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th class="text-center">Cantidad</th>
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
                                    <td>
                                        @if(auth()->user()->perfil_id == 1)
                                            <button type="button" class="btn btn-danger" title="Eliminar marca" onclick="eliminar('{{ $producto->id }}', '{{ $producto->producto->nombre }}')"><i class="fas fa-trash-alt"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <table class="table table-hover">
                        <thead>
                            <h4><span class="text-danger">PRODUCTOS PARA ENVIO</span></h4>
                            <tr>
                                <td colspan="2"><h4><span class="text-info">Numero de envio:</span> {{ $datos_envio->numero }}</h4></td>
                                <td colspan="2"><h4><span class="text-info">Origen:</span> {{ $datos_envio->almacen_origen->nombre }}</h4></td>
                                <td colspan="2"><h4><span class="text-info">Destino:</span> {{ $datos_envio->almacen->nombre }}</h4></td>
                                <td colspan="2"><h4><span class="text-info">Fecha:</span> {{ $datos_envio->fecha }}</h4></td>
                            </tr>
                        </thead>
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th class="text-center">Ingreso</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos_envio as $key => $producto)
                            @php
                                $total = 0;
                            @endphp
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $producto->producto->codigo }}</td>
                                    <td>{{ $producto->producto->nombre }}</td>
                                    <td>{{ $producto->producto->marca->nombre }}</td>
                                    <td>{{ $producto->producto->tipo->nombre }}</td>
                                    <td class="text-center">{{ round($producto->ingreso) }}</td>
                                    @php
                                        $ingreso = App\Movimiento::where('producto_id', $producto->producto_id)
                                                                ->where('almacene_id', $producto->almacene_id)
                                                                ->where('ingreso', '>', 0)
                                                                ->sum('ingreso');
                                        $salida = App\Movimiento::where('producto_id', $producto->producto_id)
                                                                ->where('almacene_id', $producto->almacene_id)
                                                                ->where('salida', '>', 0)
                                                                ->sum('salida');
                                        $total = $ingreso - $salida;
                                    @endphp
                                    <td class="text-center">{{ ($total-$producto->ingreso) }}</td>
                                    <td class="text-center">{{ $total }}</td>
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
                <a class="btn btn-inverse btn-block " href="{{ url('Producto/vista_previa_ingreso/'.$datos->numero_ingreso) }}" target="_blank"><span><i class="fa fa-print"></i> VISTA IMPRESION INGRESO </span></a>
            </div>
            <div class="col">
                <a class="btn btn-inverse btn-block " href="{{ url('Envio/vista_previa_envio/'.$datos_envio->numero) }}" target="_blank"><span><i class="fa fa-print"></i> VISTA IMPRESION ENVIO </span></a>
            </div>
            @if(auth()->user()->perfil_id == 1)
                <div class="col">
                    <button class="btn btn-danger btn-block" onclick="elimina_ingreso()" type="button"> <span><i class="fa fa-print"></i> ELIMINAR INGRESO </span></button>
                </div>
            @endif
        </div>
    </div>
</div>
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
                    window.location.href = "{{ url('Producto/eliminaProducto') }}/"+id;
                });
            }
        })
    }

    function elimina_ingreso()
    {
        let numero_ingreso = $('#numero_ingreso').val();
        Swal.fire({
            title: 'Quieres borrar el envio # ' + numero_ingreso + '?',
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
                    window.location.href = "{{ url('Producto/eliminaIngreso') }}/"+numero_ingreso;
                });
            }
        })
    }

    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 2) {
            $.ajax({
                url: "{{ url('Producto/ajaxBuscaIngresoProducto') }}",
                data: {termino: termino_busqueda},
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }

    });


</script>
@endsection
