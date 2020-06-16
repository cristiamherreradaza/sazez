
<div class="card">
    <div class="card-body analytics-info">
        <h4 class="card-title">Ventas y Alcances</h4>
        @php
            if (count($alc_us) > 0){
        @endphp
        <div id="bar-basic" style="height:400px;"></div>
        @php
            } else {
        @endphp
        <h1>¡NO EXISTE NINGUN REGISTRO DE VENTA DEL MES Y A&Ntilde;O SELECCIONADO</h1>
        @php
            }
        @endphp
    </div>
</div>
<script>
   $(function() {
     // based on prepared DOM, initialize echarts instance
        var barbasicChart = echarts.init(document.getElementById('bar-basic'));

        var option = {

             // Setup grid
                grid: {
                    x: 60,
                    x2: 40,
                    y: 45,
                    y2: 25
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Vendido', 'Meta']
                },

                // Add custom colors
                color: ['#00CCCC', '#FF6666'],

                // Horizontal axis
                xAxis: [{
                    type: 'value',
                    boundaryGap: [0, 0.01]
                }],

                // Vertical axis
                yAxis: [{
                    type: 'category',
                    data: [
                    @php
                        foreach ($alc_us as $datos) {
                    @endphp
                    '{{ $datos->user->name}}',
                    @php
                        }
                    @endphp
                    ]
                }],

                // Add series
                series : [
                    {
                        name:'Vendido',
                        type:'bar',
                        data:[
                        @php
                            foreach ($alc_us as $datos) {
                        @endphp
                        {{ $datos->total_vendido}},
                        @php
                            }
                        @endphp
                        ]
                    },
                    {
                        name:'Meta',
                        type:'bar',
                        data:[
                        @php
                            foreach ($alc_us as $datos) {
                        @endphp
                        {{ $datos->alcance_max}},
                        @php
                            }
                        @endphp
                        ]
                    }
                ]
        };
        // use configuration item and data specified to show chart
        barbasicChart.setOption(option);
    });
</script>

