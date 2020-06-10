@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
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
                            <td>{{ $cupon->producto_id }}</td>
                            <td>{{ $cupon->cliente_id }}</td>
                            <td>{{ $cupon->almacene_id }}</td>
                            <td>{{ $cupon->estado }}</td>
                            <td>{{ $cupon->fecha_inicio }}</td>
                            <td>{{ $cupon->fecha_inicio }}</td>
                            <td>{{ $cupon->fecha_fin }}</td>
                            <td>
                                <button type="button" class="btn btn-warning" title="Editar cupon"  onclick="editar('{{ $cupon->id }}', '{{ $cupon->producto_id }}')"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Eliminar cupon"  onclick="eliminar('{{ $cupon->id }}', '{{ $cupon->producto_id }}')"><i class="fas fa-trash"></i></button>
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
    <div class="modal-dialog modal-lg">
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
                                <input name="termino" type="text" id="termino" class="form-control">
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
                                    <input name="producto_descuento" type="number" id="producto_descuento" class="form-control" value="1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Total</label>
                                    <input name="producto_total" type="number" id="producto_total" class="form-control" value="0">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control">
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"> {{ $cliente->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                <input name="fecha_inicio" type="date" id="fecha_inicio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Fecha Fin</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="fecha_fin" type="date" id="fecha_fin" class="form-control" required>
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

<!-- inicio modal editar cupon -->
<!-- <div id="editar_marcas" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EDITAR CUP&Oacute;N</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Marca/actualizar') }}"  method="POST" >
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre" type="text" id="nombre" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualiza_marca()">ACTUALIZAR MARCA</button>
                </div>
            </form>
        </div>
    </div>
</div> -->
<!-- fin modal editar cupon -->

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

    function editar(id, nombre)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#editar_marcas").modal('show');
    }

    function actualiza_marca()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        if(nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Marca actualizada correctamente.',
                'success'
            )
        }
    }

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
                    'La marca fue eliminada',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Marca/eliminar') }}/"+id;
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
</script>
@endsection
