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
            <form class="form-horizontal col-md-12">
                <div class="row">
                    <div class="col-md-4">
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
                <div class="table-responsve">
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
                                        $stock=intval($stock->total);
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
                                        <button type="button" class="btn btn-danger" title="Eliminar marca" onclick="eliminar('{{ $producto->id }}', '{{ $producto->producto->nombre }}')"><i class="fas fa-trash-alt"></i></button>
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
        <button id="botonImprimir" class="btn btn-inverse btn-block col-md-12 print-page" type="button"> <span><i class="fa fa-print"></i> IMPRIMIR </span></button>
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
            // hay aque corregir
            window.location.href = "{{ url('Envio/eliminaProducto') }}/"+id;
            if (result.value) {
                Swal.fire(
                    'Excelente!',
                    'El producto fue eliminado',
                    'success'
                );
            }
        })
    }

    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 3) {
            $.ajax({
                url: "{{ url('Movimiento/ajaxBuscaProducto') }}",
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
