<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Tienda</th>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Precio en $us</th>
                <th>Tipo Cambio</th>
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
                                                        ->whereNull('deleted_at')
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

                        // PARA EL PRECIO DEL PRODUCTO
                        $precioProducto = App\Precio::where('producto_id', $producto->id)->where('escala_id', 1)->first();
                    @endphp
                    <td>
                        @if ($precioProducto)
                            @if ($almacen->modalidad == 'actual')
                                {{ ceil($precioProducto->precio / $almacen->actual_tipo_cambio) }}
                            @else
                                {{ ceil($precioProducto->precio * $almacen->tipo_cambio) }}
                            @endif
                        @else

                        @endif
                    </td>
                    <td>
                        @if ($almacen->modalidad == 'actual')
                            {{ $almacen->actual_tipo_cambio }}
                        @else
                            {{ $almacen->tipo_cambio }}
                        @endif
                    </td>
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
