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
        <h4 class="mb-0 text-white">PRODUCTOS ENTREGADOS</h4>
    </div>  
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label text-right col-md-6" style="color: green; font-weight: normal;">Almacen:</label>
                            <div class="col-md-6">
                                <p class="form-control-static"> {{ $pedidos[0]->nombre }} </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label text-right col-md-6" style="color: green; font-weight: normal;">Encargado de Almacen:</label>
                            <div class="col-md-6">
                                <p class="form-control-static"> {{ $pedidos[0]->solicitante_id }} </p>
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    
                    <!--/span-->
                </div>
                <!--/row-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label text-right col-md-6" style="color: green; font-weight: normal;">Numero de Pedido:</label>
                            <div class="col-md-6">
                                <p class="form-control-static"> {{ $pedidos[0]->numero }} </p>
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label text-right col-md-6" style="color: green; font-weight: normal;">Fecha de Entrega:</label>
                            <div class="col-md-6">
                                <p class="form-control-static"> {{ $pedidos[0]->fecha }} </p>
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>
            </div>
                
            </div>
    </div>
    <div class="row">
        <div class="col-md-11 m-auto">
            <div class="card card-outline-primary">                                
                <div class="card-header bg-secondary">
                    <h4 class="mb-0 text-white">PRODUCTOS</h4>
                </div>
                <form action="{{ url('Entrega/store') }}" method="POST">
                @csrf
                    <input type="text" class="form-control" id="pedido_id" name="pedido_id" value="{{ $pedidos[0]->id }}" hidden>
                    <input type="text" class="form-control" id="almacene_id" name="almacene_id" value="{{ $pedidos[0]->almacene_solicitante_id }}" hidden>
                    <br>
                    <div class="table-responsive m-t-40">
                        <table id="config-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Tipo</th>
                                    <th>Modelo</th>
                                    <th>Colores</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $n = 1;
                                @endphp
                                @foreach ($productos as $prod)
                                    <tr>
                                        <td>{{ $n++ }}</td>
                                        <td>{{ $prod->codigo }}</td>
                                        <td>{{ $prod->nombre }}</td>
                                        <td>{{ $prod->nombre_marca }}</td>
                                        <td>{{ $prod->nombre_tipo }}</td>
                                        <td>{{ $prod->modelo }}</td>
                                        <td>{{ $prod->colores }}</td>
                                        <td>{{ $prod->ingreso }}</td>
                                    </tr>
                                @endforeach
                                
                            </tbody>

                        </table>
                        {{-- <div class="modal-footer">
                                <button type="submit" onclick="enviar()" class="btn waves-effect waves-light btn-block btn-success">ENTREGAR PRODUCTOS</button>
                        </div> --}}
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
<script>
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
</script>
@endsection
