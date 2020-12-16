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
                <h4 class="mb-0 text-white">REPORTE DE TRANSFERENCIAS DE PRODUCTOS ENTRE SUCURSALES</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Fecha de inicio</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Fecha final</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_final" id="fecha_final" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>De </label>
                            <select name="almacen_origen_id" id="almacen_origen_id" class="form-control">
                                @if(auth()->user()->rol == 'Administrador')
                                    <option value="" selected>Todos</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ auth()->user()->almacen->id }}">{{ auth()->user()->almacen->nombre }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>A </label>
                            <select name="almacen_destino_id" id="almacen_destino_id" class="form-control">
                            <option value="" selected>Todos</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="button" onclick="buscar()" class="btn btn-block btn-primary">Buscar</button>
                        </div>                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="listadoProductosAjax"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12" id="mostrar" style="display:none;">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">LISTA DE TRANSFERENCIAS ENTRE SUCURSALES</h4>
            <table id="tabla-tienda" class="table table-bordered table-striped no-wrap">
                <thead>
                    <tr>
                        <th>Id Transferencia</th>
                        <th>Tienda Salida</th>
                        <th>Tienda Llegada</th>
                        <th>Usuario</th>
                        <th>Codigo Producto</th>
                        <th>Nombre Producto</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Id Transferencia</th>
                        <th>Tienda Salida</th>
                        <th>Tienda Llegada</th>
                        <th>Usuario</th>
                        <th>Codigo Producto</th>
                        <th>Nombre Producto</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                    </tr>
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

    var oldExportAction = function (self, e, dt, button, config) {
        if (button[0].className.indexOf('buttons-excel') >= 0) {
            if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
            } else {
                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            }
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
    };

    var newExportAction = function (e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;

        dt.one('preXhr', function (e, s, data) {
            // Just this once, load all data from the server...
            data.start = 0;
            data.length = 2147483647;

            dt.one('preDraw', function (e, settings) {
                // Call the original action function 
                oldExportAction(self, e, dt, button, config);

                dt.one('preXhr', function (e, s, data) {
                    // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                    // Set the property to what it was before exporting.
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });

                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);

                // Prevent rendering of the full data to the DOM
                return false;
            });
        });

        // Requery the server with the new one-time export settings
        dt.ajax.reload();
    };

    function buscar()
    {
        $("#mostrar").show('slow');
        var table = $('#tabla-tienda').DataTable();
        table.destroy();
        var fecha_inicial = $("#fecha_inicial").val();
        var fecha_final = $("#fecha_final").val();
        var almacen_origen_id = $("#almacen_origen_id").val();
        var almacen_destino_id = $("#almacen_destino_id").val();

        $("#tabla-tienda thead th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder=" ' + title + '" />');
        });

        // DataTable
        table = $('#tabla-tienda').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    title: 'Listado de Entregas',
                    action: newExportAction
                },                
            ],
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: { 
                url : "{{ url('Reporte/ajaxTransferenciasListado') }}",
                type: "GET",
                data: {
                    fecha_inicial : fecha_inicial,
                    fecha_final : fecha_final,
                    almacen_origen_id : almacen_origen_id,
                    almacen_destino_id : almacen_destino_id
                    } 
                },
            columns: [
                {data: 'nro_transferencia', name: 'movimientos.id'},
                {data: 'tienda_salida', name: 'origen.nombre'},
                {data: 'tienda_llegada', name: 'almacenes.nombre'},
                {data: 'usuario', name: 'users.name'},
                {data: 'codigo', name: 'productos.codigo'},
                {data: 'producto_nombre', name: 'productos.nombre'},
                {data: 'cantidad', name: 'movimientos.ingreso'},
                {data: 'fecha', name: 'movimientos.fecha'},
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
</script>

@endsection