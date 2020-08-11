<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li>
                    <!-- User profile -->
                    <div class="user-profile text-center position-relative pt-4 mt-1">
                        <!-- User profile image -->
                        <div class="profile-img m-auto">
                            @if(auth()->user()->image)
                                <img src="{{ asset('assets/images/users/'.auth()->user()->image) }}" alt="user" class="w-100 rounded-circle">
                            @else
                                <img src="{{ asset('assets/images/users/usuario.png') }}" alt="user" class="w-100 rounded-circle">
                            @endif
                        </div>
                        <!-- User profile text-->
                        <div class="profile-text py-1 text-white">
                            {{ auth()->user()->name }}  
                        </div>
                    </div>
                    <!-- End User profile text-->
                </li>
                <!-- User Profile-->
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">ADMINISTRACION</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('Producto/panelControl') }}" aria-expanded="false">
                        <i data-feather="pie-chart" class="feather-icon"></i><span class="hide-menu"> DATOS </span>
                    </a>
                </li>

                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="package" class="feather-icon"></i><span class="hide-menu"> PRODUCTOS </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        @if(auth()->user()->rol == 'Administrador')
                        <li class="sidebar-item">
                            <a href="{{ url('Producto/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        @endif
                        <li class="sidebar-item">
                            <a href="{{ url('Producto/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                        @if(auth()->user()->rol == 'Administrador')
                        <li class="sidebar-item">
                            <a href="{{ url('Movimiento/ingreso') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Ingreso </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="shopping-cart" class="feather-icon"></i><span class="hide-menu"> VENTAS </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Venta/tienda') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item">
                            <a href="{{ url('Venta/mayorista') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Mayorista </span>
                            </a>
                        </li> --}}
                        <li class="sidebar-item">
                            <a href="{{ url('Venta/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if(auth()->user()->rol == 'Administrador')
                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="star" class="feather-icon"></i><span class="hide-menu"> PROMOS </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Combo/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Combo/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                

                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> PEDIDOS </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Pedido/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Pedido/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="share" class="feather-icon"></i><span class="hide-menu"> ENVIOS </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Envio/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Envio/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="share" class="feather-icon"></i><span class="hide-menu"> CUPONES </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Cupon/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Cupon/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if(auth()->user()->rol == 'Administrador')
                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="settings" class="feather-icon"></i><span class="hide-menu"> CONFIGURACIONES </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Almacen/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Almacenes </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Categoria/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Categorias </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Cliente/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Clientes </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Escala/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Escalas </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Escala/grupo_escala') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Escalas Grupales </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Producto/garantia') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Garantias </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Marca/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Marcas </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Proveedor/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Proveedores </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Tipo/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Tipos </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('User/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Usuarios </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Configuracione/listadoEliminaVenta') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Motivos Eliminacion</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Configuracione/listadoDevolucionProducto') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Motivos Devolucion</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(auth()->user()->rol == 'Administrador')
                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="star" class="feather-icon"></i><span class="hide-menu"> REPORTES </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Tienda/reporte_tienda') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Reporte de Tienda </span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="nav-devider"></li>
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Otros</span>
                </li>
                <li class="sidebar-item"> 
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url('Producto/info') }}" aria-expanded="false">
                        <i data-feather="codepen" class="feather-icon"></i><span class="hide-menu">Informacion</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item-->
        <a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
        <!-- item-->
        <a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
        <!-- item-->
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="link" data-toggle="tooltip" title="Cerrar Sesión"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>