<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Tienda</th>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Saldo Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $almacen->nombre }}</td>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->tipo->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    @php
                        $saldo = App\Movimiento::select(DB::raw("(SUM(ingreso) - SUM(salida)) as total"))
                                                        ->where('producto_id', $producto->id)
                                                        ->where('almacene_id', $almacen->id)
                                                        ->whereDate('fecha', '<=', $fecha)
                                                        ->get();
                        if($saldo[0]->total)
                        {
                            $saldo = round($saldo[0]->total);
                        }
                        else
                        {
                            $saldo = 0;
                        }
                    @endphp
                    <td>{{ $saldo }}</td>
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