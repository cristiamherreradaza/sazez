<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th colspan="2"></th>
                @foreach($users as $usuario)
                    <th colspan="3">{{ $usuario->name }}</th>
                @endforeach
                <th></th>
            </tr>
            <tr>
                <th>Dia</th>
                <th>Fecha</th>
                @foreach($users as $usuario)
                    <th>Accesorios</th>
                    <th>Descuentos</th>
                    <th>Total</th>
                @endforeach
                <th>Total Dia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fechas as $fecha)
                <tr>
                    <td>
                        @switch($fecha->dia)
                            @case(1)
                                Domingo
                                @break
                            @case(2)
                                Lunes
                                @break
                            @case(3)
                                Martes
                                @break
                            @case(4)
                                Miercoles
                                @break
                            @case(5)
                                Jueves
                                @break
                            @case(6)
                                Viernes
                                @break
                            @case(7)
                                Sabado
                                @break
                        @endswitch
                    </td>
                    <td>{{ $fecha->fecha }}</td>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($users as $usuario)
                        @php
                            $accesorios_venta = App\VentasProducto::where('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->sum('precio_venta');
                            $accesorios_cobrado = App\VentasProducto::where('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->sum('precio_cobrado');
                            $descuento = $accesorios_venta - $accesorios_cobrado;
                            $accesorios_venta_mayor = App\VentasProducto::where('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->sum('precio_venta_mayor');
                            $accesorios_cobrado_mayor = App\VentasProducto::where('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->sum('precio_cobrado_mayor');
                            $descuento_mayor = $accesorios_venta_mayor - $accesorios_cobrado_mayor;
                            $total = $total + ($accesorios_venta - $descuento);
                        @endphp
                        <td>{{ $accesorios_venta }}</td>
                        <td>{{ $descuento }}</td>
                        <td>{{ ($accesorios_venta - $descuento) }}</td>
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