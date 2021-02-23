@if ($productos->count() > 0)
<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Almacen</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Ingreso</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $p)
                @if ($p->ingreso > 0)
                    @php
                        $almacen = App\Almacene::find($p->almacene_id);
                        $marca = App\Marca::find($p->marca_id);
                    @endphp
                    <tr>
                        <td>{{ $almacen->nombre }}</td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->tipo->nombre }}</td>
                        <td>{{ $marca->nombre }}</td>
                        <td>{{ $p->ingreso }}</td>
                        <td>{{ $p->created_at }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@else
    <h3 class="text-info text-center">No existen registros.</h3>
@endif

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