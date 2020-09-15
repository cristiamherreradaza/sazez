<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Tienda</th>
                <th>Tipo</th>
                <th>Producto</th>
                <th>Marca</th>
                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Saldo Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $almacen->nombre }}</td>
                    <td>{{ $producto->tipo->nombre }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    @php

                    $input = $fecha.' 23:59:59';
                    $date = strtotime($input);
                    $fecha_cambiada = date('Y-m-d h:i:s', $date); 
                    dd($fecha_cambiada);

                        $totali = DB::select("SELECT SUM(ingreso) as total
                                            FROM movimientos
                                            WHERE producto_id = $producto->id
                                            AND almacene_id = $almacen->id
                                            AND fecha <= $fecha_cambiada
                                            GROUP BY producto_id");
                        dd($totali);
                        $ingreso = $totali[0]->total;
                        $total = DB::select("SELECT SUM(salida) as total
                                            FROM movimientos
                                            WHERE producto_id = $producto->id
                                            AND almacene_id = $almacen->id
                                            AND fecha <= $fecha_cambiada
                                            GROUP BY producto_id");
                        $salida = $total[0]->total;
                        $resultado = $ingreso-$salida;
                    @endphp
                    <td>{{ round($ingreso) }}</td>
                    <td>{{ round($salida) }}</td>
                    <td>{{ $resultado }}</td>
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