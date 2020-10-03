<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>REPORTE DE SALDOS DE {{ $almacen->nombre }} EN FECHA {{ $fecha }}</tr>
            <tr>
                <th>Tienda</th>
                <th>Producto</th>
                <th>Marca</th>
                <th>Saldo Anterior</th>
                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Saldo Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                @php
                    $saldo_anterior = App\Movimiento::select(DB::raw("(SUM(ingreso) - SUM(salida)) as total"))
                                                    ->where('producto_id', $producto->id)
                                                    ->where('almacene_id', $almacen->id)
                                                    ->whereDate('fecha', '<', $fecha)
                                                    ->get();
                    if($saldo_anterior[0]->total)
                    {
                        $saldo_anterior = round($saldo_anterior[0]->total);
                    }
                    else
                    {
                        $saldo_anterior = 0;
                    }
                    $ingreso = App\Movimiento::select(DB::raw("SUM(ingreso) as total"))
                                                    ->where('producto_id', $producto->id)
                                                    ->where('almacene_id', $almacen->id)
                                                    ->whereDate('fecha', $fecha)
                                                    
                                                    ->get();
                    if($ingreso[0]->total)
                    {
                        $ingreso = round($ingreso[0]->total);
                    }
                    else
                    {
                        $ingreso = 0;
                    }
                    $salida = App\Movimiento::select(DB::raw("SUM(salida) as total"))
                                                    ->where('producto_id', $producto->id)
                                                    ->where('almacene_id', $almacen->id)
                                                    ->whereDate('fecha', $fecha)
                                                    ->get();
                    if($salida[0]->total)
                    {
                        $salida = round($salida[0]->total);
                    }
                    else
                    {
                        $salida = 0;
                    }
                    $saldo_total = ($saldo_anterior+$ingreso-$salida)
                @endphp
                <tr>
                    <td>{{ $almacen->nombre }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    
                    <td>{{ $saldo_anterior }}</td>
                    <td>{{ $ingreso }}</td>
                    <td>{{ $salida }}</td>
                    <td>{{ $saldo_total }}</td>
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