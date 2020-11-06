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
                $totalventa        = 0;
                $totalventaMayor   = 0;
                $totalcobrado      = 0;
                $totalcobradoMayor = 0;
            @endphp
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->fecha }}</td>
                <td>{{ $venta->user->almacen->nombre }}</td>
                <td>{{ $venta->user->name }}</td>
                <td>{{ $venta->producto->nombre }}</td>
                @if ($venta->precio_cobrado_mayor > 0)
                    <td style="text-align: right">
                        <span class="text-info"><b>{{ ($venta->precio_cobrado_mayor>0)?$venta->escala->nombre:"" }}</b></span>&nbsp;&nbsp;
                        {{ $venta->precio_venta_mayor }}
                    </td>
                    <td style="text-align: right">{{ $venta->precio_cobrado_mayor }}</td>
                @else
                    <td style="text-align: right">{{ $venta->precio_venta }}</td>
                    <td style="text-align: right">{{ $venta->precio_cobrado }}</td>
                @endif
                <td style="text-align: right">{{ round($venta->cantidad) }}</td>
                @if ($venta->precio_cobrado_mayor > 0)
                    <td style="text-align: right">{{ ($venta->cantidad * $venta->precio_venta_mayor) }}</td>
                    <td style="text-align: right">{{ ($venta->cantidad * $venta->precio_cobrado_mayor) }}</td>
                @else
                    <td style="text-align: right">{{ ($venta->cantidad * $venta->precio_venta) }}</td>
                    <td style="text-align: right">{{ ($venta->cantidad * $venta->precio_cobrado) }}</td>
                @endif
                @php
                    if ($venta->precio_cobrado_mayor > 0) {
                        $totalventaMayor += $venta->cantidad * $venta->precio_venta_mayor;
                    }else{
                        $totalventa=$totalventa+($venta->cantidad * $venta->precio_venta);
                    }
                    $totalVentas = $totalventa + $totalventaMayor;

                    if ($venta->precio_cobrado_mayor > 0) {
                        $totalcobradoMayor += $venta->cantidad * $venta->precio_cobrado_mayor;
                    }else{
                        $totalcobrado=$totalcobrado+($venta->cantidad * $venta->precio_cobrado);
                    }
                    $cobradoMayor = $totalcobradoMayor + $totalcobrado;

                @endphp
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7"></th>
                <th style="text-align: right">{{ $totalVentas }}</th>
                <th style="text-align: right">{{ $cobradoMayor }}</th>
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