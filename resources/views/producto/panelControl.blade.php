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
                        <h2 class="text-white">120</h2>
                        <h6 class="text-white">Ventas Diarias</h6>
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
                        <h2 class="text-white">150</h2>
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
                        <h2 class="text-white">450</h2>
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
                        <h2 class="text-white">100</h2>
                        <h6 class="text-white">Ventas Anuales</h6>
                    </div>
                    <div class="ml-auto">
                        <span class="text-white display-6"><i class="ti-stats-down"></i></span>
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
            <h4 class="card-title">Bsic Bar Chart</h4>
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
                <h4 class="card-title">Nested Pie Chart</h4>
                <div id="nested-pie" style="height:400px;"></div>
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
                    data:['Site A','Site B']
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
                        data : ['Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sept','Oct','Nov','Dec']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'Site A',
                        type:'bar',
                        data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3],
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
                        name:'Site B',
                        type:'bar',
                        data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],
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
                    data: ['Italy','Spain','Austria','Germany','Poland','Denmark','Hungary','Portugal','France','Netherlands']
                },

                // Add custom colors
                color: ['#ffbc34', '#7460ee', '#212529', '#f62d51', '#009efb'],

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
                        name: 'Countries',
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
                            {value: 535, name: 'Italy'},
                            {value: 679, name: 'Spain'},
                            {value: 1548, name: 'Austria'}
                        ]
                    },

                    // Outer
                    {
                        name: 'Countries',
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
                            {value: 535, name: 'Italy'},
                            {value: 310, name: 'Germany'},
                            {value: 234, name: 'Poland'},
                            {value: 135, name: 'Denmark'},
                            {value: 948, name: 'Hungary'},
                            {value: 251, name: 'Portugal'},
                            {value: 147, name: 'France'},
                            {value: 202, name: 'Netherlands'}
                        ]
                    }
                ]
        };    
        nestedChart.setOption(option);
       
        });
       
</Script> 
@endsection