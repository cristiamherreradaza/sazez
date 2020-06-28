@extends('layouts.app')

@section('css')
@endsection

@section('content')


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor mb-0 mt-0">Mi Perfil</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Información de Contacto</a></li>
                <li class="breadcrumb-item active">Fecha: {{ date('d-m-Y') }}</li>
            </ol>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        @if (session('flash'))
            <div class="alert alert-danger col-md-12" role="alert">
                {{ session('flash') }}
            </div>
        @endif
        <!-- Column -->
        <div class="col-md-12 col-lg-4 col-xlg-3">
            <div class="card">
                <div class="card-body">
                    <center class="mt-4"> 
                        @if(auth()->user()->image)
                            <img src="{{ asset('assets/images/users/'.auth()->user()->image) }}" class="img-circle" width="150">
                        @else
                            <img src="{{ asset('assets/images/users/usuario.png') }}" class="img-circle" width="150">
                        @endif    
                        <h4 class="card-title mt-2">{{ auth()->user()->name }}</h4>
                        <h6 class="card-subtitle">Movimientos efectuados</h6>
                        <div class="row text-center justify-content-md-center">
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">254</font></a></div>
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-picture"></i> <font class="font-medium">54</font></a></div>
                        </div>
                    </center>
                </div>
                <div>
                    <hr> </div>
                <div class="card-body"> <small class="text-muted">Rol</small>
                    <h6>{{ auth()->user()->rol }}</h6> <small class="text-muted p-t-30 db">Correo Electrónico</small>
                    <h6>{{ auth()->user()->email }}</h6> <small class="text-muted p-t-30 db">Celular</small>
                    <h6>{{ auth()->user()->celulares }}</h6> <small class="text-muted p-t-30 db">Dirección</small>
                    <h6>71 Pilgrim Avenue Chevy Chase, MD 20815</h6>
                    <div class="map-box">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d470029.1604841957!2d72.29955005258641!3d23.019996818380896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C+Gujarat!5e0!3m2!1sen!2sin!4v1493204785508" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div> 
                    <!-- <small class="text-muted p-t-30 db">Social Profile</small>
                    <br/>
                    <button class="btn btn-circle btn-secondary"><i class="fab fa-facebook"></i></button>
                    <button class="btn btn-circle btn-secondary"><i class="fab fa-twitter"></i></button>
                    <button class="btn btn-circle btn-secondary"><i class="fab fa-youtube"></i></button> -->
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-md-12 col-lg-8 col-xlg-9">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <!-- <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a> </li> -->
                    <!-- <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Perfil</a> </li> -->
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#settings" role="tab">Edición</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- <div class="tab-pane active" id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <div class="sl-item">
                                    <div class="sl-left"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle" /> </div>
                                    <div class="sl-right">
                                        <div><a href="#" class="link">John Doe</a> <span class="sl-date">5 minutes ago</span>
                                            <p>assign a new task <a href="#"> Design weblayout</a></p>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 mb-3"><img src="../assets/images/big/img1.jpg" class="img-responsive radius" /></div>
                                                <div class="col-lg-3 col-md-6 mb-3"><img src="../assets/images/big/img2.jpg" class="img-responsive radius" /></div>
                                                <div class="col-lg-3 col-md-6 mb-3"><img src="../assets/images/big/img3.jpg" class="img-responsive radius" /></div>
                                                <div class="col-lg-3 col-md-6 mb-3"><img src="../assets/images/big/img4.jpg" class="img-responsive radius" /></div>
                                            </div>
                                            <div class="like-comm"> <a href="javascript:void(0)" class="link mr-2">2 comment</a> <a href="javascript:void(0)" class="link mr-2"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="sl-item">
                                    <div class="sl-left"> <img src="../assets/images/users/2.jpg" alt="user" class="img-circle" /> </div>
                                    <div class="sl-right">
                                        <div> <a href="#" class="link">John Doe</a> <span class="sl-date">5 minutes ago</span>
                                            <div class="mt-3 row">
                                                <div class="col-md-3 col-xs-12"><img src="../assets/images/big/img1.jpg" alt="user" class="img-responsive radius" /></div>
                                                <div class="col-md-9 col-xs-12">
                                                    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. </p> <a href="#" class="btn btn-success"> Design weblayout</a></div>
                                            </div>
                                            <div class="like-comm mt-3"> <a href="javascript:void(0)" class="link mr-2">2 comment</a> <a href="javascript:void(0)" class="link mr-2"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="sl-item">
                                    <div class="sl-left"> <img src="../assets/images/users/3.jpg" alt="user" class="img-circle" /> </div>
                                    <div class="sl-right">
                                        <div><a href="#" class="link">John Doe</a> <span class="sl-date">5 minutes ago</span>
                                            <p class="mt-2"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper </p>
                                        </div>
                                        <div class="like-comm mt-3"> <a href="javascript:void(0)" class="link mr-2">2 comment</a> <a href="javascript:void(0)" class="link mr-2"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="sl-item">
                                    <div class="sl-left"> <img src="../assets/images/users/4.jpg" alt="user" class="img-circle" /> </div>
                                    <div class="sl-right">
                                        <div><a href="#" class="link">John Doe</a> <span class="sl-date">5 minutes ago</span>
                                            <blockquote class="mt-2">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!--second tab-->
                    <!-- <div class="tab-pane active" id="profile" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-xs-6 border-right"> <strong>Nombre Completo</strong>
                                    <br>
                                    <p class="text-muted">{{ auth()->user()->name }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 border-right"> <strong>Celular(es)</strong>
                                    <br>
                                    <p class="text-muted">{{ auth()->user()->celulares }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6 border-right"> <strong>Correo Electrónico</strong>
                                    <br>
                                    <p class="text-muted">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="col-md-3 col-xs-6"> <strong>Rol</strong>
                                    <br>
                                    <p class="text-muted">{{ auth()->user()->rol }}</p>
                                </div>
                            </div>
                            <hr>
                            <p class="mt-4">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries </p>
                            <p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <h4 class="font-medium mt-4">Skill Set</h4>
                            <hr>
                            <h5 class="mt-4">Wordpress <span class="float-right">80%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%; height:6px;"> <span class="sr-only">50% Complete</span> </div>
                            </div>
                            <h5 class="mt-4">HTML 5 <span class="float-right">90%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:90%; height:6px;"> <span class="sr-only">50% Complete</span> </div>
                            </div>
                            <h5 class="mt-4">jQuery <span class="float-right">50%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%; height:6px;"> <span class="sr-only">50% Complete</span> </div>
                            </div>
                            <h5 class="mt-4">Photoshop <span class="float-right">70%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:6px;"> <span class="sr-only">50% Complete</span> </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="tab-pane active" id="settings" role="tabpanel">
                        <div class="card-body">
                            <form action="{{ url('User/actualizar') }}" class="needs-validation form-horizontal form-material" method="POST" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ auth()->user()->id }}">
                                <div class="form-group">
                                    <label class="col-md-12">Nombre completo</label>
                                    <div class="col-md-12">
                                        <input name="nombre" id="nombre" type="text" value="{{ auth()->user()->name }}" class="form-control form-control-line" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Correo Electrónico</label>
                                    <div class="col-md-12">
                                        <input name="email" id="email" type="email" value="{{ auth()->user()->email }}" class="form-control form-control-line" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Celular(es)</label>
                                    <div class="col-md-12">
                                        <input name="celular" id="celular" type="text" value="{{ auth()->user()->celulares }}" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Razón Social</label>
                                    <div class="col-md-12">
                                        <input name="razon_social" id="razon_social" type="text" value="{{ auth()->user()->razon_social }}" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Nit</label>
                                    <div class="col-md-12">
                                        <input name="nit" id="nit" type="text" value="{{ auth()->user()->nit }}" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-success" onclick="actualizar_usuario()">Actualizar Perfil</button>
                                        <button type="button" class="btn btn-info" onclick="contrasena()">Cambiar contrase&nacute;a</button>
                                        <a href="{{ url('home') }}" class="btn btn-primary" >Volver</a>
                                    </div>
                                </div>
                            </form>
                            <form method="post" action="{{ url('User/actualizarImagen') }}" class="needs-validation form-horizontal form-material" novalidate enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_usuario" id="id_usuario" value="{{ auth()->user()->id }}">

                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" id="documento" name="documento" required>                                        
                                        <div class="form-control-focus"> </div>
                                        <label class="custom-file-label form-control" for="inputGroupFile04">Cambiar foto de perfil</label>
                                        
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary text-white" type="submit" id="inputGroupFileAddon04">Actualizar Foto</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->


<!-- inicio modal cambiar contrasena -->
<div id="password_usuarios" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">CAMBIAR CONTRASE&Ntilde;A</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ url('User/password') }}" class="needs-validation" method="POST" novalidate>
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_password" id="id_password" value="{{ auth()->user()->id }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Contraseña</label>
                                <span class="text-danger">
                                    <i class="mr-2 mdi mdi-alert-circle"></i>
                                </span>
                                <input name="password" type="password" id="password" class="form-control" minlength="8" placeholder="Debe tener al menos 8 digitos" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_password()">ACTUALIZAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal cambiar contrasena -->
</div>




@stop

@section('js')
<script>
    function actualizar_usuario()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var rol = $("#rol").val();
        var email = $("#email").val();
        if(nombre.length>0 && rol.length>0 && email.length>0){
            Swal.fire(
                'Excelente!',
                'Usuario actualizado correctamente.',
                'success'
            )
        }
    }

    function contrasena()
    {
        $("#password_usuarios").modal('show');
    }

    function actualizar_password()
    {
        var password = $("#password").val();
        if(password.length>7){
            Swal.fire(
                'Excelente!',
                'Contraseña cambiada.',
                'success'
            )
        }
    }
</script>
@endsection
