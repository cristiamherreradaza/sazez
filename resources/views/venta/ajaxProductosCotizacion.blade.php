<div class="table-responsive">
    <table class="table table-striped no-wrap" id="tablaProductosEncontrados">
        <thead>
            <tr>
                <th>ID</th>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Modelo</th>
                <th>Colores</th>
                <th class="text-nowrap">Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productosCotizacion as $key => $pc)
                <tr class="item_{{ $pc->id }}">
                    <td>{{ $pc->id }}</td>
                    <td>{{ $pc->producto->codigo }}</td>
                    <td>{{ $pc->producto->nombre }}</td>
                    <td>{{ $pc->modelo }}</td>
                    <td>{{ $pc->colores }}</td>
                    <td></td>
                    <td></td>
                    <td>
                        <button type="button" class="btnSelecciona btn btn-info" title="Adiciona Item" onclick="adiciona_item('{{ $pc->id }}')"><i class="fas fa-plus"></i></button>
                    </td>
                </tr>    
            @endforeach
        </tbody>
    </table>
</div>