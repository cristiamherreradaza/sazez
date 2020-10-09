@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-primary">
    <div class="card-header bg-primary">
        <h4 class="mb-0 text-white">DETALLE DE PEDIDO - ENVIO</h4>
    </div>
    <div class="card-body" id="printableArea">
        <div class="row">
            <div class="col-md-12">
                <div class="row col-md-12">
                    <h4><span class="text-danger">PRODUCTOS SOLICITADOS</span></h4>
                </div>
                <div class="row">
                    <div class="col-md-4"><h4><span class="text-info">Numero de pedido:</span> {{ $pedido->numero }}</h4></div>
                    <div class="col-md-4"><h4><span class="text-info">Sucursal que solicita:</span> {{ $pedido->almacen->nombre }}</h4></div>
                    <div class="col-md-4"><h4><span class="text-info">Fecha:</span> {{ $pedido->fecha }}</h4></div>
                </div>
                <div class="table-responsve">
                    <table class="table table-hover">
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th class="text-center">Cantidad Solicitada</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido_productos as $key => $producto)
                            @php
                                $total = 0;
                            @endphp
                                <tr>
                                    <td>{{ ($key+1) }}</td>
                                    <td>{{ $producto->producto->codigo }}</td>
                                    <td>{{ $producto->producto->nombre }}</td>
                                    <td>{{ $producto->producto->marca->nombre }}</td>
                                    <td>{{ $producto->producto->tipo->nombre }}</td>
                                    <td class="text-center">{{ round($producto->cantidad) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="row col-md-12">
                        <h4><span class="text-danger">PRODUCTOS ENVIADOS</span></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><h4><span class="text-info">Numero de envio:</span> {{ $envio->numero }} </h4></div>
                        <div class="col-md-4"><h4><span class="text-info">Sucursal que envia:</span> {{ $envio->almacen_origen->nombre }} </h4></div>
                        <div class="col-md-4"><h4><span class="text-info">Fecha:</span> {{ $envio->fecha }} </h4></div>
                    </div>
                    <table class="table table-hover">
                        <thead class="bg-inverse text-white">
                            <tr>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th class="text-center">Cantidad Enviada</th>
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
                                    @php
                                        $cantidad = App\Movimiento::where('pedido_id', $pedido->id)
                                                                ->where('ingreso', '>', 0)
                                                                ->where('producto_id', $producto->producto_id)
                                                                ->first();
                                        if($cantidad){
                                            $cantidad = $cantidad->ingreso;
                                        }else{
                                            $cantidad = 0;
                                        }

                                        $saldo = App\Movimiento::select(DB::raw("(SUM(ingreso) - SUM(salida)) as total"))
                                                        ->where('producto_id', $producto->producto->id)
                                                        ->where('almacene_id', $envio->almacen_origen->id)
                                                        ->whereDate('fecha', '<=', date('Y-m-d'))
                                                        ->get();
                                        if($saldo[0]->total)
                                        {
                                            $saldo = round($saldo[0]->total);
                                        }
                                        else
                                        {
                                            $saldo = 0;
                                        }
                                    @endphp
                                    <td class="text-center">{{ round($cantidad) }}</td>
                                    <td>
                                        @if(auth()->user()->perfil_id == 1)
                                            <button type="button" class="btn btn-primary" title="Modificar cantidad" onclick="editar('{{ $producto->id }}', '{{ $producto->producto->nombre }}', '{{ $saldo }}', '{{ $producto->cantidad }}', '{{ round($cantidad) }}')"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger" title="Eliminar envio" onclick="eliminar('{{ $producto->id }}', '{{ $producto->producto->nombre }}')"><i class="fas fa-trash-alt"></i></button>
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
                <a class="btn btn-inverse btn-block " href="{{ url('Entrega/vista_previa_entrega/'.$pedido->id) }}" target="_blank"><span><i class="fa fa-print"></i> VISTA IMPRESION PEDIDO </span></a>
            </div>
            @if(auth()->user()->perfil_id == 1)
                <div class="col">
                    <button class="btn btn-danger btn-block" onclick="elimina_envio()" type="button"> <span><i class="fa fa-print"></i> ELIMINAR TODO EL ENVIO </span></button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- inicio modal editar almacen -->
<div id="editar_envios" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">MODIFICAR ENVIO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Entrega/modificar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="numero_envio" id="numero_envio" value="{{ $envio->numero }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nombre Producto</label>
                                <input name="nombre" type="text" id="nombre" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Stock en {{ $envio->almacen_origen->nombre }}</label>
                                <input name="saldo" type="text" id="saldo" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad solicitada</label>
                                <input name="cantidad_solicitada" type="number" id="cantidad_solicitada" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cantidad a enviar</label>
                                <input name="cantidad_enviar" type="number" id="cantidad_enviar" min="0" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-primary">ACTUALIZAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal editar almacen -->
@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    // Funcion para el uso de ajax
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Funcion que habilita el datatable
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

    // Funcion que elimina un producto de la lista de producto enviados
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
                    window.location.href = "{{ url('Entrega/eliminaEntrega') }}/"+id;
                });
            }
        })
    }

    // Funcion que elimina todo el envio de productos solicitados
    function elimina_envio()
    {
        let numero_envio = $('#numero_envio').val();
        Swal.fire({
            title: 'Quieres borrar el envio # ' + numero_envio + '?',
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
                    window.location.href = "{{ url('Entrega/eliminaEnvio') }}/"+numero_envio;
                });
            }
        })
    }

    function editar(id, nombre, saldo, cantidad_solicitada, cantidad_enviar)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#saldo").val(saldo);
        $("#cantidad_solicitada").val(cantidad_solicitada);
        $("#cantidad_enviar").val(cantidad_enviar);
        $("#editar_envios").modal('show');
    }
</script>
@endsection
