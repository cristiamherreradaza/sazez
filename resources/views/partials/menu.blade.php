<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile">
            <!-- User profile image -->
            <div class="profile-img">
                @if(auth()->user()->image)
                    <img src="{{ auth()->user()->image }}" alt="user">
                @else
                    <img src="{{ asset('assets/images/users/usuario.png') }}" alt="user">
                @endif
            </div>
            <!-- User profile text-->
            <div class="profile-text"> <a href="#" class="dropdown-toggle link u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{ auth()->user()->name }}<span class="caret"></span></a>
                <div class="dropdown-menu animated flipInY">
                    <a href="{{ url('User/perfil') }}" class="dropdown-item"><i class="ti-user"></i> Mi Perfil</a>
                    <!-- <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
                    <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                    <div class="dropdown-divider"></div> <a href="#" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a> -->
                    <div class="dropdown-divider"></div> 
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">PERSONAL</li>
                <li>
                    <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-email"></i><span
                            class="hide-menu">PRODUCTOS</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('Producto/nuevo') }}">Nuevo</a></li>
                        <li><a href="{{ url('Producto/listado') }}">Listado</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow " href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-email"></i><span
                            class="hide-menu">COMBOS</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('Combo/nuevo') }}">Nuevo</a></li>
                        <li><a href="{{ url('Combo/listado') }}">Listado</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow " href="#" aria-expanded="false"><i class="mdi mdi-email"></i><span
                            class="hide-menu">CONFIGURACIONES</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('Almacen/listado') }}">Almacenes</a></li>
                        <li><a href="{{ url('Categoria/listado') }}">Categorias</a></li>
                        <li><a href="{{ url('Cliente/listado') }}">Clientes</a></li>
                        <li><a href="{{ url('Escala/listado') }}">Escalas</a></li>
                        <li><a href="{{ url('Escala/grupo_escala') }}">Grupo Escalas</a></li>
                        <li><a href="{{ url('Marca/listado') }}">Marcas</a></li>
                        <li><a href="{{ url('User/listado') }}">Usuarios</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow " href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-email"></i><span
                            class="hide-menu">PEDIDOS</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('Pedido/nuevo') }}">Nuevo</a></li>
                        <li><a href="{{ url('Pedido/listado') }}">Listado</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow " href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-email"></i><span
                            class="hide-menu">ENVIOS</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ url('Entrega/envio') }}">Subir Excel</a></li>
                        {{-- <li><a href="{{ url('Pedido/listado') }}">Listado</a></li> --}}
                    </ul>
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