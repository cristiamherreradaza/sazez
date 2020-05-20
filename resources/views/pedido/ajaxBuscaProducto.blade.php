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
        // code to read selected table row cell data (values).
        $("#tablaProductosEncontrados").on('click', '.btnSelecciona', function () {
            // get the current row
            // console.log("entro");
            var currentRow = $(this).closest("tr");

            var col1 = currentRow.find("td:eq(0)").text(); // get current row 1st TD value
            var col2 = currentRow.find("td:eq(1)").text(); // get current row 2nd TD
            var col3 = currentRow.find("td:eq(2)").text(); // get current row 3rd TD
            var data = col1 + "\n" + col2 + "\n" + col3;

            // console.log(t);

            t.row.add([
                col1,
                col2,
                '<input type="text">',
                '<button type="button" class="btnElimina btn btn-danger" title="Eliminar marca"><i class="fas fa-trash"></i></button>'
            ]).draw(false);

            // alert(data);
        });

    });
</script>