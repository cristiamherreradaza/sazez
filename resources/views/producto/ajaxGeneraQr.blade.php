<form action="{{ url('Producto/generaQr') }}" method="POST" id="formularioGeneraQr">
@csrf
<div class="modal-body">
    <input type="hidden" name="productoIdQr" id="productoIdQr" value="{{ $datosProducto->id }}">
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
                <label>TIPOS </label>
                <select name="tipoQr" class="form-control">
                    @foreach ($datosPrecios as $p)
                    <option value="{{ $p->id }}">{{ $p->escala->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Cant.</label>
                <span class="text-danger">
                    <i class="mr-2 mdi mdi-alert-circle"></i>
                </span>
                <input name="cantidadQr" type="number" id="cantidadQr" min="1" class="form-control" value="1" required />
            </div>
        </div>
        
    </div>
    @else
        <h3 class="text-info">No tienes precios en el producto, necesitas un precio para generar un codigo QR</h3>
    @endif
    
    
</div>
@if ($datosPrecios->count() != 0)
    <div class="modal-footer">
        <button type="button" class="btn waves-effect waves-light btn-block btn-dark" onclick="valida_qr()">GENERAR</button>
    </div>
@endif
</form>