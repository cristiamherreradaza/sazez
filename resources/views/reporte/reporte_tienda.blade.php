@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div class="row">
        <div class="col-md-12">
            <div class="card border-info">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">REPORTE DE TIENDA</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha de Inicio</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="date" name="fecha_ini" id="fecha_ini" class="form-control" required>
                            </div>                    
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha Final</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                            </div>                    
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Seleccionar Tienda</label>
                                <select name="almacen_id" id="almacen_id" class="form-control">
                                    @if(auth()->user()->rol == 'Administrador')
                                        <option value="0">Todos</option>
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
                                {{-- <input type="date" name="fecha_final" id="fecha_final" class="form-control" required> --}}
                                <button type="button" onclick="consultar()"
                                    class="btn btn-block btn-primary">Buscar
                                </button>
                            </div>                    
                        </div>
                       {{--  <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">.</label>
                                <a href="{{ asset('excels/formato_productos_vacio.xlsx') }}" target="_blank" rel="noopener noreferrer">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-warning">Descargar formato excel</button>
                                </a>
                            </div>                    
                        </div> --}}
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

    <!-- Column -->

    <div class="col-md-12" id="mostrar" style="display:none;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">LISTA DE ENVIOS </h4>
                {{-- <div class="table-responsive m-t-40"> --}}
                <table id="tabla-tienda" class="table table-bordered table-striped no-wrap">
                    <thead>
                        <tr>
                            <th>Almacen</th>
                            <th>Tipo</th>
                            <th>Producto </th>
                            <th>Marca</th>
                            <th>Color</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Almacen</th>
                            <th>Tipo</th>
                            <th>Producto </th>
                            <th>Marca</th>
                            <th>Color</th>
                            <th>Stock</th>
                        </tr>
                  </tfoot>
                </table>
                {{-- </div> --}}
            </div>
        </div>
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>

<script>
    function consultar()
    {
        $("#mostrar").show('slow');
        var table = $('#tabla-tienda').DataTable();
        table.destroy();
        var almacen_id = $("#almacen_id").val();
        var fecha_ini = $("#fecha_ini").val();
        var fecha_fin = $("#fecha_fin").val();
        $("#tabla-tienda thead th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder=" ' + title + '" />');
          });

        // DataTable
        table = $('#tabla-tienda').DataTable( {
            "iDisplayLength": 10,
            "processing": true,
            "scrollX": true,
            "serverSide": true,
            "ajax": { 
                url : "{{ url('Reporte/ajax_tienda_listado') }}",
                type: "GET",
                data: {
                        "tipo_id" : almacen_id, "tipo_fecha_ini" : fecha_ini, "tipo_fecha_fin" : fecha_fin
                      } 
                },
            "columns": [
                {data: 'alma_nombre', name: 'alma_nombre'},
                {data: 'tipo_nombre', name: 'tipo_nombre'},
                {data: 'prod_nombre', name: 'prod_nombre'},
                {data: 'marc_nombre', name: 'marc_nombre'},
                {data: 'colores', name: 'colores'},
                {data: 'stock', name: 'stock'},
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