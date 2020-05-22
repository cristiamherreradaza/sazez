@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
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
        <!-- Column -->
        <div class="col-md-12 col-lg-4 col-xlg-3">
            <div class="card">
                <div class="card-body">
                    <center class="mt-4"> 
                        @if(auth()->user()->image)
                            <img src="{{ auth()->user()->image }}" class="img-circle" width="150">
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
                            <form action="{{ url('User/actualizar') }}" method="POST" class="form-horizontal form-material">
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
                                    <label class="col-md-12">Contraseña</label>
                                    <div class="col-md-12">
                                        <input name="password" id="password" type="password" class="form-control form-control-line" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirmar Contraseña</label>
                                    <div class="col-md-12">
                                        <input name="confirm_password" id="confirm_password" type="password" class="form-control form-control-line" required>
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
                                        <a href="{{ url('home') }}" class="btn btn-info" >Volver</a>
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
</div>




@stop

@section('js')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script>
    $(function () {
        $('#myTable').DataTable();
        // responsive table
        $('#config-table').DataTable({
            responsive: true
        });
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });

        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    });

</script>
<script>
    function nuevo_usuario()
    {
        $("#modal_usuarios").modal('show');
    }

    function guardar_usuario()
    {
        var nombre_usuario = $("#nombre_usuario").val();
        var rol_usuario = $("#rol_usuario").val();
        var email_usuario = $("#email_usuario").val();
        var password_usuario = $("#password_usuario").val();
        var confirm_password_usuario = $("#confirm_password_usuario").val();
        var almacen_usuario = $("#almacen_usuario").val();

        if(nombre_usuario.length>0 && rol_usuario.length>0 && email_usuario.length>0 && password_usuario.length>0 && confirm_password_usuario.length>0 && password_usuario == confirm_password_usuario){
            Swal.fire(
                'Excelente!',
                'Una nuevo usuario fue registrado.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar los campos: <br>- Nombre <br>- Correo Electrónico <br>- Contraseña <br>- Confirmar Contraseña <br>Y que las contraseñas coincidan',
                'error'
            )
        }
        
    }

    function editar(id, nombre, email, celular, nit, razon_social, rol, almacen)
    {
        $("#id").val(id);
        $("#nombre").val(nombre);
        $("#email").val(email);
        $("#celular").val(celular);
        $("#nit").val(nit);
        $("#razon_social").val(razon_social);
        $("#rol").val(rol);
        $("#almacen").val(almacen);
        $("#editar_usuarios").modal('show');
    }

    function actualizar_usuario()
    {
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var rol = $("#rol").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        if(nombre.length>0 && rol.length>0 && email.length>0 && password.length>0 && confirm_password.length>0 && password==confirm_password){
            Swal.fire(
                'Excelente!',
                'Usuario actualizado correctamente.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar los campos: <br>- Nombre <br>- Correo Electrónico <br>- Contraseña <br>- Confirmar Contraseña <br>Y que las contraseñas coincidan',
                'error'
            )
        }
        
    }

    function eliminar(id, nombre)
    {
        Swal.fire({
            title: 'Quieres borrar ' + nombre + '?',
            text: "Luego no podras recuperarlo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, estoy seguro!',
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Excelente!',
                    'El usuario fue eliminado',
                    'success'
                ).then(function() {
                    window.location.href = "{{ url('User/eliminar') }}/"+id;
                });
            }
        })
    }
</script>
@endsection
