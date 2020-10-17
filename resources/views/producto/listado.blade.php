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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Codigo</label>
                            <input type="text" name="codigo" id="codigo" class="form-control">
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>                    
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Seleccionar Tipo</label>
                            <select name="tipo" id="tipo" class="form-control">
                            <option value="" selected>Todos</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}"> {{ $tipo->nombre }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Seleccionar Marca</label>
                            <select name="marca" id="marca" class="form-control">
                            <option value="" selected>Todos</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}"> {{ $marca->nombre }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" id="estado" class="form-control">
                                <option value="Bueno" Selected> Bueno </option>
                                <option value="Defectuoso"> Defectuoso </option>
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
            <form action="{{ url('Movimiento/reportar') }}"  method="POST" />
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
                                <input name="nombre_producto_a_reportar" type="text" id="nombre_producto_a_reportar" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cant.</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="cantidad_producto_a_reportar" type="number" id="cantidad_producto_a_reportar" min="1" class="form-control" value="1" required>
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
                                <input name="descripcion_producto_a_reportar" type="text" id="descripcion_producto_a_reportar" minlength="4" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-dark" onclick="reportar()">ENVIAR</button>
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

{{-- modal genera qr --}}
<div id="genera_qr" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-dark">
                <h4 class="modal-title text-white" id="myModalLabel">GENERADOR DE QR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
            <div class="modal-body" id="ajaxGeneraQr">

            </div>
        </div>
    </div>
</div>
{{-- fin modal genera qr --}}

<!-- inicio modal adiciona producto -->
<div id="adiciona_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title text-white" id="myModalLabel">ADICIONA PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
                <form action="{{ url('Producto/adicionaRegularizacion') }}" method="POST" id="formularioAdicionaProducto" />
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="regularizaAdicionProductoId" id="regularizaAdicionProductoId" value="">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cant.</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="adicionaCantidad" type="number" id="adicionaCantidad" min="1" class="form-control" value="1" required>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="descripcion" type="text" id="descripcion" class="form-control" required>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="valida_adiciona()">ADICIONAR</button>
                </div>
                </form>
        </div>
    </div>
</div>
<!-- fin modal adiciona producto -->

{{-- inicio modal quita producto --}}
<div id="quita_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-danger">
                <h4 class="modal-title text-white" id="myModalLabel">QUITA PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </div>
                <form action="{{ url('Producto/quitaRegularizacion') }}" method="POST" id="formularioQuitaProducto" />
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="regularizaQuitaProductoId" id="regularizaQuitaProductoId" value="">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cant.</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="quitaCantidad" type="number" id="quitaCantidad" min="1" class="form-control" value="1" required>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="descripcion" type="text" id="descripcion" class="form-control" required>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn waves-effect waves-light btn-block btn-danger" onclick="valida_quita()">QUITAR</button>
                </div>
                </form>
        </div>
    </div>
</div>
{{-- fin inicio modal quita producto --}}

<!-- inicio modal habilitar producto -->
<div id="habilitar_producto" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title text-white" id="myModalLabel">HABILITAR PRODUCTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('Movimiento/habilitar') }}"  method="POST" >
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
                                <input name="nombre_producto_a_habilitar" type="text" id="nombre_producto_a_habilitar" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cantidad</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="cantidad_producto_a_habilitar" type="number" id="cantidad_producto_a_habilitar" min="1" max="" class="form-control" value="1" required>
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
                                <input name="descripcion_producto_a_habilitar" type="text" id="descripcion_producto_a_habilitar" minlength="4" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="habilitar()">ENVIAR</button>
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

    function adiciona_producto(productoId)
    {
        $("#regularizaAdicionProductoId").val(productoId);
        $("#adiciona_producto").modal("show");
    }

    function quita_producto(productoId)
    {
        $("#regularizaQuitaProductoId").val(productoId);
        $("#quita_producto").modal("show");
    }

    function genera_qr(productoId)
    {
        $("#genera_qr").modal("show");
        
        $.ajax({
            url: "{{ url('Producto/ajaxGeneraQr') }}",
            data: {producto_id: productoId},
            type: 'POST',
            success: function(data) {
                $("#ajaxGeneraQr").html(data);
            }
        });

    }

    function valida_adiciona(productoId)
    {
        if ($("#formularioAdicionaProducto")[0].checkValidity()) {
            $("#formularioAdicionaProducto").submit();
        }else{
            $("#formularioAdicionaProducto")[0].reportValidity();
        }
    }

    function valida_quita(productoId)
    {
        if ($("#formularioQuitaProducto")[0].checkValidity()) {
            $("#formularioQuitaProducto").submit();
        }else{
            $("#formularioQuitaProducto")[0].reportValidity();
        }
    }

</script>

@endsection