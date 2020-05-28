<link href="{{ asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
<div class="table-responsive">

    <table class="table table-striped no-wrap" id="tablaProductosEncontrados">
        <thead>
            <tr>
                <th>No.</th>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Color</th>
                <th style="width: 15%">Precio</th>
                <th style="width: 10%">Cantidad</th>
                <th class="text-nowrap">Total</th>
                <th class="text-nowrap"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productosCotizacion as $key => $pc)
            @php
                $precioProducto = App\Precio::where('producto_id', $pc->producto->id)->where('escala_id', 1)->first();
            @endphp
                <tr class="item_{{ $pc->id }}">
                    <td>{{ ++$key }}</td>
                    <td>{{ $pc->producto->codigo }}</td>
                    <td>{{ $pc->producto->nombre }}</td>
                    <td>{{ $pc->producto->marca->nombre }}</td>
                    <td>{{ $pc->producto->tipo->nombre }}</td>
                    <td>{{ $pc->producto->colores }}</td>
                    <td>
                        <input id="tch2" type="text" value="{{ $precioProducto->precio }}" name="tch2" class=" form-control"
                        data-bts-button-down-class="btn btn-secondary btn-outline" data-bts-button-up-class="btn btn-secondary btn-outline">
                    </td>
                    <td>
                        <input id="tch3" type="text" value="1" name="tch3" data-bts-button-down-class="btn btn-secondary btn-outline" data-bts-button-up-class="btn btn-secondary btn-outline">
                    </td>
                    <td></td>
                    <td>
                        <button type="button" class="btnSelecciona btn btn-info" title="Adiciona Item" onclick="adiciona_item('{{ $pc->id }}')"><i class="fas fa-plus"></i></button>
                    </td>
                </tr>    
            @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js') }}" type="text/javascript"></script>
<script>
    $("input[name='tch2']").TouchSpin({
        min: -1000000000,
        max: 1000000000,
        stepinterval: 50,
        maxboostedstep: 10000000,
        prefix: '$'
    });
    $("input[name='tch3']").TouchSpin();
</script>