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
                <h4 class="mb-0 text-white">REPORTE DE FACTURAS</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Fecha de inicio</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" required>
                        </div>                    
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Fecha final</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_final" id="fecha_final" class="form-control" required>
                        </div>                    
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Seleccionar Tienda</label>
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
                            <label class="control-label">&nbsp;</label>
                            <button type="button" onclick="buscar()" class="btn btn-block btn-primary">Buscar</button>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12" id="mostrar" style="display:none;">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">LISTA DE FACTURAS EMITIDAS</h4>
            <table id="tabla-tienda" class="table table-bordered table-striped no-wrap">
                <thead>
                    <tr>
                        <th>Tienda</th>
                        <th>Cliente</th>
                        <th>Nro Factura</th>
                        <th>Nit Cliente</th>
                        <th>Fecha Compra</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Tienda</th>
                        <th>Cliente</th>
                        <th>Nro Factura</th>
                        <th>Nit Cliente</th>
                        <th>Fecha Compra</th>
                        <th>Monto</th>
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
    function buscar()
    {
        $("#mostrar").show('slow');
        var table = $('#tabla-tienda').DataTable();
        table.destroy();
        var fecha_inicial = $("#fecha_inicial").val();
        var fecha_final = $("#fecha_final").val();
        var almacen_id = $("#almacen_id").val();

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
                url : "{{ url('Factura/ajax_listado') }}",
                type: "GET",
                data: {
                    fecha_inicial : fecha_inicial,
                    fecha_final : fecha_final,
                    almacen_id : almacen_id
                    } 
                },
            columns: [
                {data: 'tienda', name: 'almacenes.nombre'},
                {data: 'cliente', name: 'users.name'},
                {data: 'numero_factura', name: 'facturas.numero_factura'},
                {data: 'nit_cliente', name: 'facturas.nit_cliente'},
                {data: 'fecha_compra', name: 'facturas.fecha_compra'},
                {data: 'monto_compra', name: 'facturas.monto_compra'},
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