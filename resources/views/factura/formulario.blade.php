@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-info">
            <div class="card-header bg-info">
                <h4 class="mb-0 text-white">PRODUCTOS</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Nit</label>
                            <input type="text" name="codigo" id="codigo" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Razon Social / nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Producto</label>
                            <input type="text" name="producto" id="producto" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control" min="1">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Precio</label>
                            <input type="number" name="precio" id="precio" class="form-control" min="1" step="any">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Subtotal</label>
                            <input type="text" name="subtotal" id="subtotal" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="button" onclick="buscar()" class="btn btn-block btn-success">Buscar</button>
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

<div class="row">
    <div class="col-md-12" id="mostrar" style="display:none;">
    </div>
</div>

<!-- inicio modal reportar producto -->
<div id="reportar_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-dark">
                <h4 class="modal-title text-white" id="myModalLabel">REPORTAR PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Movimiento/reportar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_producto_a_reportar" id="id_producto_a_reportar" value="">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_producto_a_reportar" type="text" id="nombre_producto_a_reportar"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cantidad</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="cantidad_producto_a_reportar" type="number"
                                    id="cantidad_producto_a_reportar" min="1" class="form-control" value="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="descripcion_producto_a_reportar" type="text"
                                    id="descripcion_producto_a_reportar" minlength="4" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-dark"
                        onclick="reportar()">ENVIAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal reportar producto -->

<!-- inicio modal informacion producto -->
<div id="detalle_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">DETALLE PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body" id="ajaxDetalleProducto">

            </div>
        </div>
    </div>
</div>
<!-- fin modal informacion producto -->

<!-- inicio modal habilitar producto -->
<div id="habilitar_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title text-white" id="myModalLabel">HABILITAR PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Movimiento/habilitar') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_producto_a_habilitar" id="id_producto_a_habilitar" value="">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="nombre_producto_a_habilitar" type="text" id="nombre_producto_a_habilitar"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cantidad</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="cantidad_producto_a_habilitar" type="number"
                                    id="cantidad_producto_a_habilitar" min="1" max="" class="form-control" value="1"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="descripcion_producto_a_habilitar" type="text"
                                    id="descripcion_producto_a_habilitar" minlength="4" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success"
                        onclick="habilitar()">ENVIAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal habilitar producto -->

@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function buscar()
    {
        var codigo = $("#codigo").val();
        var nombre = $("#nombre").val();
        var tipo = $("#tipo").val();
        var marca = $("#marca").val();
        var estado = $("#estado").val();
        $.ajax({
            url: "{{ url('Producto/ajax_listado') }}",
            data: {
                codigo: codigo,
                nombre: nombre,
                tipo: tipo,
                marca: marca,
                estado: estado
                },
            type: 'get',
            success: function(data) {
                $("#mostrar").html(data);
                $("#mostrar").show('slow');
            }
        });
    }

    function edita_producto(producto_id)
    {
        window.location.href = "{{ url('Producto/edita') }}/" + producto_id;
    }

    function muestra_producto(producto_id)
    {
        window.location.href = "{{ url('Producto/muestra') }}/" + producto_id;
    }

    function elimina_producto(id, nombre)
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
                    'El producto fue eliminada',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Producto/elimina') }}/"+id;
                });
            }
        })
    }

    function reporta_producto(id_producto, nombre)
    {
        $("#id_producto_a_reportar").val(id_producto);
        $("#nombre_producto_a_reportar").val(nombre);
        $("#reportar_producto").modal('show');
    }

    function reportar()
    {
        //var cantidad = $("#cantidad_producto_a_reportar").val();
        var descripcion = $("#descripcion_producto_a_reportar").val();
        if(descripcion.length>3){
            Swal.fire(
                'Excelente!',
                'Producto reportado correctamente.',
                'success'
            )
        }
    }

    function habilita_producto(id_producto, nombre)
    {
        $("#id_producto_a_habilitar").val(id_producto);
        $("#nombre_producto_a_habilitar").val(nombre);
        $("#habilitar_producto").modal('show');
    }

    function habilitar()
    {
        //var cantidad = $("#cantidad_producto_a_habilitar").val();
        var descripcion = $("#descripcion_producto_a_habilitar").val();
        if(descripcion.length>3){
            Swal.fire(
                'Excelente!',
                'Producto habilitado correctamente.',
                'success'
            )
        }
    }

    function discontinua_producto(id, nombre)
    {
        Swal.fire({
            title: 'Quieres discontinuar ' + nombre + '?',
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
                    'El producto ha sido discontinuado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Producto/discontinua') }}/"+id;
                });
            }
        })
    }

    function continua_producto(id, nombre)
    {
        Swal.fire({
            title: 'Quieres cambiar a continuo a ' + nombre + '?',
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
                    'El producto vuelve a ser continuo',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('Producto/continua') }}/"+id;
                });
            }
        })
    }

    function muestraInformacion(productoId)
    {
        $.ajax({
            url: "{{ url('Producto/ajaxInformacion') }}",
            data: {producto_id: productoId},
            type: 'POST',
            success: function(data) {
                $("#ajaxDetalleProducto").html(data);
            }
        });

        // $("#danger-header-modal").modal("show");

        $("#detalle_producto").modal("show");
        //ajaxMuestraTotalesAlmacenes
    }

</script>

@endsection