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
            $("#oculta_detalle").hide('slow');
            var currentRow = $(this).closest("tr");
            var id = currentRow.find("td:eq(0)").text();
            var nombre = currentRow.find("td:eq(2)").text();
            var precio = currentRow.find("td:eq(7)").text();
            $("#producto_id").val(id);
            $("#producto_nombre").val(nombre);
            $("#producto_precio").val(precio);
            $("#producto_total").val(precio);
            $("#muestra_detalle").css("display", "block");            
        });

    });
</script>