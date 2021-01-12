<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Producto</th>
                <th>Marca</th>
                @foreach($almacenes as $almacen)
                    <th>{{ $almacen->nombre }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    @php
                        $total = 0;
                    @endphp
                    <td>{{ $producto->tipo->nombre }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->marca->nombre }}</td>
                    @foreach($almacenes as $almacen)
                        @php
                            $ingreso = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(ingreso) as total'))
                                    ->whereNull('deleted_at')
                                    ->where('producto_id', $producto->id)
                                    ->where('almacene_id', $almacen->id)
                                    ->first();
                            $salida = App\Movimiento::select(Illuminate\Support\Facades\DB::raw('SUM(salida) as total'))
                                                    ->whereNull('deleted_at')
                                                    ->where('producto_id', $producto->id)
                                                    ->where('almacene_id', $almacen->id)
                                                    ->first();
                            $resultado = $ingreso->total - $salida->total;
                            $total = $total + $resultado;
                        @endphp
                        <td>{{ round($resultado) }}</td>
                    @endforeach
                    <td>{{ $total }}</td>
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