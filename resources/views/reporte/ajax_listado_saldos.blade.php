<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Tienda</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Saldo Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datosMovimientos as $producto)
                <tr>
                    <td>{{ $producto->almacen->nombre }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->nombre_tipo }}</td>
                    <td>{{ $producto->nombre_marca }}</td>
                    <td>{{ round($producto->total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(function () {
        $('#tabla-usuarios').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>