@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">

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
                <h4 class="mb-0 text-white">REPORTE DE PRODUCTOS EN TIENDA</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Fecha</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}"required>
                        </div>                    
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sucursal</label>
                            <select name="almacen_id" id="almacen_id" class="form-control">
                                @if(auth()->user()->rol == 'Administrador')
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ auth()->user()->almacen->id }}">{{ auth()->user()->almacen->nombre }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo_id" id="tipo_id" class="form-control">
                                <option value="todos" selected>Todos</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-2">
                        <div class="form-group">
                            <label>Productos Continuos</label>
                            <select name="continuo" id="continuo" class="form-control">
                                    <option value="" selected>Si</option>
                                    <option value="No">No</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="button" onclick="buscar()" class="btn btn-block btn-primary" id="btnBuscar">Buscar</button>
                            <button class="btn btn-primary btn-block" type="button" id="btnTrabajando" disabled style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                &nbsp;&nbsp;Estamos trabajando, ten paciencia ;-)
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

<div class="col-md-12" id="mostrar" style="display:none;">
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
</div>

@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>

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
    
    
    function buscar()
    {
        var fecha = $("#fecha").val();
        var almacen_id = $("#almacen_id").val();
        var tipo_id = $("#tipo_id").val();
         $("#btnBuscar").hide();
         $("#btnTrabajando").show();
        // var continuo = $("#continuo").val();
        $.ajax({
            url: "{{ url('Reporte/ajax_listado_saldos') }}",
            data: {
                fecha: fecha,
                almacen_id: almacen_id,
                tipo_id: tipo_id,
                // continuo: continuo
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
    

    
    /*function buscar()
    {
        $("#mostrar").show('slow');
        var table = $('#tabla-tienda').DataTable();
        table.destroy();
        var fecha = $("#fecha").val();
        var almacen_id = $("#almacen_id").val();
        var tipo_id = $("#tipo_id").val();

        $("#tabla-tienda thead th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder=" ' + title + '" />');
          });

        // DataTable
        table = $('#tabla-tienda').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: { 
                url : "{{ url('Reporte/ajax_listado_saldos') }}",
                type: "GET",
                data: {
                    fecha : fecha,
                    almacen_id : almacen_id,
                    tipo_id : tipo_id
                    } 
                },
            columns: [
                {data: 'almacen_nombre', name: 'almacenes.nombre'},
                {data: 'tipo_nombre', name: 'productos.tipo_id'},
                {data: 'producto_nombre', name: 'productos.nombre'},
            ],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        } );

        table.columns().every(function(index) {
            var that = this;

            $("input", this.header()).on("keyup change clear", function() {
              if (that.search() !== this.value) {
                that.search(this.value).draw();
                table
                  .rows()
                  .$("tr", { filter: "applied" })
                  .each(function() {
                    // console.log(table.row(this).data());
                  });
              }
            });
          });
    }
    */
    
</script>

@endsection