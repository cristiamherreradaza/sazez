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

                @php
                    $menus = App\Menu::get();
                @endphp
                @foreach($menus as $menu)
                    @php
                        $permiso = App\Menususer::where('menu_id', $menu->id)->where('user_id', auth()->user()->id)->first();
                        $padre='no';
                    @endphp
                    @if($permiso)
                        @foreach($menus as $row)
                            @if($menu->id == $row->padre)
                                @php
                                    $padre='si'
                                @endphp
                            @endif
                        @endforeach
                        @if($padre == 'si')
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                                    <i data-feather="{{ $menu->icono }}" class="feather-icon"></i><span class="hide-menu"> {{ $menu->nombre }} </span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level">
                                @foreach($menus as $hijo)
                                    @if($hijo->padre == $menu->id)
                                        <li class="sidebar-item">
                                            <a href='{{ url("$hijo->direccion") }}' class="sidebar-link">
                                                <i data-feather="{{ $hijo->icono }}" class="feather-icon"></i><span class="hide-menu"> {{ $hijo->nombre }} </span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                </ul>
                            </li>
                        @elseif(is_null($menu->padre))
                            <li class="sidebar-item">
                                <a class="sidebar-link" href='{{ url("$menu->direccion") }}' aria-expanded="false">
                                    <i data-feather="{{ $menu->icono }}" class="feather-icon"></i><span class="hide-menu"> {{ $menu->nombre }} </span>
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach


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