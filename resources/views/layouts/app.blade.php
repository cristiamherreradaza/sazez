<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    @yield('metadatos')
    <title>@yield('title')</title>
	<link rel="canonical" href="https://www.wrappixel.com/templates/monsteradmin/" />
    <!-- Custom CSS -->
    @section('css')
    @show
    <link href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        
</head>

<body class="fix-header card-no-border fix-sidebar">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
                    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                        <div class="navbar-header">
                            <!-- This is for the sidebar toggle which is visible on mobile only -->
                            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                                    class="ti-menu ti-close"></i></a>
                            <!-- ============================================================== -->
                            <!-- Logo -->
                            <!-- ============================================================== -->
                            <a class="navbar-brand" href="{{ url('home') }}">
                                <!-- Logo icon -->
                                <b class="logo-icon">
                                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                                    <!-- Dark Logo icon -->
                                    <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                                    <!-- Light Logo icon -->
                                    <img src="{{ asset('assets/images/icon_inicio.png') }}" alt="homepage" class="light-logo" />
                                </b>
                                <!--End Logo icon -->
                                <!-- Logo text -->
                                <span class="logo-text">
                                    <!-- dark Logo text -->
                                    <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                                    <!-- Light Logo text -->
                                    <img src="{{ asset('assets/images/titulo_inicio.png') }}" class="light-logo" alt="homepage" />
                                </span>
                            </a>
                            <!-- ============================================================== -->
                            <!-- End Logo -->
                            <!-- ============================================================== -->
                            <!-- ============================================================== -->
                            <!-- Toggle which is visible on mobile only -->
                            <!-- ============================================================== -->
                            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                                data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                        </div>
                        <!-- ============================================================== -->
                        <!-- End Logo -->
                        <!-- ============================================================== -->
                        <div class="navbar-collapse collapse" id="navbarSupportedContent">
                            <!-- ============================================================== -->
                            <!-- toggle and nav items -->
                            <!-- ============================================================== -->
                            <ul class="navbar-nav float-left mr-auto">
                                <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light"
                                        href="javascript:void(0)" data-sidebartype="mini-sidebar"><i
                                            class="icon-arrow-left-circle"></i></a></li>
                                <!-- ============================================================== -->
                                <!-- Comment -->
                                <!-- ============================================================== -->
                                @if(auth()->user()->rol != 'Cliente')
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-wallet-giftcard"></i>
                                            <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                                        </a>
                                        <div class="dropdown-menu mailbox animated bounceInDown">
                                            <ul class="list-style-none">
                                                <li>
                                                    <div class="font-weight-medium border-bottom rounded-top py-3 px-4">
                                                        Cupones Recientes
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="message-center notifications position-relative" style="height:250px;">
                                                        @php
                                                            $cupones = App\Cupone::where(function ($query) {
                                                                                    $query->where('almacene_id', auth()->user()->almacen->id)
                                                                                        ->orWhere('almacene_id', NULL);
                                                                                })->whereDate('fecha_final', '>=', date('Y-m-d'))
                                                                                ->where('estado', 'Vigente')
                                                                                ->orderBy('id', 'desc')
                                                                                ->take(5)
                                                                                ->get();
                                                        @endphp
                                                        @if(count($cupones) > 0)
                                                            @foreach($cupones as $key => $cupon)
                                                                <a href="{{ url('Cupon/cobra_cupon/'.$cupon->id) }}" class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                                    @php
                                                                        if($cupon->producto_id){
                                                                            $icono = 'cube';
                                                                            $item = $cupon->producto->nombre;
                                                                        }else{
                                                                            $icono = 'cubes';
                                                                            $item = $cupon->combo->nombre;
                                                                        }
                                                                    @endphp
                                                                    <span class="btn btn-info rounded-circle btn-circle">
                                                                        <i class="fas fa-{{ $icono }}"></i>
                                                                    </span>
                                                                    <div class="w-75 d-inline-block v-middle pl-2">
                                                                        <h5 class="message-title mb-0 mt-1 text-info">{{ $cupon->user_id }}</h5>
                                                                        <span class="font-12 text-nowrap d-block text-muted text-truncate">
                                                                            {{ $item }}
                                                                        </span>
                                                                        <span class="font-12 text-nowrap d-block text-danger">
                                                                            Valido hasta {{ $cupon->fecha_final }}
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <a class="nav-link border-top text-center text-dark pt-3" href="{{ url('Cupon/listado') }}">
                                                        <strong>Ver todos los cupones</strong> <i class="fa fa-angle-right"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                                
                                <!-- ============================================================== -->
                                <!-- End Comment -->
                                <!-- ============================================================== -->
                                <!-- ============================================================== -->
                                <!-- Messages -->
                                <!-- ============================================================== -->
                                @if(auth()->user()->rol != 'Cliente')
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-cart-plus"></i>
                                            <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                                        </a>
                                        <div class="dropdown-menu mailbox animated bounceInDown" aria-labelledby="2">
                                            <ul class="list-style-none">
                                                <li>
                                                    <div class="font-weight-medium border-bottom rounded-top py-3 px-4">
                                                        Promociones
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="message-center message-body position-relative" style="height:250px;">
                                                        @php
                                                            $combos = App\Combo::whereDate('fecha_final', '>=', date('Y-m-d'))
                                                                                ->get();
                                                        @endphp
                                                        @if(count($combos)>0)
                                                            @foreach($combos as $combo)
                                                                <a href="javascript:void(0)" class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                                    <span class="btn btn-success rounded-circle btn-circle">
                                                                        <i class="fas fa-star"></i>
                                                                    </span>
                                                                    <div class="w-75 d-inline-block v-middle pl-2">
                                                                        <h5 class="message-title mb-0 mt-1">{{ $combo->nombre }}</h5>
                                                                        @php
                                                                            $cantidad = App\CombosProducto::where('combo_id', $combo->id)->count();
                                                                        @endphp
                                                                        <span class="font-12 text-nowrap d-block text-muted text-truncate">
                                                                            {{ $cantidad }} Producto(s) en promocion
                                                                        </span>
                                                                        <span class="font-12 text-nowrap d-block text-danger">Vigente hasta {{ $combo->fecha_final }}</span>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <a class="nav-link border-top text-center text-dark pt-3" href="{{ url('Combo/listado') }}">
                                                        <b>Ver todas las promociones</b> <i class="fa fa-angle-right"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                                
                                <!-- ============================================================== -->
                                <!-- End Messages -->
                                <!-- ============================================================== -->
                            </ul>
                            <!-- ============================================================== -->
                            <!-- Right side toggle and nav items -->
                            <!-- ============================================================== -->
                            <ul class="navbar-nav float-right">
                                <!-- ============================================================== -->
                                <!-- Search -->
                                <!-- ============================================================== -->
                                <li class="nav-item search-box d-none d-md-block">
                                    <div class="app-search mt-3 mr-2 text-white text-bold"><span id="fechaActual"></span><br />Hora <span id="horaActual"></span></div>
                                    {{-- <form class="app-search mt-3 mr-2">
                                        <input type="text" class="form-control rounded-pill border-0" placeholder="Search for...">
                                        <a class="srh-btn"><i class="ti-search"></i></a>
                                    </form> --}}
                                </li>
                                <!-- ============================================================== -->
                                <!-- User profile and search -->
                                <!-- ============================================================== -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if(auth()->user()->image)
                                            <img src="{{ asset('assets/images/users/'.auth()->user()->image) }}" alt="user" class="rounded-circle" width="31">
                                        @else
                                            <img src="{{ asset('assets/images/users/usuario.png') }}" alt="user" class="rounded-circle" width="31"/>
                                        @endif
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                        <div class="d-flex no-block align-items-center p-3 mb-2 border-bottom">
                                            <div class="">
                                                @if(auth()->user()->image)
                                                    <img src="{{ asset('assets/images/users/'.auth()->user()->image) }}" alt="user" class="rounded" width="80">
                                                @else
                                                    <img src="{{ asset('assets/images/users/usuario.png') }}" alt="user" class="rounded" width="80">
                                                @endif
                                            </div>
                                            <div class="ml-2">
                                                <h4 class="mb-0">{{ auth()->user()->name }}</h4>
                                                <p class=" mb-0">{{ auth()->user()->email }}</p>
                                                <a href="{{ url('User/perfil') }}" class="btn btn-rounded btn-danger btn-sm">Ver Perfil</a>
                                            </div>
                                        </div>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off mr-1 ml-1"></i>
                                            {{ __('CERRAR SESION') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                <!-- ============================================================== -->
                                <!-- User profile and search -->
                                <!-- ============================================================== -->
                            </ul>
                        </div>
                    </nav>
                </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @include('partials.menu')
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                @yield('content')
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © {{ date('Y') }} sazez.net
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>

    {{-- sweet alert --}}
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sweetalert2/sweet-alert.init.js') }}"></script>

    <script>
        // funcion para la validacion del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        // fin funcion para la validacion del formulario

         // script para que todos los formularios pasen con ENTER en vez de TAB
        jQuery(document).ready(function() {
            $('body').on('keydown', 'input, select', function(e) {
            if (e.key === "Enter") {
                var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
                focusable = form.find('input,a,select,button,textarea').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                } else {
                    form.submit();
                }
                return false;
            }
            });

            muestraFechaActual();
            mueveReloj();
        });

        function makeArray() {
            for (i = 0; i < makeArray.arguments.length; i++)
                this[i + 1] = makeArray.arguments[i];
        }

        function muestraFechaActual() {
            var months = new makeArray('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
                'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            var date = new Date();
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var yy = date.getYear();
            var year = (yy < 1000) ? yy + 1900 : yy;
            let fechaActual = "Hoy es " + day + " de " + months[month] + " del " + year;
            $("#fechaActual").html(fechaActual);
        }

        function mueveReloj() {
            momentoActual = new Date()
            hora = momentoActual.getHours()
            minuto = momentoActual.getMinutes()
            segundo = momentoActual.getSeconds()

            horaImprimible = hora + " : " + minuto + " : " + segundo
            $("#horaActual").html(horaImprimible);
            // document.form_reloj.reloj.value = horaImprimible

            setTimeout("mueveReloj()", 1000)
        }

    </script>

    @section('js')
        
    @show
</body>

</html>