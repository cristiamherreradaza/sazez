<div class="row">
    <div class="col-md-12"><h5><span class="text-info">Usuario: </span>{{ $datosFactura->user->name }}</h5></div>
</div>

<div class="row">
    <div class="col-md-6"><h5><span class="text-info">Fecha: </span>{{ $datosFactura->created_at }}</h5></div>
    <div class="col-md-6"><h5><span class="text-info">Almacen: </span>{{ $datosFactura->almacen->nombre }}</h5></div>
</div>

<div class="row">
    <div class="col-md-4"><h5><span class="text-info">Razon: </span>{{ $datosFactura->cliente->razon_social }}</h5></div>
    <div class="col-md-4"><h5><span class="text-info">Nit: </span>{{ $datosFactura->nit_cliente }}</h5></div>
    <div class="col-md-4"><h5><span class="text-info">Venta: </span>{{ $datosFactura->venta_id }}</h5></div>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label for="nombre">
                RAZON SOCIAL
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
            </label>
            <input type="text" class="form-control" name="razon_social" id="razon_social" value="{{ $datosFactura->cliente->razon_social }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="nombre">
                NIT
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
            </label>
            <input type="number" class="form-control" name="nit" id="nit" min="1" value="{{ $datosFactura->nit_cliente }}">
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label for="nombre">MONTO ORIGINAL</label>
            <input type="text" class="form-control" name="montoOriginal" id="montoOriginal" value="{{ $datosFactura->venta->total }}" readonly>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="nombre">
                A CAMBIAR
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
            </label>
            <input type="number" class="form-control" name="cambio" id="cambio" min="1" value="1">
            <input type="hidden" id="facturaId" value="{{ $datosFactura->id }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="nombre">&nbsp;</label>
            <button type="button" onclick="cambiaMonto()" class="btn btn-block btn-success">Cambiar</button>
        </div>
    </div>

</div>