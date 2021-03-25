<div class="table-responsive m-t-40">
    <table id="tabla-usuarios" class="table table-striped table-bordered no-wrap">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tienda</th>
                <th>Enero</th>
                <th>Febrero</th>
                <th>Marzo</th>
                <th>Abril</th>
                <th>Mayo</th>
                <th>Junio</th>
                <th>Julio</th>
                <th>Agosto</th>
                <th>Septiembre</th>
                <th>Octubre</th>
                <th>Noviembre</th>
                <th>Diciembre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendedores as $v)
            @php
                // para enero
                $metasUsuarioEnero = App\Meta::where('numero_mes', 1)
                                ->where('user_id', $v->id)
                                ->first();

                $totalVentasMarzo = App\Venta::select(Illuminate\Support\Facades\DB::raw('SUM(total) as total'))
                        ->whereMonth('fecha', '=', 1)
                        ->whereYear('fecha', '=', $gestion)
                        ->where('user_id', $v->id)
                        ->first();

                if ($metasUsuarioEnero) {

                    $metaMarzo = $metasUsuarioEnero->meta;
                    $porcentajeMarzo = ((float)$totalVentasMarzo->total * 100) / $metaMarzo;
                    $porcentajeMarzo = round($porcentajeMarzo, 0); 
                    $calculaFaltanteMarzo = $metaMarzo - $totalVentasMarzo->total;
                    $faltanteMarzo = ($calculaFaltanteMarzo < 1)?0:$calculaFaltanteMarzo;
                    

                }else{

                    $metaMarzo = 0;
                    $porcentajeMarzo = 0;
                    $faltanteMarzo = 0;
                }

                // para marzo
                $metasUsuarioMarzo = App\Meta::where('numero_mes', 3)
                                ->where('user_id', $v->id)
                                ->first();

                $totalVentasMarzo = App\Venta::select(Illuminate\Support\Facades\DB::raw('SUM(total) as total'))
                        ->whereMonth('fecha', '=', 1)
                        ->whereYear('fecha', '=', $gestion)
                        ->where('user_id', $v->id)
                        ->first();

                if ($metasUsuarioMarzo) {

                    $metaMarzo = $metasUsuarioMarzo->meta;
                    $porcentajeMarzo = ((float)$totalVentasMarzo->total * 100) / $metaMarzo;
                    $porcentajeMarzo = round($porcentajeMarzo, 0); 
                    $calculaFaltanteMarzo = $metaMarzo - $totalVentasMarzo->total;
                    $faltanteMarzo = ($calculaFaltanteMarzo < 1)?0:$calculaFaltanteMarzo;
                    

                }else{

                    $metaMarzo = 0;
                    $porcentajeMarzo = 0;
                    $faltanteMarzo = 0;
                }

            @endphp
                <tr>
                    <td>{{ $v->name }}</td>
                    <td>{{ $v->almacen->nombre }}</td>
                    <td>
                        
                    </td>
                    <td>Febrero</td>
                    <td class="text-right">
                        <div class="metasMarzo" style="display: none;">
                            <b>META:</b> <span class="text-right">{{ number_format($metaMarzo, 0) }}</span><br>
                            <b>LOGRADO:</b> {{ number_format($totalVentasMarzo->total, 0) }}<br>
                            <b>FALTANTE:</b> {{ number_format($faltanteMarzo, 0) }}<br />
                        </div>
                        <button type="button" onclick="detalleMetasMarzo()" class="btn btn-block btn-info">{{ $porcentajeMarzo }} %</button>
                    </td>
                    <td>Abril</td>
                    <td>Mayo</td>
                    <td>Junio</td>
                    <td>Julio</td>
                    <td>Agosto</td>
                    <td>Septiembre</td>
                    <td>Octubre</td>
                    <td>Noviembre</td>
                    <td>Diciembre</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function detalleMetasMarzo()
    {
        $(".metasMarzo").toggle('slow');
    }

    $(function () {
        $('#tabla-usuarios').DataTable({
           
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>