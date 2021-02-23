@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-info">
            <div class="card-header bg-info">
                <h4 class="mb-0 text-white">REPORTE DE INGRESOS</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Fecha Inicio</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ date('Y-m-d') }}"required>
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Fecha Final</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ date('Y-m-d') }}"required>
                        </div>                    
                    </div>
                    @if (auth()->user()->perfil_id == 1)
                    <div class="col">
                        <div class="form-group">
                            <label>Sucursal</label>
                            <select name="almacen_id" id="almacen_id" class="form-control" onchange="muestraVendedores();">
                                @if(auth()->user()->rol == 'Administrador')
                                    <option value="todos">Todos</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ auth()->user()->almacen->id }}">{{ auth()->user()->almacen->nombre }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo_id" id="tipo_id" class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option value="todos" selected>Todos</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label>Marcas</label>
                            <select name="marca_id" id="marca_id" class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option value="todos" selected>Todos</option>
                                @foreach($marcas as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="button" onclick="buscar()" class="btn btn-block btn-primary" id="btnBuscar">Buscar</button>
                            <button class="btn btn-primary btn-block" type="button" id="btnTrabajando" disabled style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                &nbsp;&nbsp;Estamos trabajando ;-)
                            </button>

                        </div>                    
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-12">
                        <div id="listadoProductosAjax"></div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-12" id="mostrar" style="display:none;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="col-md-12" id="mostrar" style="display:none;">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">LISTA DE PROMOCIONES CANJEADAS</h4>
            <table id="tabla-tienda" class="table table-bordered table-striped no-wrap">
                <thead>
                    <tr>
                        <th>Almacen</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Almacen</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                </tfoot>
            </table>
        </div>
    </div>
</div> -->

@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="{{ asset('dist/js/pages/datatable/datatable-advanced.init.js') }}"></script>

<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $(".select2").select2();
    });
    
    function buscar()
    {
        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin    = $("#fecha_fin").val();
        var almacen_id   = $("#almacen_id").val();
        var tipo_id      = $("#tipo_id").val();
        // var usuario_id   = $("#usuario_id").val();
        var marca_id     = $("#marca_id").val();

        $("#btnBuscar").hide();
        $("#btnTrabajando").show();

        // var continuo  = $("#continuo").val();
        $.ajax({
            url: "{{ url('Reporte/ajax_ingresos') }}",
            data: {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
                almacen_id: almacen_id,
                tipo_id: tipo_id,
                // usuario_id: usuario_id,
                marca_id: marca_id
                },
            type: 'get',
            success: function(data) {
                $("#mostrar").html(data);
                $("#mostrar").show('slow');
                $("#btnBuscar").show();
                $("#btnTrabajando").hide();
            }
        });
    }

    function muestraVendedores()
    {
        let almacenId = $("#almacen_id").val();
        if(almacenId == 'todos'){
            $("#muestraVendedores").hide();
        }else{
            $("#muestraVendedores").show();
        }
        $.ajax({
            url: "{{ url('Reporte/ajaxMuestraVendedores') }}",
            data: {
                almacenId: almacenId,
                },
            type: 'get',
            success: function(data) {
                $("#muestraVendedores").html(data);
            }
        });
    }

</script>

@endsection