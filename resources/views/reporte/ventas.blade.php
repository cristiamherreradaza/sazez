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
                <h4 class="mb-0 text-white">REPORTE DE VENTAS</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Fecha de inicio</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" required>
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Fecha final</label>
                            <span class="text-danger">
                                <i class="mr-2 mdi mdi-alert-circle"></i>
                            </span>
                            <input type="date" name="fecha_final" id="fecha_final" class="form-control" required>
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Seleccionar Tienda</label>
                            <select name="almacen_id" id="almacen_id" class="form-control">
                            <option value="" selected>Todos</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Seleccionar Vendedor</label>
                            <select name="usuario_id" id="usuario_id" class="form-control">
                            <option value="" selected>Todos</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}"> {{ $usuario->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Incluir Deudores</label>
                            <select name="deudores" id="deudores" class="form-control">
                            <option value="" selected>Todos</option>
                                <option value="Si"> Si </option>
                                <option value="No"> No </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
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
            <h4 class="card-title">LISTA DE VENTAS </h4>
            <table id="tabla-tienda" class="table table-bordered table-striped no-wrap">
                <thead>
                    <tr>
                        <th>Nro venta</th>
                        <th>Tienda</th>
                        <th>Usuario </th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nro venta</th>
                        <th>Tienda</th>
                        <th>Usuario </th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Saldo</th>
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
        // var almacen_id = $("#almacen_id").val();
        // var fecha_ini = $("#fecha_ini").val();
        // var fecha_fin = $("#fecha_fin").val();


        var fecha_inicial = $("#fecha_inicial").val();
        var fecha_final = $("#fecha_final").val();
        var almacen_id = $("#almacen_id").val();
        var usuario_id = $("#usuario_id").val();
        var deudores = $("#deudores").val();

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
            ajax: { 
                url : "{{ url('Reporte/ajaxVentasListado') }}",
                type: "GET",
                data: {
                    fecha_inicial : fecha_inicial,
                    fecha_final : fecha_final,
                    almacen_id : almacen_id,
                    usuario_id : usuario_id,
                    deudores : deudores
                    } 
                },
            columns: [
                {data: 'nro_venta', name: 'ventas.id'},
                {data: 'tienda', name: 'almacenes.nombre'},
                {data: 'usuario_nombre', name: 'users.name'},
                {data: 'cliente_nombre', name: 'clientes.name'},
                {data: 'fecha', name: 'ventas.fecha'},
                {data: 'monto', name: 'ventas.total'},
                {data: 'saldo', name: 'ventas.saldo'},
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