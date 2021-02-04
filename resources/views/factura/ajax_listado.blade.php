@if ($facturas->count() > 0)
<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tienda</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>No</th>
                <th>Estado</th>
                <th>NIT</th>
                <th>Razon Social</th>
                <th>Importe</th>
                <th>Codigo Control</th>
                <th>Numero Autorizacion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $k => $f)
                <tr>
                    <td>{{ ++$k }}</td>
                    <td>{{ $f->almacen->nombre }}</td>
                    <td>{{ $f->user->name }}</td>
                    <td>
                        @php
                            $fechaArray = explode(" ", $f->created_at);
                        @endphp
                        {{ $fechaArray[0] }}
                    </td>
                    <td>
                        @if ($f->venta_id == null)
                            <a href="{{ url("Factura/imprimeFactura/$f->id") }}" target="_blank">{{ $f->numero_factura }}</a>
                        @else 
                            <a href="{{ url("Venta/imprimeFactura/$f->venta_id") }}" target="_blank">{{ $f->numero_factura }}</a>
                        @endif
                    </td>
                    <td>{{ $f->estado }}</td>
                    <td>{{ $f->nit_cliente }}</td>
                    <td>{{ $f->cliente['razon_social'] }}</td>
                    <td>{{ $f->monto_compra }}</td>
                    <td>
                        <a href="#" onclick="modifica({{ $f->id }})">{{ $f->codigo_control }}</a>
                    </td>
                    <td>{{ $f->numero_autorizacion }}</td>
                </tr>
            @endforeach
        </tbody>
        
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
            {
                extend: 'excel',
                title: 'Facturas' 
            },
            'copy'],
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>
@else
    <h2 class="text-info text-center">No existen registros</h2>
@endif
