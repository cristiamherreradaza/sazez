@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/lobilist.css') }}">
<link rel="stylesheet" href="{{ asset('assets/extra-libs/taskboard/css/jquery-ui.min.css') }}">
@endsection

@section('content')
<div class="card border-info">
    <div class="card-header bg-info">
        <h4 class="mb-0 text-white">COBRO DE CUPON</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12"><h4><span class="text-danger">Datos del comprador</span></h4></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ url('Cupon/cobrar') }}" method="POST" >
                    @csrf
                    <input type="hidden" name="cobro_cupon_id" id="cobro_cupon_id" value="{{ $cupon->id }}">
                    <input type="hidden" name="cobro_cliente_id" id="cobro_cliente_id" value="{{ $cupon->cliente_id }}">
                    @if($cupon->producto_id)
                        <input type="hidden" name="cobro_producto_id" id="cobro_producto_id" value="{{ $cupon->producto_id }}">
                    @else
                        <input type="hidden" name="cobro_combo_id" id="cobro_combo_id" value="{{ $cupon->combo_id }}">
                    @endif
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="cobro_nombre" type="text" id="cobro_nombre" value="{{ $cupon->cliente->name }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Cedula de Identidad</label>
                                <input name="cobro_ci" type="text" id="cobro_ci" value="{{ $cupon->cliente->ci }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Celulares</label>
                                <input name="cobro_celular" type="text" id="cobro_celular" value="{{ $cupon->cliente->celulares }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Correo Electrónico</label>
                                <input name="cobro_email" type="email" id="cobro_email" value="{{ $cupon->cliente->email }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nit</label>
                                <input name="cobro_nit" type="text" id="cobro_nit" value="{{ $cupon->cliente->nit }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Razon Social</label>
                                <input name="cobro_razon_social" type="text" id="cobro_razon_social" value="{{ $cupon->cliente->razon_social }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12"><h4><span class="text-danger">Datos de la Sucursal</span></h4></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Tienda</label>
                                        <input name="cobro_tienda" type="text" id="cobro_tienda" value="{{ auth()->user()->almacen->nombre }}" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            @if($cupon->producto_id != null)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Producto</label>
                                            <input name="cobro_producto" type="text" id="cobro_producto" value="{{ $cupon->producto->nombre }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Stock</label>
                                            @php
                                                $stock = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                                                                        ->where('producto_id', $cupon->producto_id)
                                                                        ->where('almacene_id', auth()->user()->almacen_id)
                                                                        ->first();
                                                $stock=intval($stock->total);
                                            @endphp
                                            <input name="cobro_stock" type="text" id="cobro_stock" value="{{ $stock }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Precio</label>
                                            @php
                                                $precio = App\Precio::where('producto_id', $cupon->producto_id)
                                                                    ->where('escala_id', 1)
                                                                    ->first();
                                                $precio = $precio->precio;
                                            @endphp
                                            <input name="cobro_precio" type="text" id="cobro_precio" value="{{ $precio }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Descuento</label>
                                            <input name="cobro_descuento" type="text" id="cobro_descuento" value="{{ $cupon->descuento }} %" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Total</label>
                                            @php
                                                dd($precioTotalCombo);
                                            @endphp
                                            @if ($cupon->combo_id != null)
                                                <input name="cobro_promo" type="text" id="cobro_promo" value="{{ $precioTotalCombo }}" class="form-control" readonly>
                                                
                                            @else
                                                <input name="cobro_promo" type="text" id="cobro_promo" value="{{ $cupon->monto_total }}" class="form-control" readonly>
                                                
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    $cantidad_de_productos = App\CombosProducto::where('combo_id', $cupon->combo_id)->count();
                                    $productos_combo = App\CombosProducto::where('combo_id', $cupon->combo_id)->get();
                                @endphp
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Nombre de la promo</label>
                                            <input name="cobro_producto" type="text" id="cobro_producto" value="{{ $cupon->combo->nombre }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Productos en promocion {{ $cantidad_de_productos }}</label>
                                                @foreach($productos_combo as $productos)
                                                    <input name="cobro_descuento" type="text" value="{{ $productos->producto->nombre }} %" class="form-control" readonly>
                                                @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Precio</label>
                                            @foreach($productos_combo as $productos)
                                                <input name="cobro_precio" type="text" value="{{ $productos->precio }}" class="form-control" readonly>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Cantidad</label>
                                            @foreach($productos_combo as $key => $productos)
                                                <input name="cantidad_promo_producto-{{ ($key+1) }}" type="text" id="cantidad_promo_producto-{{ ($key+1) }}" value="{{ $productos->cantidad }}" class="form-control" readonly>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="hidden" name="cantidad_productos_promo" id="cantidad_productos_promo" value="{{ $cantidad_de_productos }}">
                                            <label class="control-label">Stock</label>
                                            @foreach($productos_combo as $key => $productos)
                                                @php
                                                    $stock = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                                                        ->where('producto_id', $productos->producto_id)
                                                        ->where('almacene_id', auth()->user()->almacen_id)
                                                        ->first();
                                                    $stock=intval($stock->total);
                                                @endphp
                                                <input name="stock_promo_producto-{{ ($key+1) }}" type="text" id="stock_promo_producto-{{ ($key+1) }}" value="{{ $stock }}" class="form-control" readonly>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 text-center"><h4><span class="text-danger">Detalle de Transacci&oacute;n</span></h4></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="email2" class="col-sm-5 text-right control-label col-form-label">TOTAL</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="cobro_total" id="cobro_total" value="{{ $precioTotalCombo }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="email2" class="col-sm-5 text-right control-label col-form-label">EFECTIVO</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="cobro_efectivo" id="cobro_efectivo" value="0" step="any">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="email2" class="col-sm-5 text-right control-label col-form-label">CAMBIO</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="cobro_cambio" id="cobro_cambio" value="0" step="any" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block waves-effect waves-light" onclick="cobra_cupon()" id="boton_compra" disabled >Efectuar compra</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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


</script>
@endsection
