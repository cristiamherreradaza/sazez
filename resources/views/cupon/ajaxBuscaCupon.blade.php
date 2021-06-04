@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
@if ($cuponClienteVerificado != null)
    <div class="card border-primary">
        <div class="card-header bg-primary">
            <h4 class="mb-0 text-white">
                CUPON ENCONTRADO
            </h4>
        </div>
        <div class="card-body" id="lista">
            <div class="table-responsive m-t-40">
                <table id="tabla-cupones" class="table table-striped table-bordered no-wrap">
                    <thead>
                        <tr>
                            <th>Promo</th>
                            <th>Cliente</th>
                            <th>Carnet</th>
                            <th>Fecha Registro</th>
                            <th>Fecha Expiracion</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($cuponClienteVerificado->producto_id == null)
                        <td>{{ $cuponClienteVerificado->combo->nombre }}</td>
                        @else
                        <td>{{ $cuponClienteVerificado->producto->nombre }}</td>
                        @endif
                        <td>{{ $cuponClienteVerificado->nombre }}</td>
                        <td>{{ $cuponClienteVerificado->ci }}</td>
                        <td>{{ $cuponClienteVerificado->fecha_creacion }}</td>
                            @if ($cuponClienteVerificado->estado == 'Expirado')
                                <td class="text-danger">{{ $cuponClienteVerificado->fecha_final }}</td>
                            @else
                                <td>{{ $cuponClienteVerificado->fecha_final }}</td>
                            @endif
                        <td>
                            @if ($cuponClienteVerificado->estado == 'Vigente')
                                <button onclick="cobrar({{ $cuponClienteVerificado->id }})" class="btn btn-primary" title="Cobrar cupon"><i class="fas fa-laptop"></i>
                                </button>
                                <button onclick="eliminar({{ $cuponClienteVerificado->id }})" class="btn btn-danger" title="Eliminar cupon"><i
                                        class="fas fa-trash-alt"></i></button>
                            @endif
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
@else
    <h3 class="text-center text-primary">NO EXISTE EL CUPON</h3>
@endif



{{-- modal para qr --}}
<div class="modal fade" id="modal-qr" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">QR PROMO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5><span class="text-info">PROMO: </span><span id="titulo-modal-qr"> </span> </h5>
                <h6><span class="text-info">URL: </span><span id="url-modal-qr"> </span> </h6>
                <canvas id="qr">

                </canvas>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- fin modal para qr --}}

<!-- Inicio modal cobro cupon -->
<div id="modal_cobro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="muestraCuponAjax">

    </div>
</div>
<!-- Fin modal cobro cupon -->

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('dist/js/qrious.min.js') }}"></script>

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

    function ver(cupon_id)
    {
        //"{{ url('Cupon/eliminar') }}/"+id;
        window.open('{{ url("Cupon/ver") }}/'+cupon_id, '_blank');
    }

    $(document).ready(function() {
        var table = $('#tabla-cupones').DataTable( {
            iDisplayLength: 10,
            // processing: true,
            // "scrollX": true,
            // serverSide: true,
            // ajax: "{{ url('Cupon/ajax_listado') }}",
            /*columns: [
                {data: 'producto_nombre', name: 'productos.nombre'},
                {data: 'combo_nombre', name: 'combos.nombre'},
                {data: 'tienda', name: 'almacenes.nombre'},
                {data: 'fecha_inicio', name: 'fecha_inicio'},
                {data: 'fecha_final', name: 'fecha_final'},
                {data: 'action'},
            ],*/
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        } );
    } );

    // Se efectua el cobro del cupon, por tanto se necesitara los datos del cupon(cupones) y los datos del cliente(users)
    // ademas de crear un nuevo registro de cupones_creados
    // function cobrar(id, cliente_id, nombre, ci, celular, email, nit, razon_social, producto_id, producto, stock, tienda, precio, descuento, promo)
    // {
    //     // Colocamos en modal los datos del cliente
    //     $("#cobro_cupon_id").val(id);
    //     $("#cobro_cliente_id").val(cliente_id);
    //     $("#cobro_nombre").val(nombre);
    //     $("#cobro_ci").val(ci);
    //     $("#cobro_celular").val(celular);
    //     $("#cobro_email").val(email);
    //     $("#cobro_nit").val(nit);
    //     $("#cobro_razon_social").val(razon_social);
    //     // Colocamos en modal los datos del producto
    //     $("#cobro_producto_id").val(producto_id);
    //     $("#cobro_producto").val(producto);
    //     $("#cobro_stock").val(stock);
    //     $("#cobro_tienda").val(tienda);
    //     $("#cobro_precio").val(precio);
    //     $("#cobro_descuento").val(descuento+' %');
    //     $("#cobro_promo").val(promo);
    //     // Colocamos en modal los datos la transaccion
    //     $("#cobro_total").val(promo);
    //     $("#cobro_efectivo").val(0);
    //     $("#cobro_cambio").val(0);
    //     $("#boton_compra").prop("disabled", true);
    //     // Desplegamos el modal de cobro
    //     $("#modal_cobro").modal('show');
    // }

    $(document).on('keyup change', '#cobro_efectivo', function () {
        let producto_id = $("#cobro_producto_id").val();
        let combo_id = $("#cobro_combo_id").val();

        let totalVenta = Number($("#cobro_total").val());
        let efectivo = Number($("#cobro_efectivo").val());
        let cambio = efectivo - totalVenta; 
        if (cambio > 0) {
            $("#cobro_cambio").val(cambio);
        }else{
            $("#cobro_cambio").val(0);
        }
        
        if(producto_id != null){
            //alert ('existe producto');
            //Validar que el boton se habilite una vez se efectue la compra
            //siempre que el stock sea 1 o mayor y el efectivo sea igual o mayor al totalVenta
            let stock = Number($("#cobro_stock").val());
            if (efectivo >= totalVenta && stock >= 1) {
                $("#boton_compra").prop("disabled", false);
            }else{
                $("#boton_compra").prop("disabled", true);
            }
        }else{
            let cantidad_productos = Number($("#cantidad_productos_promo").val());
            let valida = 1;     // 1 todo en orden, 0 producto con stock insuficiente
            let cantidad = 0;
            let stock = 0;
            for(i = 1; i <= cantidad_productos; i++) {
                cantidad = Number($("#cantidad_promo_producto-"+i).val());
                stock = Number($("#stock_promo_producto-"+i).val());
                if(stock < cantidad){
                    valida = 0;
                }
            }
            if(efectivo >= totalVenta && valida == 1){
                $("#boton_compra").prop("disabled", false);
            }else{
                $("#boton_compra").prop("disabled", true);
            }
        }
        //Validar que el boton se habilite una vez se efectue la compra
        //siempre que el stock sea 1 o mayor y el efectivo sea igual o mayor al totalVenta
        //let stock = Number($("#cobro_stock").val());
        //if (efectivo >= totalVenta && stock >= 1) {
        // if (efectivo >= totalVenta) {
        //     $("#boton_compra").prop("disabled", false);
        // }else{
        //     $("#boton_compra").prop("disabled", true);
        // }
    });

    function cobra_cupon()
    {
        let nombre = $("#cobro_nombre").val();
        let ci = $("#cobro_ci").val();

        if(nombre.length>0 && ci.length>0){
            Swal.fire(
                'Excelente!',
                'Cupón cobrado exitosamente.',
                'success'
            )
        }        
    }    

    // funcion no utilizada
    function guarda_cupon()
    {
        var producto_nombre = $("#producto_nombre").val();
        if(producto_nombre.length>0){
            Swal.fire(
                'Excelente!',
                'Una nuevo cupón fue registrado.',
                'success'
            )
        }
        //Abriendo el documento en otra pagina
        //window.open('{{ url("Cupon/prueba") }}', '_blank');
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
        if (termino_busqueda.length > 2) {
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

    function generaQr(promoId)
    {
        // codigo-qr-cupon
        $("#modal-qr").modal('show');
        let filaTabla=document.getElementById('boton-'+promoId).parentNode.parentNode;
        let producto = filaTabla.cells[0].innerHTML;
        let promo = filaTabla.cells[1].innerHTML;
        if(producto == ""){
            datosPromo = promo;
        }else{
            datosPromo = producto;
        }
        document.getElementById('titulo-modal-qr').innerHTML = datosPromo;
        document.getElementById('url-modal-qr').innerHTML = "{{ url('Cupon/ver') }}/"+promoId;

        var qr = new QRious({
            element: document.getElementById('qr'),
            size: 270,
            value: "{{ url('Cupon/ver') }}/"+promoId
        });

    }
</script>
<script src="{{ asset('assets/libs/moment/min/moment-with-locales.js') }}"></script>
<script
    src="{{ asset('assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}">
</script>
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