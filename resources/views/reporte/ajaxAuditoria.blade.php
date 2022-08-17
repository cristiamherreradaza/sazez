@if ($ventas->count() > 0)
<div class="table-responsive m-t-40">
    <table id="tabla-ventas" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>No.</th>
                <th>Venta</th>
                <th>Usuario</th>
                <th>Tienda</th>
                <th>Factura</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Saldo</th>
                <th>F/Usuario</th>
                <th>F/Sistema</th>
                <th>F/Eliminacion</th>
                <th>Desc. Eliminacion</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $k => $v)
            <tr>
                <td>{{ ++$k }}</td>
                <td>{{ $v->id }}</td>
                <td>{{ $v->user->name }}</td>
                <td>{{ $v->almacen->nombre }}</td>
                <td>
                    @if($v->factura)
                        {{ $v->factura['numero_factura'] }}
                    @endif
                </td>
                <td>{{ $v->cliente->name }}</td>
                <td>{{ $v->total }}</td>
                <td>{{ $v->saldo }}</td>
                <td>{{ $v->fecha }}</td>
                <td>{{ $v->created_at }}</td>
                <td>{{ $v->deleted_at }}</td>
                <td>{{ $v->descripcion }}</td>
                @php
                    $movimientos = App\Movimiento::where('venta_id', $v->id)
                                    ->withTrashed()
                                    ->get();
                    
                @endphp    
                    <td>
                        {{-- <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>    
                            </thead>
                            <tbody>
                                @if ($movimientos->count() > 0)
                                    @foreach ($movimientos as $m)
                                        <tr>
                                            <td>
                                                {{ $m->producto->nombre }}
                                            </td>
                                            <td>{{ $m->salida }}</td>
                                            <td>{{ $m->precio_venta }}</td>
                                        </tr>    
                                    @endforeach
                                @else
                                <tr>
                                    <td>no tiene</td>
                                </tr>
                                    
                                @endif
                            </tbody>
                        </table> --}}

                                @if ($movimientos->count() > 0)
                                    @foreach ($movimientos as $m)
                                            <b>Nombre:</b> {{ $m->producto->nombre }}
                                            <b>Cantidad:</b> {{ $m->salida }}
                                            <b>Precio:</b> {{ $m->precio_venta }}
                                        <br/ >
                                    @endforeach
                                @else
                                    No tiene
                                @endif

                    </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<script>
    $(function () {
        
        $('#tabla-ventas').DataTable({
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
                title: 'Ventas' 
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