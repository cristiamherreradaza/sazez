@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">
            CUPONES &nbsp;&nbsp;
            <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" onclick="nuevo_cupon()"><i class="fas fa-plus"></i> &nbsp; NUEVO CUP&Oacute;N</button>
        </h4>
    </div>
    <div class="card-body" id="lista">
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cup&oacute;n</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Tienda</th>
                        <th>Cobrado</th>
                        <th>Fecha Cobro</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cupones as $key => $cupon)
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $cupon->codigo }}</td>
                            <td>{{ $cupon->producto->nombre }}</td>
                            <td>{{ $cupon->user->name }}</td>
                            <td>
                                @if($cupon->almacen)
                                    {{ $cupon->almacen->nombre }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $cobrado = App\CuponesCobrado::where('cupone_id', $cupon->id)
                                                ->first();
                                @endphp
                                @if($cobrado)
                                    Si
                                @else
                                    No
                                @endif
                            </td>
                            <td>
                                @if($cobrado)
                                    {{ $cobrado->fecha }}
                                @endif
                            </td>
                            <td>{{ $cupon->fecha_inicio }}</td>
                            <td>{{ $cupon->fecha_final }}</td>
                            <td>
                                <button type="button" class="btn btn-danger" title="Eliminar cupon"  onclick="eliminar('{{ $cupon->id }}')"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- inicio modal cupon nuevo -->
<div id="modal_cupones" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO CUP&Oacute;N</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Cupon/guardar') }}" method="POST" >
                @csrf
                <div class="modal-body">

                    <div class="row" id="oculta_detalle">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Producto</label>
                                <input name="termino" type="text" id="termino" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div id="muestra_detalle" style="display: none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" name="producto_id" id="producto_id" value="">
                                    <label class="control-label">Producto</label>
                                    <input name="producto_nombre" type="text" id="producto_nombre" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Precio</label>
                                    <input name="producto_precio" type="text" id="producto_precio" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Descuento</label>
                                    <input name="producto_descuento" type="number" id="producto_descuento" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Total</label>
                                    <input name="producto_total" type="number" id="producto_total" class="form-control" step="any">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="listadoProductosAjax"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label">Medio a enviar</label>
                            <select name="tipo_envio" id="tipo_envio" class="form-control" required>
                                <option value="" selected></option>
                                <option value="1">Cliente</option>
                                <option value="2">Correo Electrónico</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control">
                                    <option value="" selected></option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"> {{ $cliente->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <input name="email" type="email" id="email" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Tienda</label>
                                <select name="tienda" id="tienda" class="form-control">
                                    <option value="" selected></option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Fecha Inicio</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Inicio" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Fecha Fin</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Fin" id="fecha_fin" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="guarda_cupon()">GUARDAR CUP&Oacute;N</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal cupon nuevo -->

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });

    $(function () {
        $('#datetimepicker1').datetimepicker();
    });

    $( function() {
        $("#cliente").prop("disabled", true);
        $("#email").prop("disabled", true);
        $("#tipo_envio").val("");

        $("#tipo_envio").change( function() {
            if ($(this).val() == "1") {
                $("#cliente").prop("disabled", false);
                $("#email").prop("disabled", true);
            }
            if ($(this).val() == "2") {
                $("#cliente").prop("disabled", true);
                $("#email").prop("disabled", false);
            }
        });
    });
</script>
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function nuevo_cupon()
    {
        $("#modal_cupones").modal('show');
    }

    // funcion no utilizada
    function guarda_cupon()
    {
        var nombre_producto = $("#nombre_producto").val();
        if(nombre_producto.length>0){
            Swal.fire(
                'Excelente!',
                'Una nuevo cupón fue registrado.',
                'success'
            )
        }
    }

    function eliminar(id)
    {
        Swal.fire({
            title: 'Quieres borrar este cupón?',
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
                    'El cupón fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Cupon/eliminar') }}/"+id;
                });
            }
        })
    }

    $(document).on('keyup', '#termino', function(e) {
        termino_busqueda = $('#termino').val();
        if (termino_busqueda.length > 3) {
            $.ajax({
                url: "{{ url('Cupon/ajaxBuscaProducto') }}",
                data: {termino: termino_busqueda},
                type: 'POST',
                success: function(data) {
                    $("#listadoProductosAjax").show('slow');
                    $("#listadoProductosAjax").html(data);
                }
            });
        }

    });

    $(document).on('keyup change', '#producto_descuento', function(e){
        let descuento = Number($(this).val())/100;
        //alert(descuento);
        // let id = $(this).data("id");
        let precio = Number($("#producto_precio").val());
        //alert(precio);

        let total = precio - (precio*descuento);
        //alert(total);
        $("#producto_total").val(total);
        // sumaSubTotales();
    });



</script>
    <script src="{{ asset('assets/libs/moment/min/moment-with-locales.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}"></script>
    <script>
    $('#fecha_inicio').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', minDate: new Date(), lang: 'es' });
    $('#fecha_fin').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', minDate: new Date(), lang: 'es' });

    // MAterial Date picker    
    $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    $('#timepicker').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
    $('#date-format').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });

    $('#min-date').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm', minDate: new Date() });
    $('#date-fr').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm', lang: 'fr', weekStart: 1, cancelText: 'ANNULER' });
    $('#date-end').bootstrapMaterialDatePicker({ weekStart: 0 });
    $('#date-start').bootstrapMaterialDatePicker({ weekStart: 0 }).on('change', function(e, date) {
        $('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
    });
    </script>
@endsection
