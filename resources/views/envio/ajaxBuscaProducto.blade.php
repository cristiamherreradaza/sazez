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
                        $total = DB::select("SELECT (SUM(ingreso) - SUM(salida))as total
                                            FROM movimientos
                                            WHERE producto_id = '$p->id'
                                            AND almacene_id = 1
                                            GROUP BY producto_id");
                        $cantidad_disponible = $total[0]->total;
                    @endphp
                    <td>{{ $cantidad_disponible }}</td>
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

            $("#producto_id").val("");
            $("#producto_nombre").val("");
            $("#producto_cantidad").val(1);

            var currentRow = $(this).closest("tr");

            var id      = currentRow.find("td:eq(0)").text();
            var codigo  = currentRow.find("td:eq(1)").text();
            var nombre  = currentRow.find("td:eq(2)").text();
            var marca   = currentRow.find("td:eq(3)").text();
            var tipo    = currentRow.find("td:eq(4)").text();
            var modelo  = currentRow.find("td:eq(5)").text();
            var colores = currentRow.find("td:eq(6)").text();
            var stock   = currentRow.find("td:eq(7)").text();

            // agregaremos los atributos: id, nombre, y cantidad (max->stock)
            $("#producto_id").val(id);
            $("#producto_nombre").val(nombre);
            $("#producto_cantidad").attr({ "max" : stock });

            // let buscaItem = itemsPedidoArray.lastIndexOf(id);
            // if(buscaItem < 0)
            // {
            //     itemsPedidoArray.push(id);  
            //     t.row.add([
            //         id,
            //         codigo,
            //         nombre,
            //         marca,
            //         tipo,
            //         modelo,
            //         colores,
            //         stock,
            //         `<input type="number" class="form-control" value="1" min="1" max="`+stock+`" name="item[` + id + `]">`,
            //         '<button type="button" class="btnElimina btn btn-danger" title="Eliminar marca"><i class="fas fa-trash-alt"></i></button>'
            //     ]).draw(false);
            // }
        });

    });
</script>