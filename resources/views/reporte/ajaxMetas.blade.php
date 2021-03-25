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
                $datos = array();

                for($i=1; $i<=12; $i++)
                {
                    $metasUsuarioMarzo = App\Meta::where('numero_mes', $i)
                                    ->where('user_id', $v->id)
                                    ->first();

                    $totalVentasMarzo = App\Venta::select(Illuminate\Support\Facades\DB::raw('SUM(total) as total'))
                            ->whereMonth('fecha', '=', $i)
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

                    $datos[$i]['meta'] = $metaMarzo; 
                    $datos[$i]['porcentaje'] = $porcentajeMarzo; 
                    $datos[$i]['faltante'] = $faltanteMarzo; 
                    $datos[$i]['totalVentas'] = $totalVentasMarzo->total; 
                }
                // dd($datos);
            @endphp
            <tr>
                <td>{{ $v->name }}</td>
                <td>{{ $v->almacen->nombre }}</td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[1]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[1]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[1]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[1]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[2]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[2]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[2]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[2]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[3]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[3]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[3]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[3]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[4]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[4]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[4]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[4]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[5]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[5]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[5]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[5]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[6]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[6]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[6]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[6]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[7]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[7]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[7]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[7]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[8]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[8]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[8]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[8]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[9]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[9]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[9]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[9]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[10]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[10]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[10]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[10]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[11]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[11]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[11]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[11]['porcentaje'] }} %</span>
                </td>
                <td class="text-right">
                    <div class="metas" style="display: none;">
                        <b>META:</b> <span class="text-right">{{ number_format($datos[12]['meta'], 0) }}</span><br>
                        <b>LOGRADO:</b> {{ number_format($datos[12]['totalVentas'], 0) }}<br>
                        <b>FALTANTE:</b> {{ number_format($datos[12]['faltante'], 0) }}<br />
                    </div>
                    <span class="font-weight-bold">{{ $datos[12]['porcentaje'] }} %</span>
                </td>                            
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function detalleMetas()
    {
        $(".metas").toggle('slow');
    }

    $(function () {
        $('#tabla-usuarios').DataTable({
           
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
        });
    });
</script>