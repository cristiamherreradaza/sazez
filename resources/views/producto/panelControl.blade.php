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
                        <h2 class="text-white">20</h2>
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
                        <h2 class="text-white">140</h2>
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
                        <h2 class="text-white">600</h2>
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
                        <h2 class="text-white">7200</h2>
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

<!-- Row -->
<div class="col-lg-12">
    <div class="card">
        <div class="card-body analytics-info">
            <h4 class="card-title">Registro Anual de Ventas</h4>
            <div id="basic-bar" style="height:400px;"></div>
        </div>
    </div>
</div>
<!-- end Row -->

{{-- row  --}}
<div class="row">
    <div class="col-lg-12 col-xl-6">
        <div class="card">
            <div class="card-body analytics-info">
                <h4 class="card-title">Productos mas Vendidos</h4>
                <div id="nested-pie" style="height:400px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-md-flex no-block">
                    <div>
                        <h4 class="card-title">Productos con Bajo Stock</h4>
                        {{-- <h6 class="card-subtitle">Check the monthly sales </h6> --}}
                    </div>
                    <div class="ml-auto">
                        <select class="custom-select">
                            <option selected="">Junio</option>
                            {{-- <option value="1">February</option>
                            <option value="2">May</option>
                            <option value="3">April</option> --}}
                        </select>
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
                            <th class="border-0">STOCK OPTIMO</th>
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
                        <tr>
                            <td class="text-center">1</td>
                            <td class="txt-oflo">XIA-Rou-Rou-1</td>
                            <td class="txt-oflo">Router 4C (DVB4209CN)</td>
                            <td><span class="badge badge-success py-1">20</span> </td>
                            <td><span class="text-danger">3</span></td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td class="txt-oflo">XIA-Por-Red-5</td>
                            <td class="txt-oflo">Redmi 20000 mAh (PB200LZM)</td>
                            <td><span class="badge badge-success py-1">30</span> </td>
                            <td><span class="text-danger">5</span></td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td class="txt-oflo">SN-Cab-Cab-46</td>
                            <td class="txt-oflo">Cable Adaptador OTG TipoC 3.0</td>
                            <td><span class="badge badge-success py-1">150</span> </td>
                            <td><span class="text-danger">6</span></td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td class="txt-oflo">XIA-Cab-Cab-50</td>
                            <td class="txt-oflo">Cable DC62 (Iphone)</td>
                            <td><span class="badge badge-success py-1">200</span> </td>
                            <td><span class="text-danger">8</span></td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td class="txt-oflo">Hoc-Cam-Tar-98</td>
                            <td class="txt-oflo">Tarjeta de Memoria Micro SD 16gb Hoco</td>
                            <td><span class="badge badge-success py-1">300</span> </td>
                            <td><span class="text-danger">9</span></td>
                        </tr>
                        <tr>
                            <td class="text-center">6</td>
                            <td class="txt-oflo">SAM-Acc-Fla-112</td>
                            <td class="txt-oflo">Flash 32gb Bar plus Samsung</td>
                            <td><span class="badge badge-success py-1">100</span> </td>
                            <td><span class="text-warning">10</span></td>
                        </tr>
                        <tr>
                            <td class="text-center">7</td>
                            <td class="txt-oflo">SEA-Dis-Dis-320</td>
                            <td class="txt-oflo">Disco Duro Externo 1tb (SRD0VN2)</td>
                            <td><span class="badge badge-success py-1">30</span> </td>
                            <td><span class="text-warning">12</span></td>
                        </tr>
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
<Script>
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
                    data:['Venta Normal','Venta por Mayor']
                },
                toolbox: {
                    show : true,
                    feature : {

                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                color: ["#009efb", "#7460ee"],
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : ['Jul','Ago','Sept','Oct','Nov','Dic','Ene','Feb','Mar','Abr','May','Jun']
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
                        data:[400.0, 450.0, 435.0, 500.0, 300.0, 280.0, 290.0, 350.0, 380.0, 250.0, 200.0, 100.0],
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
                        name:'Venta por Mayor',
                        type:'bar',
                        data:[350.0, 400.0, 385.0, 400.0, 250.0, 290.0, 310.0, 380.0, 400.0, 290.0, 190.0, 50.0],
                        markPoint : {
                            data : [
                                {name : 'The highest year', value : 182.2, xAxis: 7, yAxis: 183, symbolSize:18},
                                {name : 'Year minimum', value : 2.3, xAxis: 11, yAxis: 3}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name : 'Average'}
                            ]
                        }
                    }
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
                    data: ['Porta Celular (Para auto)','Cable 6681 (TipoC 2m)','Parlante UF-1705','Selfie Stick','Reloj Smart Band 4','Pilas ZI5 (PB401)','MI Box S','Mouse Game']
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
                            {value: 535, name: 'Pilas ZI5'},
                            {value: 700, name: 'MI Box S'},
                            {value: 950, name: 'Mouse Game'}
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
                            {value: 505, name: 'Porta Celular (Para auto)'},
                            {value: 150, name: 'Cable 6681 (TipoC 2m)'},
                            {value: 320, name: 'Parlante UF-1705'},
                            {value: 350, name: 'Selfie Stick'},
                            {value: 400, name: 'Reloj Smart Band 4'},
                            {value: 535, name: 'Pilas ZI5 (PB401)'},
                            {value: 700, name: 'MI Box S'},
                            {value: 950, name: 'Mouse Game'}
                        ]
                    }
                ]
        };    
        nestedChart.setOption(option);
       
        });
       
</Script> 
@endsection