<div class="card">
    <div class="card-body">
        <h4 class="card-title">Historal de Ventas de {{ $name }}</h4>
        <div id="morris-bar-chart" height="150" width="300"></div>
    </div>
</div>
<script>
$(function () {
    "use strict";   
    // Morris bar chart
    Morris.Bar({
        element: 'morris-bar-chart',
        data: [
        @php
            $nro = count($grafico_mes);
            for ($i=$nro-1; $i >= 0 ; $i--) { 
        @endphp 
        {
            y: 'dic-2006',
            a: {{ $grafico_mes[$i]->total_vendido}},
            b: {{ $grafico_mes[$i]->alcance_max}}
        },
        @php
            }
        @endphp
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Vendido', 'Meta'],
        barColors:['#ffbc34', '#39c449'],
        hideHover: 'auto',
        gridLineColor: '#eef0f2',
        resize: true
    });
});
</script>

