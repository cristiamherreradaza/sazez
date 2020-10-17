<form action="{{ url('Producto/adicionaRegularizacion') }}" method="POST" id="formularioAdicionaProducto" />
@csrf
<div class="modal-body">
    <input type="hidden" name="regularizaAdicionProductoId" id="regularizaAdicionProductoId" value="">
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Codigo</label>
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
                <input name="codigoQr" type="text" id="codigoQr" class="form-control" value="{{ $datosProducto->codigo }}" readonly>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <label class="control-label">Nombre</label>
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
                <input name="nombreQr" type="text" id="nombreQr" class="form-control" value="{{ $datosProducto->nombre }}" readonly>
            </div>
        </div>
        
    </div>
    @if ($datosPrecios->count() != 0)
    <div class="row">

        <div class="col-md-8">
            <div class="form-group">
                <label class="control-label">Tipo</label>
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
                <input name="nombreQr" type="text" id="nombreQr" class="form-control" value="{{ $datosProducto->nombre }}" readonly>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Codigo</label>
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
                <input name="cantidad_producto_a_reportar" type="number" id="cantidad_producto_a_reportar" min="1" class="form-control" value="1" required>
            </div>
        </div>
        
    </div>
    @endif
    
    
</div>
<div class="modal-footer">
    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="valida_adiciona()">ADICIONAR</button>
</div>
</form>