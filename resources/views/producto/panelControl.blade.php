@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
    <!-- chartist CSS -->
    <link href="{{ asset('assets/plugins/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/chartist-js/dist/chartist-init.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/css-chart/css-chart.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ventas Diarias</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="ti-arrow-up text-success"></i> $120</h2>
                    <span class="text-muted">Ingresos</span>
                </div>
                <span class="text-success">80%</span>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 80%; height: 6px;"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ventas Semanales</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="ti-arrow-up text-info"></i> $5,000</h2>
                    <span class="text-muted">Ingresos</span>
                </div>
                <span class="text-info">30%</span>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 30%; height: 6px;"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ventas Mensuales</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="ti-arrow-up text-purple"></i> $8,000</h2>
                    <span class="text-muted">Ingresos</span>
                </div>
                <span class="text-purple">60%</span>
                <div class="progress">
                    <div class="progress-bar bg-purple" role="progressbar" style="width: 60%; height: 6px;"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ventas Anuales</h4>
                <div class="text-right">
                    <h2 class="font-light mb-0"><i class="ti-arrow-down text-danger"></i> $12,000</h2>
                    <span class="text-muted">Ingresos</span>
                </div>
                <span class="text-danger">80%</span>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 80%; height: 6px;"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>

<!-- Row -->
<div class="row">
    <!-- Column -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-wrap">
                            <div>
                                <h3>Revenue Statistics</h3>
                                <h6 class="card-subtitle">January 2019</h6>
                            </div>
                            <div class="ml-auto ">
                                <ul class="list-inline">
                                    <li>
                                        <h6 class="text-muted"><i class="fa fa-circle mr-1 text-success"></i>Product A
                                        </h6>
                                    </li>
                                    <li>
                                        <h6 class="text-muted"><i class="fa fa-circle mr-1 text-info"></i>Product B</h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="total-revenue4" style="height: 350px;"></div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h1 class="mb-0 font-light">$54578</h1>
                        <h6 class="text-muted">Total Revenue</h6>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h1 class="mb-0 font-light">$43451</h1>
                        <h6 class="text-muted">Online Revenue</h6>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h1 class="mb-0 font-light">$44578</h1>
                        <h6 class="text-muted">Product A</h6>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h1 class="mb-0 font-light">$12578</h1>
                        <h6 class="text-muted">Product B</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row -->
<!-- Row -->
<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ventas of the Month</h4>
                <div id="Ventas-donute" style="width:100%; height:300px;"></div>
                <div class="round-overlap mt-3"><i class="mdi mdi-cart"></i></div>
                <ul class="list-inline mt-4 text-center">
                    <li><i class="fa fa-circle text-purple"></i> Item A</li>
                    <li><i class="fa fa-circle text-success"></i> Item B</li>
                    <li><i class="fa fa-circle text-info"></i> Item C</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ventas Prediction</h4>
                        <div class="d-flex flex-row">
                            <div class="align-self-center">
                                <span class="display-6">$3528</span>
                                <h6 class="text-muted">(150-165 Ventas)</h6>
                            </div>
                            <div class="ml-auto">
                                <div id="gauge-chart" style=" width:150px; height:150px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ventas Difference</h4>
                        <div class="d-flex flex-row">
                            <div class="align-self-center">
                                <span class="display-6">$4316</span>
                                <h6 class="text-muted">(150-165 Ventas)</h6>
                            </div>
                            <div class="ml-auto">
                                <div class="ct-chart" style="width:120px; height: 120px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class=""><img src="../assets/images/users/1.jpg" alt="user" class="img-circle" width="100">
                    </div>
                    <div class="pl-3">
                        <h3 class="font-medium">Daniel Kristeen</h3>
                        <h6>UIUX Designer</h6>
                        <button class="btn btn-success"><i class="ti-plus"></i> Follow</button>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col border-right">
                        <h2 class="font-light">14</h2>
                        <h6>Photos</h6>
                    </div>
                    <div class="col border-right">
                        <h2 class="font-light">54</h2>
                        <h6>Videos</h6>
                    </div>
                    <div class="col">
                        <h2 class="font-light">145</h2>
                        <h6>Tasks</h6>
                    </div>
                </div>
            </div>
            <div>
                <hr>
            </div>
            <div class="card-body">
                <p class="text-center aboutscroll">
                    Lorem ipsum dolor sit ametetur adipisicing elit, sed do eiusmod tempor incididunt adipisicing elit,
                    sed do eiusmod tempor incididunLorem ipsum dolor sit ametetur adipisicing elit, sed do eiusmod
                    tempor incididuntt
                </p>
                <ul class="list-icons d-flex flex-item text-center pt-2">
                    <li class="col"><a href="javascript:void(0)" data-toggle="tooltip" title=""
                            data-original-title="Website"><i class="fa fa-globe font-20"></i></a></li>
                    <li class="col"><a href="javascript:void(0)" data-toggle="tooltip" title=""
                            data-original-title="twitter"><i class="fab fa-twitter font-20"></i></a></li>
                    <li class="col"><a href="javascript:void(0)" data-toggle="tooltip" title=""
                            data-original-title="Facebook"><i class="fab fa-facebook-square font-20"></i></a></li>
                    <li class="col"><a href="javascript:void(0)" data-toggle="tooltip" title=""
                            data-original-title="Youtube"><i class="fab fa-youtube font-20"></i></a></li>
                    <li class="col"><a href="javascript:void(0)" data-toggle="tooltip" title=""
                            data-original-title="Linkd-in"><i class="fab fa-linkedin font-20"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Row -->
@stop

@section('js')
<!-- chartist chart -->
<script src="{{ asset('assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
<!-- Chart JS -->
<script src="{{ asset('assets/plugins/echarts/echarts-all.js') }}"></script>
<script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
<!-- Chart JS -->
<script src="{{ asset('js/dashboard1.js') }}"></script>
<script src="{{ asset('js/toastr.js') }}"></script>
<script>
    $.toast({
        heading: 'Welcome to Monster admin',
        text: 'Use the predefined ones, or specify a custom position object.',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'info',
        hideAfter: 3000,
        stack: 6
    });
</script>
@endsection