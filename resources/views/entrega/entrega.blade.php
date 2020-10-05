@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">ENTREGA DE PEDIDO DE PRODUCTOS</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Numero de Pedido</label>
                    <div class="input-group mb-3">
                        <h5 class="form-control">{{ $pedido->numero }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Almacen a enviar</label>
                    <div class="input-group mb-3">
                        <h5 class="form-control">{{ $pedido->almacen->nombre }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Usuario que envia</label>
                    <div class="input-group mb-3">
                        <h5 class="form-control">{{ auth()->user()->name }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Fecha solicitud</label>
                    <div class="input-group mb-3">
                        <h5 class="form-control">{{ $pedido->fecha }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-dark">
            <div class="card-header bg-dark">
                <h4 class="mb-0 text-white">PRODUCTOS PARA ENVIO</h4>
            </div>
            <div class="card-body">
                <form action="{{ url('Entrega/store') }}" method="POST">
                @csrf
                <input type="text" id="pedido_id" name="pedido_id" value="{{ $pedido->id }}" hidden>
                <div class="table-responsive m-t-40">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th>Stock</th>
                                <th>Cantidad Solicitada</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $n = 1;
                            @endphp
                            @foreach ($pedido_productos as $key => $producto)
                                <tr>
                                    <td>{{ $key+1 }}</td>
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
                                        $cantidad_disponible = $ingreso - $salida;
                                    @endphp
                                    <td style="text-align:center;">{{ $cantidad_disponible }}</td>
                                    <td style="text-align:center;">{{ $producto->cantidad }}</td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="cantidad_{{ $producto->producto->id }}" name="cantidad_{{ $producto->producto->id }}" value="0" min="0" max="{{ $cantidad_disponible }}">
                                        </div>
                                    </td>  
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-success">ENTREGAR PRODUCTOS</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>

@endsection
