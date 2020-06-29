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
                <th>Precio</th>
                <th class="text-nowrap">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $key => $p)
                <tr class="item_{{ $p->id }}">
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->codigo }}</td>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->marca->nombre }}</td>
                    <td>{{ $p->tipo->nombre }}</td>
                    <td>{{ $p->modelo }}</td>
                    <td>{{ $p->colores }}</td>
                    @php
                        $cantidadTotal = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) - SUM(salida) as total'))
                            ->where('producto_id', $p->id)
                            ->where('almacene_id', auth()->user()->almacen_id)
                            ->first();
                        $cantidadTotal=intval($cantidadTotal->total);
                    @endphp
                    <td>{{ $cantidadTotal }}</td>
                    @php
                        $precio = App\Precio::where('producto_id', $p->id)
                                    ->where('escala_id', 1)
                                    ->first();
                    @endphp
                    <td>{{ $precio->precio }}</td>
                    <td>
                        <button type="button" class="btnSelecciona btn btn-info" title="Adiciona Item"><i class="fas fa-plus"></i></button>
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
                    `<input type="number" class="form-control text-right precio" name="precio[`+id+`]" id="precio_`+id+`" value="0" data-id="`+id+`" step="any" min="0" pattern="^[0-9]+">`,
                    `<input type="number" class="form-control text-right cantidad" name="cantidad[`+id+`]" id="cantidad_`+id+`" value="1" data-id="`+id+`" min="1" pattern="^[0-9]+">`,
                    `<input type="number" class="form-control text-right subtotal" name="subtotal[`+id+`]" id="subtotal_`+id+`" value="1" step="any" readonly>`,
                    '<button type="button" class="btnElimina btn btn-danger" title="Eliminar producto"><i class="fas fa-trash-alt"></i></button>'
                ]).draw(false);
                sumaSubTotales();
            }
        });

    });
</script>