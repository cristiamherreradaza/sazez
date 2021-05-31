@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
@endsection

@section('content')

<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <!-- Column -->
    <div class="col-md-12">
        <!-- Row -->
        <form action="{{ url('Cupon/guardar') }}" method="post" id="formularioCupon">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-info">
                        <div class="card-header bg-info">
                            <h4 class="mb-0 text-white">NUEVO CUP&Oacute;N</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Tipo de Oferta</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <select name="tipo_oferta" id="tipo_oferta" class="form-control" required>
                                        <option value="">Seleccione</option>
                                        <option value="1">Cupon por Producto</option>
                                        <option value="2">Cupon por Promocion</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Fecha Inicio</label>
                                        <span class="text-danger">
                                            <i class="mr-2 mdi mdi-alert-circle"></i>
                                        </span>
                                        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Fecha Fin</label>
                                        <span class="text-danger">
                                            <i class="mr-2 mdi mdi-alert-circle"></i>
                                        </span>
                                        <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Tienda</label>
                                        <select name="tienda" id="tienda" class="form-control">
                                            <option value="">Todas las tiendas</option>
                                            @foreach($almacenes as $almacen)
                                                <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="oculta_detalle">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Producto</label>
                                        <input name="termino" type="text" id="termino" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="detalle_promocion">
                                <div class="col-md-12">
                                    <label class="control-label">Seleccione promoción</label>
                                    <select name="promocion" id="promocion" class="form-control" onchange="validaFechaPromo()">
                                        <option value="" selected></option>
                                        @foreach($promociones as $promocion)
                                            <option value="{{ $promocion->id }}" data-fechai="{{ $promocion->fecha_inicio }}" data-fechaf="{{ $promocion->fecha_final }}">{{ $promocion->nombre }} (Fecha Inicio: {{ $promocion->fecha_inicio }} - Fecha Final: {{ $promocion->fecha_final }})</option>
                                        @endforeach
                                    </select>
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
                                            <input name="producto_descuento" type="number" id="producto_descuento" class="form-control" min="0" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Total</label>
                                            <input name="producto_total" type="number" id="producto_total" class="form-control" min="0" step="any">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="listadoProductosAjax">

                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" id="guarda_cupon" onclick="validaItems()">GUARDAR CUP&Oacute;N</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Row -->
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/forms/select2/select2.init.js') }}"></script>
<script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/libs/moment/min/moment-with-locales.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}"></script>
<script>
    // $('#fecha_inicio').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', minDate: new Date(), lang: 'es' });
    // let calendarioFinal = $('#fecha_fin').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm', minDate: new Date(), maxDate: "2020-08-26 23:59:59", lang: 'es' });

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
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // generamos los tabs
    $('#tabsProductos div .btn').click(function () {
        var t = $(this).attr('id');

        if ($(this).hasClass('inactivo')) { //preguntamos si tiene la clase inactivo 
            $('#tabsProductos div .btn').addClass('inactivo');
            $(this).removeClass('inactivo');

            $('.tabContenido').hide();
            $('#' + t + 'C').fadeIn('slow');
        }
    });
    
    //Funcion de habilitar y deshabilitar clientes y emails en base al select
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

    //Funcion para colocar todos los valores en ""
    $( function() {
        //$("#guarda_cupon").prop("disabled", true);
        $("#tipo_oferta").val("");
        $("#fecha_inicio").val("");
        $("#fecha_fin").val("");
        $("#tienda").val("");
        $("#termino").val("");
        $("#producto_id").val("");
        $("#producto_nombre").val("");
        $("#producto_precio").val(0);
        $("#producto_descuento").val(0);
        $("#producto_total").val(0);
        $("#promocion").val("");
        $("#tipo_envio").val("");
        $("#cliente").val("");
        $("#email").val("");
        $(".cajas").prop("checked", false);
    });

    //Funcion para ocultar/mostrar datos de cupon/promocion
    $( function() {
        $("#oculta_detalle").hide();
        $("#detalle_promocion").hide();
        $("#tipo_oferta").change( function() {
            if($(this).val() == "") {
                $("#termino").val("");
                $("#producto_id").val("");
                $("#producto_nombre").val("");
                $("#producto_precio").val(0);
                $("#producto_descuento").val(0);
                $("#producto_total").val(0);
                $("#promocion").val("");
                
                
                //$("#guarda_cupon").prop("disabled", true);
                $("#detalle_promocion").hide();
                $("#listadoProductosAjax").hide();
                $("#oculta_detalle").hide();
                $("#muestra_detalle").hide();
            }
            if ($(this).val() == "1") {
                $("#detalle_promocion").hide();
                $("#oculta_detalle").show();
                //$("#guarda_cupon").prop("disabled", false);
            }
            if ($(this).val() == "2") {
                $("#oculta_detalle").hide();
                $("#muestra_detalle").hide();
                $("#detalle_promocion").show();
            }
        });
    });

    // Funcion para ocultar datos de promocion
    function validaItems()
    {   
        if ($("#formularioCupon")[0].checkValidity()) {
            $("#formularioCupon").submit();
            Swal.fire(
                'Excelente!',
                'Procesando cupon...',
                'success'
            )
        }else{
            $("#formularioCupon")[0].reportValidity();
        }

        // if(($("#tipo_envio").val().length!=0 && ($("#cliente").val().length!=0 || $("#email").val().length!=0)) || ($(".cajas").is(':checked')) && ($("#producto_id").val().length!=0 || $("#promocion").val().length!=0))
        // {
        //     Swal.fire(
        //         'Excelente!',
        //         'Procesando cupon...',
        //         'success'
        //     )
        //     swal.showLoading();
        // }
        // else
        // {
        //     Swal.fire({
        //         type: 'error',
        //         title: 'Oops...',
        //         text: 'Tienes que completar datos en el formulario!'
        //     })
        //     event.preventDefault();
        // }

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
        total = Math.round(total);
        //alert(total);
        $("#producto_total").val(total);
        // sumaSubTotales();
    });

    // funcion para validar las fechas y las horas de las promociones
    function validaFechaPromo()
    {
        let fechaInicioPromocion = $("#promocion").find(':selected').data('fechai');
        let fechaFinalPromocion = $("#promocion").find(':selected').data('fechaf');
        let fechaHoraInicio = fechaInicioPromocion+"T00:00";
        let fechaHoraFinal = fechaFinalPromocion+"T00:00";
        $("#fecha_inicio").attr({"min": fechaHoraInicio, "max": fechaHoraFinal});
        $("#fecha_fin").attr({"min": fechaHoraInicio, "max": fechaHoraFinal});
    }

</script>

@endsection