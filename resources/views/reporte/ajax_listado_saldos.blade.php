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
                    $ingreso = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) as total'))
                                            ->where('producto_id', $producto->id)
                                            ->where('almacene_id', $almacen->id)
                                            ->first();
                    $salida = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(salida) as total'))
                                            ->where('producto_id', $producto->id)
                                            ->where('almacene_id', $almacen->id)
                                            ->first();
                    $resultado = $ingreso->total - $salida->total;
                    @endphp
                    <td>{{ round($ingreso->total) }}</td>
                    <td>{{ round($salida->total) }}</td>
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