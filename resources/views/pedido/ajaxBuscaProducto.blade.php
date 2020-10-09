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
                <th>Stock</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $key => $producto)
                <tr class="item_{{ $producto->id }}">
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    <td>{{ $producto->tipo->nombre }}</td>
                    <td>{{ $producto->modelo }}</td>
                    <td>{{ $producto->colores }}</td>
                    @php
                        $ingreso = App\Movimiento::where('producto_id', $producto->id)
                                                ->where('almacene_id', $almacen_id)
                                                ->where('ingreso', '>', 0)
                                                ->sum('ingreso');
                        $salida = App\Movimiento::where('producto_id', $producto->id)
                                                ->where('almacene_id', $almacen_id)
                                                ->where('salida', '>', 0)
                                                ->sum('salida');
                        $total = $ingreso - $salida;
                    @endphp
                    <td>{{ $total }}</td>
                    <td>
                        @if($total > 0)
                            <button type="button" class="btnSelecciona btn btn-info" title="Adiciona Item"><i class="fas fa-plus"></i></button>
                        @endif
                    </td>
                </tr>    
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $("#tablaProductosEncontrados").on('click', '.btnSelecciona', function () {

            $("#listadoProductosAjax").hide('slow');
            $("#termino").val("");
            $("#termino").focus();

            var currentRow = $(this).closest("tr");

            var id      = currentRow.find("td:eq(0)").text();
            var codigo  = currentRow.find("td:eq(1)").text();
            var nombre  = currentRow.find("td:eq(2)").text();
            var marca   = currentRow.find("td:eq(3)").text();
            var tipo    = currentRow.find("td:eq(4)").text();
            var modelo  = currentRow.find("td:eq(5)").text();
            var colores = currentRow.find("td:eq(6)").text();
            var stock   = currentRow.find("td:eq(7)").text();

            let buscaItem = itemsPedidoArray.lastIndexOf(id);
            if(buscaItem < 0)
            {
                itemsPedidoArray.push(id);  
                t.row.add([
                    id,
                    codigo,
                    nombre,
                    marca,
                    tipo,
                    modelo,
                    colores,
                    stock,
                    `<input type="number" class="form-control" value="1" min="1" name="item[` + id + `]" required>`,
                    '<button type="button" class="btnElimina btn btn-danger" title="Eliminar marca"><i class="fas fa-trash-alt"></i></button>'
                ]).draw(false);
            }
        });

    });
    </script>
    {{-- <input type="hidden" name="item[`+id+`][]" value="`+id+`"> --}}