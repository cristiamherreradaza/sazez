<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tienda</th>
                <th>Usuario</th>
                <th>Tipo</th>
                <th>Codigo</th>
                <th>Nombre Producto</th>
                <th>Precio Venta</th>
                <th>Precio Cobrado</th>
                <th>Cantidad</th>
                <th>Total Venta</th>
                <th>Total Cobrado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->fecha }}</td>
                <td>{{ $venta->user->almacen->nombre }}</td>
                <td>{{ $venta->user->name }}</td>
                <td>{{ $venta->tipo->nombre }}</td>
                <td>{{ $venta->producto->codigo }}</td>
                <td>{{ $venta->producto->nombre }}</td>
                <td>{{ $venta->precio_venta }}</td>
                <td>{{ $venta->precio_cobrado }}</td>
                <td>{{ round($venta->cantidad) }}</td>
                <td>{{ ($venta->cantidad * $venta->precio_venta) }}</td>
                <td>{{ ($venta->cantidad * $venta->precio_cobrado) }}</td>
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