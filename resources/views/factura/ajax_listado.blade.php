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
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $k => $f)
                <tr>
                    <td>{{ ++$k }}</td>
                    <td>{{ $f->almacen->nombre }}</td>
                    <td>{{ $f->user->name }}</td>
                    <td>{{ $f->created_at }}</td>
                    <td>{{ $f->numero_factura }}</td>
                    <td>{{ $f->estado }}</td>
                    <td>{{ $f->nit_cliente }}</td>
                    <td>{{ $f->cliente['razon_social'] }}</td>
                    <td>{{ $f->monto_compra }}</td>
                    <td>{{ $f->codigo_control }}</td>
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