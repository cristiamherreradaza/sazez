@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')

@endsection

@section('content')

<!-- Row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h2 class="text-white">{{ $venta_diaria }}</h2>
                        <h6 class="text-white">Venta Diaria</h6>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-notepad"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-cyan">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h2 class="text-white">{{ $venta_semanal }}</h2>
                        <h6 class="text-white">Ventas Semanales</h6>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-clipboard"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h2 class="text-white">{{ $venta_mensual }}</h2>
                        <h6 class="text-white">Ventas Mensuales</h6>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-wallet"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-orange">
            <div class="card-body">
                <div class="d-flex no-block align-items-center">
                    <div class="text-white">
                        <h2 class="text-white">{{ $venta_anual }}</h2>
                        <h6 class="text-white">Ventas Anuales</h6>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-stats-up"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<div class="row">
    @php
        $mesActual = date('m');
        $anioActual = date('Y');
        $usuarioActual = auth()->user()->id;

        switch ($mesActual) {
            case 1:
                $mes = "Enero";
                break;
            case 2:
                $mes = "Febrero";
                break;
            case 3:
                $mes = "Marzo";
                break;
            case 4:
                $mes = "Abril";
                break;
            case 5:
                $mes = "Mayo";
                break;
            case 6:
                $mes = "Junio";
                break;
            case 7:
                $mes = "Julio";
                break;
            case 8:
                $mes = "Agosto";
                break;
            case 9:
                $mes = "Septiembre";
                break;
            case 10:
                $mes = "Octubre";
                break;
            case 11:
                $mes = "Noviembre";
                break;
            case 12:
                $mes = "Diciembre";
                break;
        }

        $metasUsuario = App\Meta::where('mes', $mes)
                        ->where('user_id', $usuarioActual)
                        ->first();

        $totalVentas = App\Venta::select(Illuminate\Support\Facades\DB::raw('SUM(total) as total'))
                ->whereMonth('fecha', '=', $mesActual)
                ->whereYear('fecha', '=', $anioActual)
                ->where('user_id', $usuarioActual)
                ->first();


        if ($metasUsuario) {

            $meta = $metasUsuario->meta;
            $porcentaje = ((float)$totalVentas->total * 100) / $meta;
            $porcentaje = round($porcentaje, 0); 
            $calculaFaltante = $meta - $totalVentas->total;
            $faltante = ($calculaFaltante < 1)?0:$canculaFaltante;
            

        }else{

            $meta = 0;
            $porcentaje = 0;
            $faltante = 0;
        }
        // dd($totalVentas->total);

    @endphp

    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-info">METAS</h4>
                <center>
                    @if ($porcentaje < 20)
                        <img src="{{ asset('assets/images/mitu01.jpg') }}">
                    @elseif($porcentaje > 21 && $porcentaje < 95)
                        <img src="{{ asset('assets/images/mitu50.jpg') }}">
                    @elseif($porcentaje > 96)
                        <img src="{{ asset('assets/images/mitu100.jpg') }}">
                    @endif

                    <h4> {{ $mes }} - {{ date('Y') }} </h4>
                </center>
                <p></p>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentaje }}%; height:" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">{{ $porcentaje }}%</div>
                </div>
                <br>
                <table width="100%">
                    <tr>
                        <td><h5><span class="text-info">META</span></h5></td>
                        <td style="text-align: right;"><h5> {{ number_format($meta, 0) }}</h5></td>
                    </tr>

                    <tr>
                        <td><h5><span class="text-success">LOGRADO</span></h5></td>
                        <td style="text-align: right;"><h5> {{ number_format($totalVentas->total, 0) }}</h5></td>
                    </tr>

                    <tr>
                        <td><h5><span class="text-warning">FALTANTE</span></h5></td>
                        <td style="text-align: right;"><h5> {{ number_format($faltante, 0) }}</h5></td>
                    </tr>

                </table>
                
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-info">VENTAS</h4>
                <div id="basic-bar" style="height:400px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- row  --}}
<div class="row">
    <div class="col-lg-12 col-xl-6">
        <div class="card">
            <div class="card-body analytics-info">
                <h4 class="card-title">Productos mas Vendidos del Mes</h4>
                @php
                    if(!empty($productos_mas_vendidos[0]->id)){
                @endphp
                <div id="nested-pie" style="height:400px;"></div>
                @php
                    } else {
                @endphp
                <div style="height:400px;">
                    <img src="{{ asset('assets/images/imagen.jpg') }}">
                </div>
                @php
                    }
                @endphp
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-md-flex no-block">
                    <div>
                        <h4 class="card-title">Productos con Bajo Stock</h4>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover no-wrap">
                    <thead>
                        <tr>
                            <th class="text-center border-0">#</th>
                            <th class="border-0">CODIGO PROD</th>
                            <th class="border-0">PRODUCTO</th>
                            <th class="border-0">STOCK MINIMO</th>
                            <th class="border-0">STOCK</th>
                        </tr>
                    </thead>
                    <tbody>
                       {{--  <tr>
                            <td class="text-center">1</td>
                            <td class="txt-oflo">Elite admin</td>
                            <td><span class="badge badge-success py-1">SALE</span> </td>
                            <td class="txt-oflo">April 18, 2020</td>
                            <td><span class="text-success">$24</span></td>
                        </tr> --}}
                        @php
                            $n = 1;
                            for ($i=0; $i < 8; $i++) { 
                                if(!empty($stock_productos[$i]->codigo)){
                        @endphp
                        <tr>
                            <td class="text-center">{{ $n++ }}</td>
                            <td class="txt-oflo">{{ $stock_productos[$i]->codigo }}</td>
                            <td class="txt-oflo">{{ $stock_productos[$i]->nombre }}</td>
                            <td><span class="badge badge-success py-1">{{ $stock_productos[$i]->cantidad_minima }}</span> </td>
                            @php 
                                if($stock_productos[$i]->total < 10){
                            @endphp
                            <td><span class="text-danger">{{ $stock_productos[$i]->total }}</span></td>
                            @php
                                } else {
                            @endphp
                            <td><span class="text-warning">{{ $stock_productos[$i]->total }}</span></td>
                            @php     
                                }
                            @endphp
                        </tr>
                        @php
                                } else {
                                        $i = 8;
                                }
                            }
                        @endphp
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- end row  --}}

@stop

@section('js')
<!-- chartist chart -->
<script src="{{ asset('assets/libs/echarts/dist/echarts-en.min.js') }}"></script>
{{-- <script src="{{ asset('dist/js/pages/echarts/line/line-charts.js') }}"></script> --}}
<script>
    $(function() {
    
  // ------------------------------
    // Basic bar chart
    // ------------------------------
    // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('basic-bar'));

        // specify chart configuration item and data
        var option = {
                // Setup grid
                grid: {
                    left: '1%',
                    right: '2%',
                    bottom: '3%',
                    containLabel: true
                },

                // Add Tooltip
                tooltip : {
                    trigger: 'axis'
                },

                legend: {
                    data:['Venta Normal','Venta por Mayor', 'Ventas', "otros1"]
                },
                toolbox: {
                    show : true,
                    feature : {
                        // magicType : {show: true, type: ['line', 'bar']},
                        magicType : {show: true, type: ['bar']},
                        // restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                color: ["#009efb", "#7460ee", "#00CCCC", "#99FF99", "#CCCCFF", "#0066FF"],
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : ['Nov','Dic','Ene','Feb','Mar','Abr','May', 'Jun']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'Venta Normal',
                        type:'bar',
                        data:[400.0, 450.0, 435.0, 500.0, 300.0, 280.0, 290.0, 350.0],
                        markPoint : {
                            data : [
                                {type : 'max', name: 'Max'},
                                {type : 'min', name: 'Min'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name: 'Average'}
                            ]
                        }
                    },
                    @php
                        $nombre = Auth::user()->name;
                        if ($nombre == 'Administrador') {
                    @endphp
                    {
                        name:'Venta por Mayor',
                        type:'bar',
                        data:[350.0, 400.0, 385.0, 400.0, 250.0, 290.0, 310.0, 380.0],
                        markPoint : {
                            data : [
                                {type : 'max', name: 'Max'},
                                {type : 'min', name: 'Min'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name : 'Average'}
                            ]
                        }
                    },
                    
                    {
                        name:'Ventas',
                        type:'bar',
                        data:[400.0, 450.0, 435.0, 500.0, 300.0, 280.0, 290.0, 350.0],
                        markPoint : {
                            data : [
                                {type : 'max', name: 'Max'},
                                {type : 'min', name: 'Min'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name: 'Average'}
                            ]
                        }
                    },

                    {
                        name:'otros1',
                        type:'bar',
                        data:[400.0, 450.0, 435.0, 500.0, 300.0, 280.0, 290.0, 350.0],
                        markPoint : {
                            data : [
                                {type : 'max', name: 'Max'},
                                {type : 'min', name: 'Min'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name: 'Average'}
                            ]
                        }
                    }
                    @php
                        }
                    @endphp
                ]
            };
        // use configuration item and data specified to show chart
        myChart.setOption(option);



        var nestedChart = echarts.init(document.getElementById('nested-pie'));
        var option = {
            
           tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: [
                    @php
                        for($i = 0; $i < 8 ; $i++){
                            if(!empty($productos_mas_vendidos[$i]->id)){
                    @endphp
                        '{{ $productos_mas_vendidos[$i]->codigo }}',
                    @php
                            } else {
                                        $i = 8;
                                }
                       }
                    @endphp
                    ]
                },

                // Add custom colors
                color: ['#ffbc34', '#7460ee', '#f62d51', '#212529', '#009efb', 'green', 'magenta', 'cyan'],

                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    feature: {
                        mark: {
                            show: true,
                            title: {
                                mark: 'Markline switch',
                                markUndo: 'Undo markline',
                                markClear: 'Clear markline'
                            }
                        },
                        dataView: {
                            show: true,
                            readOnly: false,
                            title: 'View data',
                            lang: ['View chart data', 'Close', 'Update']
                        },
                        magicType: {
                            show: true,
                            title: {
                                pie: 'Switch to pies',
                                funnel: 'Switch to funnel',
                            },
                            type: ['pie', 'funnel']
                        },
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Same as image',
                            lang: ['Save']
                        }
                    }
                },

                // Enable drag recalculate
                calculable: false,

                // Add series
                series: [

                    // Inner
                    {
                        name: '3 mas Vendidos',
                        type: 'pie',
                        selectedMode: 'single',
                        radius: [0, '40%'],

                        // for funnel
                        x: '15%',
                        y: '7.5%',
                        width: '40%',
                        height: '85%',
                        funnelAlign: 'right',
                        max: 1548,

                        itemStyle: {
                            normal: {
                                label: {
                                    position: 'inner'
                                },
                                labelLine: {
                                    show: false
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },

                        data: [
                            @php
                                for($i = 0; $i < 3; $i++){
                                    if(!empty($productos_mas_vendidos[$i]->id)){
                            @endphp
                                {value: {{ $productos_mas_vendidos[$i]->nro }}, name: '{{ $productos_mas_vendidos[$i]->codigo }}'},
                            @php
                                    } else {
                                                $i = 8;
                                            }
                                }
                            @endphp
                        ]
                    },

                    // Outer
                    {
                        name: '8 mas Vendidos',
                        type: 'pie',
                        radius: ['60%', '85%'],

                        // for funnel
                        x: '55%',
                        y: '7.5%',
                        width: '35%',
                        height: '85%',
                        funnelAlign: 'left',
                        max: 1048,

                        data: [
                        @php
                            for($i = 0; $i < 8 ; $i++){
                                if(!empty($productos_mas_vendidos[$i]->id)){
                        @endphp
                            {value: {{ $productos_mas_vendidos[$i]->nro }}, name: '{{ $productos_mas_vendidos[$i]->codigo }}'},
                        @php
                                } else {
                                                $i = 8;
                                        }
                            }
                        @endphp
                        ]
                    }
                ]
        };    
        nestedChart.setOption(option);
       
        });
       
</script> 
@endsection