<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tienda</th>
                <th>Usuario</th>
                <th>Nombre Producto</th>
                <th>Precio Venta</th>
                <th>Precio Cobrado</th>
                <th>Cantidad</th>
                <th>Total Venta</th>
                <th>Total Cobrado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalventa=0;
                $totalcobrado=0;
            @endphp
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->fecha }}</td>
                <td>{{ $venta->user->almacen->nombre }}</td>
                <td>{{ $venta->user->name }}</td>
                <td>{{ $venta->producto->nombre }}</td>
                <td style="text-align: right">{{ $venta->precio_venta }}</td>
                <td style="text-align: right">{{ $venta->precio_cobrado }}</td>
                <td style="text-align: right">{{ round($venta->cantidad) }}</td>
                <td style="text-align: right">{{ ($venta->cantidad * $venta->precio_venta) }}</td>
                <td style="text-align: right">{{ ($venta->cantidad * $venta->precio_cobrado) }}</td>
                @php
                    $totalventa=$totalventa+($venta->cantidad * $venta->precio_venta);
                    $totalcobrado=$totalcobrado+($venta->cantidad * $venta->precio_cobrado);
                @endphp
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7"></th>
                <th style="text-align: right">{{ $totalventa }}</th>
                <th style="text-align: right">{{ $totalcobrado }}</th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(function () {
        
        $('#tabla-usuarios').DataTable({
            paging: true,
            dom: 'Bfrtip',
            buttons: [{
                // 'copy', 'excel', 'pdf'
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: 'REPORTE',
                footer: true
            },
            'excel', 'copy'],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>