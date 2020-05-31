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
                        <div class="profile-img m-auto"> <img src="../assets/images/users/1.jpg" alt="user"
                                class="w-100 rounded-circle" /> </div>
                        <!-- User profile text-->
                        <div class="profile-text py-1"> <a href="#" class="dropdown-toggle link u-dropdown"
                                data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Markarn
                                Doe <span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <a href="#" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                                <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
                                <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                                <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i
                                        class="ti-settings"></i> Account Setting</a>
                                <div class="dropdown-divider"></div> <a href="login.html" class="dropdown-item"><i
                                        class="fa fa-power-off"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                    <!-- End User profile text-->
                </li>
                <!-- User Profile-->
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                        class="hide-menu">ADMINISTRACION</span></li>
                <li class="sidebar-item"> 
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="package" class="feather-icon"></i><span class="hide-menu"> PRODUCTOS </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('Producto/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Producto/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
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
                        <li class="sidebar-item">
                            <a href="{{ url('Venta/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>

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
                            <a href="{{ url('Entrega/nuevo') }}" class="sidebar-link">
                                <i data-feather="plus-circle" class="feather-icon"></i><span class="hide-menu"> Nuevo </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('Pedidos/listado') }}" class="sidebar-link">
                                <i data-feather="list" class="feather-icon"></i><span class="hide-menu"> Listado </span>
                            </a>
                        </li>
                    </ul>
                </li>

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
                            <a href="{{ url('Marca/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Marcas </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('User/listado') }}" class="sidebar-link">
                                <i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu"> Usuarios </span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-devider"></li>
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Otros</span>
                </li>
                <li class="sidebar-item"> 
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../../Documentation/document.html" aria-expanded="false">
                        <i data-feather="codepen" class="feather-icon"></i><span class="hide-menu">Tutoriales</span>
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
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>