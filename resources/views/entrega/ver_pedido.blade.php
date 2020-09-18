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
            <form action="{{ url('Pedido/adicionaProducto') }}" method="POST" class="form-horizontal col-md-12" >
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
                    <input type="hidden" name="pedido_id" id="pedido_id" value="{{ $pedido->id }}">
                    <input type="hidden" name="numero_pedido" id="numero_pedido" value="{{ $pedido->numero }}">
                    <input type="hidden" name="almacen_solicitante" id="almacen_solicitante" value="{{ $pedido->almacen->id }}">
                    <input type="hidden" name="almacen_solicitado" id="almacen_solicitado" value="{{ $pedido->almacen_destino->id }}">
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
                            <label class="control-label">Cantidad a Solicitar</label>
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
        <h4 class="mb-0 text-white">DETALLE DE PEDIDO</h4>
    </div>
    <div class="card-body" id="printableArea">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td><h4><span class="text-info">Numero:</span> {{ $pedido->numero }}</h4></td>
                            <td><h4><span class="text-info">Almacen Solicitante:</span> {{ $pedido->almacen->nombre }}</h4></td>
                            <td><h4><span class="text-info">Almacen Solicitado:</span> {{ $pedido->almacen_destino->nombre }}</h4></td>
                            <td><h4><span class="text-info">Fecha:</span> {{ $pedido->fecha }}</h4></td>
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
                                <th class="text-center">Cantidad Disponible</th>
                                <th class="text-center">Cantidad a Solicitar</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido_productos as $key => $producto)
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $producto->producto->codigo }}</td>
                                    <td>{{ $producto->producto->nombre }}</td>
                                    <td>{{ $producto->producto->marca->nombre }}</td>
                                    <td>{{ $producto->producto->tipo->nombre }}</td>
                                    <td>{{ $producto->producto->modelo }}</td>
                                    @php
                                        $ingreso = App\Movimiento::where('producto_id', $producto->producto_id)
                                                                ->where('almacene_id', $pedido->almacene_id)
                                                                ->where('ingreso', '>', 0)
                                                                ->sum('ingreso');
                                        $salida = App\Movimiento::where('producto_id', $producto->producto_id)
                                                                ->where('almacene_id', $pedido->almacene_id)
                                                                ->where('salida', '>', 0)
                                                                ->sum('salida');
                                        $total = $ingreso - $salida;
                                    @endphp
                                    <td class="text-center">{{ $total }}</td>
                                    <td class="text-center">{{ $producto->cantidad }}</td>
                                    <td>
                                        @if(auth()->user()->almacen_id == $pedido->almacen->id || auth()->user()->perfil_id == 1)
                                            <button type="button" class="btn btn-danger" title="Eliminar producto" onclick="eliminar('{{ $producto->id }}', '{{ $producto->producto->nombre }}')"><i class="fas fa-trash-alt"></i></button>
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
    
    @if(auth()->user()->almacen_id == $pedido->almacen->id || auth()->user()->perfil_id == 1)
        <div class="card-footer">
            <div class="row">
                <!-- <div class="col">
                    <a class="btn btn-inverse btn-block " href="{{ url('Envio/vista_previa_envio/'.$pedido->numero) }}" target="_blank"><span><i class="fa fa-print"></i> VISTA PREVIA IMPRESION </span></a>
                </div> -->
                    <div class="col">
                            <button class="btn btn-danger btn-block" onclick="elimina_envio()" type="button"> <span><i class="fa fa-print"></i> ELIMINAR PEDIDO </span></button>
                    </div>
            </div>
        </div>
    @endif
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

    $(document).on('keyup', '#termino', function(e) {
        almacen_solicitado = $('#almacen_solicitado').val();
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 3) {
            $.ajax({
                url: "{{ url('Pedido/ajaxBuscaProductos') }}",
                data: {
                    almacen_solicitado: almacen_solicitado,
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
                    window.location.href = "{{ url('Pedido/eliminaProducto') }}/"+id;
                });
            }
        })
    }

    function elimina_envio()
    {
        let numero_pedido = $('#numero_pedido').val();
        let pedido_id = $('#pedido_id').val();
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
                    window.location.href = "{{ url('Pedido/eliminaPedido') }}/"+pedido_id;
                });
            }
        })
    }
</script>
@endsection
