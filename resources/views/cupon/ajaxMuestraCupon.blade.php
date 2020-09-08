<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">COBRO CUP&Oacute;N</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <form action="{{ url('Cupon/cobrar') }}" method="POST" >
        @csrf
        <div class="modal-body">
            <input type="hidden" name="cobro_cupon_id" id="cobro_cupon_id" value="{{ $cupon->id }}">
            <input type="hidden" name="cobro_cliente_id" id="cobro_cliente_id" value="{{ $cliente->id }}">
            @if($producto)
                <input type="hidden" name="cobro_producto_id" id="cobro_producto_id" value="{{ $producto->id }}">
            @else
                <input type="hidden" name="cobro_combo_id" id="cobro_combo_id" value="{{ $combo->id }}">
            @endif
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <span class="text-danger">
                            <i class="mr-2 mdi mdi-alert-circle"></i>
                        </span>
                        <input name="cobro_nombre" type="text" id="cobro_nombre" value="{{ $cliente->name }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Cedula de Identidad</label>
                        <span class="text-danger">
                            <i class="mr-2 mdi mdi-alert-circle"></i>
                        </span>
                        <input name="cobro_ci" type="text" id="cobro_ci" value="{{ $cliente->ci }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Celulares</label>
                        <input name="cobro_celular" type="text" id="cobro_celular" value="{{ $cliente->celulares }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Correo Electrónico</label>
                        <input name="cobro_email" type="email" id="cobro_email" value="{{ $cliente->email }}" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Nit</label>
                        <input name="cobro_nit" type="text" id="cobro_nit" value="{{ $cliente->nit }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Razon Social</label>
                        <input name="cobro_razon_social" type="text" id="cobro_razon_social" value="{{ $cliente->razon_social }}" class="form-control">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Tienda</label>
                                <input name="cobro_tienda" type="text" id="cobro_tienda" value="{{ auth()->user()->almacen->nombre }}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    @if($producto)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Producto</label>
                                    <input name="cobro_producto" type="text" id="cobro_producto" value="{{ $producto->nombre }}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Stock</label>
                                    @php
                                        $stock = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                                            ->where('producto_id', $producto->id)
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
                                        $precio = App\Precio::where('producto_id', $producto->id)
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
                                    <input name="cobro_promo" type="text" id="cobro_promo" value="{{ $cupon->monto_total }}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            $cantidad_de_productos = App\CombosProducto::where('combo_id', $combo->id)->count();
                        @endphp
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Nombre de la promo</label>
                                    <input name="cobro_producto" type="text" id="cobro_producto" value="{{ $combo->nombre }}" class="form-control" readonly>
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
                    <h3 class="text-center"><strong>Detalle</strong></h3>
                    <div class="row">
                        <div class="form-group row">
                            <label for="email2" class="col-sm-5 text-right control-label col-form-label">TOTAL</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="cobro_total" id="cobro_total" value="{{ $cupon->monto_total }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group row">
                            <label for="email2" class="col-sm-5 text-right control-label col-form-label">EFECTIVO</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="cobro_efectivo" id="cobro_efectivo" value="0" step="any">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group row">
                            <label for="email2" class="col-sm-5 text-right control-label col-form-label">CAMBIO</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="cobro_cambio" id="cobro_cambio" value="0" step="any" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block waves-effect waves-light" onclick="cobra_cupon()" id="boton_compra" disabled >Efectuar compra</button>
                </div>
            </div>
        </div>
    </form>
</div>