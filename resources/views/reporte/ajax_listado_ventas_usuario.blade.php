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
                        // $total = 0;
                        $totalAcumulado = 0;
                        $descuentoTotal = 0;
                    @endphp
                    @foreach($users as $usuario)
                        @php
                            $total = 0;
                            // costos de venta de menor
                            $accesorios_venta = App\VentasProducto::select(DB::raw("(precio_venta * cantidad) as total"))
                                                            ->whereDate('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->get();
                            $accesorios_cobrado = App\VentasProducto::select(DB::raw("(precio_cobrado * cantidad) as total"))
                                                            ->whereDate('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->get();

                            // costos de venta al por mayor
                            $accesorios_venta_mayor = App\VentasProducto::select(DB::raw("(precio_venta_mayor * cantidad) as total"))
                                                            ->whereDate('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->get();

                            $accesorios_cobrado_mayor = App\VentasProducto::select(DB::raw("(precio_cobrado_mayor * cantidad) as total"))
                                                            ->whereDate('fecha', $fecha->fecha)
                                                            ->where('user_id', $usuario->id)
                                                            ->get();

                            $descuentoMayor = $accesorios_venta_mayor->sum('total') - $accesorios_cobrado_mayor->sum('total');
                            $descuento = $accesorios_venta->sum('total') - $accesorios_cobrado->sum('total');

                            $descuentoTotal = $descuentoMayor + $descuento;

                            $total += ($accesorios_cobrado->sum('total') + $accesorios_cobrado_mayor->sum('total'));
                            $totalAcumulado += ($accesorios_cobrado->sum('total') + $accesorios_cobrado_mayor->sum('total'));
                        @endphp
                        <td>{{ $accesorios_venta->sum('total') + $accesorios_venta_mayor->sum('total') }}</td>
                        <td>{{ $descuentoTotal }}</td>
                        <td>{{ $total }} </td>
                    @endforeach
                    <td>{{ $totalAcumulado }}</td>
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
                orientation: 'vertical',
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