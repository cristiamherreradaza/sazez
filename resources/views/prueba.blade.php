@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('content')
<div id="divmsg" style="display:none" class="alert alert-primary" role="alert"></div>
<div class="row">
    <!-- Column -->
    <div class="col-md-12">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="mb-0 text-white">NUEVO ALUMNO</h4>
                    </div>
                    <form action="/persona/guarda" method="post">
                        @csrf
                    <div class="card-body">

                        {{-- datos personales --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-outline-warning">
                                    <div class="card-header">
                                        <h4 class="mb-0 text-white">DATOS PERSONALES</h4>
                                    </div>
                                    <div class="card-body" style="background-color: #fff6d4;">

                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Apellido Materno </label>
                                                            <input type="text" class="form-control"
                                                                name="apellido_paterno" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Apellido Paterno </label>
                                                            <input type="text" class="form-control"
                                                                name="apellido_materno" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Nombres </label>
                                                            <input type="text" class="form-control"
                                                                name="nombres" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Carnet </label>
                                                            <input type="text" class="form-control"
                                                                name="carnet" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Fecha Nacimiento </label>
                                                            <input type="date" class="form-control"
                                                                name="fecha_nacimiento" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Expedido </label>
                                                            <select name="expedido" class="form-control">
                                                                <option value="La Paz">La Paz</option>
                                                                <option value="Cochabamba">Cochabamba</option>
                                                                <option value="Santa Cruz">Santa Cruz</option>
                                                                <option value="Oruro">Oruro</option>
                                                                <option value="Potosi">Potosi</option>
                                                                <option value="Tarija">Tarija</option>
                                                                <option value="Sucre">Sucre</option>
                                                                <option value="Beni">Beni</option>
                                                                <option value="Pando">Pando</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Email </label>
                                                            <input type="text" class="form-control"
                                                                name="email" id="nivel">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Direccion </label>
                                                            <input type="text" class="form-control"
                                                                name="direccion" id="nivel">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Celular </label>
                                                            <input type="text" class="form-control"
                                                                name="telefono_celular" id="nivel">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Genero </label>
                                                            <select name="sexo" class="form-control">
                                                                <option value="Masculino">Masculino</option>
                                                                <option value="Femenino">Femenino</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- fin datos personales --}}

                        {{-- datos profesionales --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-outline-inverse">
                                    <div class="card-header">
                                        <h4 class="mb-0 text-white">DATOS PROFESIONALES</h4>
                                    </div>
                                    <div class="card-body" style="background-color: #ededed;">

                                            <div class="form-body">
                                                <!--/row-->
                                                <!-- NOMBRE DEL ATRIBUTO ENCIMA -->
                                                <div class="row pt-3">
                                                    <div class="col-md-3">
                                                        <div class="form-group has-success">
                                                            <label class="control-label">Trabaja</label>
                                                            <select class="form-control custom-select" id="trabaja" name="trabaja">
                                                                <option value="No">Seleccionar</option>
                                                                <option value="Si">Si</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- row -->
                                                <div class="row pt-3" id="mostrar_ocultar" style="display:none;">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Nombre de la Empresa</label>
                                                            <input type="text" id="empresa" class="form-control" name="empresa">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Direcci&oacute;n de la Empresa</label>
                                                            <input type="text" id="direccion_empresa" class="form-control" name="direccion_empresa">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Telefono de la Empresa</label>
                                                            <input type="text" id="telefono_empresa" class="form-control" name="telefono_empresa">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Fax</label>
                                                            <input type="text" id="fax" class="form-control" name="fax">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="control-label">Email Empresa</label>
                                                            <input type="email" id="email_empresa" class="form-control" name="email_empresa">
                                                        </div>
                                                    </div>
                                                </div>  
                                                <!-- row -->
                                                
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- fin datos profesionales --}}

                        {{-- referencias personales --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-outline-success">
                                    <div class="card-header">
                                        <h4 class="mb-0 text-white">REFERENCIAS PERSONALES</h4>
                                    </div>
                                    <div class="card-body" style="background-color: #e3ffe3;">

                                            <div class="form-body">

                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Nombre Padre </label>
                                                            <input type="text" class="form-control" name="nombre_padre" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Celular Padre </label>
                                                            <input type="text" class="form-control" name="celular_padre" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Nombre Madre </label>
                                                            <input type="text" class="form-control" name="nombre_madre" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Celular Madre </label>
                                                            <input type="text" class="form-control" name="celular_madre" id="nombre">
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                                <div class="row">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Nombre Tutor </label>
                                                            <input type="text" class="form-control" name="nombre_tutor" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Celular Tutor </label>
                                                            <input type="text" class="form-control" name="telefono_tutor" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Nombre Esposo </label>
                                                            <input type="text" class="form-control" name="nombre_esposo" id="nombre">
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Celular Esposo </label>
                                                            <input type="text" class="form-control" name="telefono_esposo" id="nombre">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- fin referencias personales --}}

                        {{-- Carrera --}}
                        <!-- Row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-outline-info">
                                    <div class="card-header">
                                        <h4 class="mb-0 text-white">Datos de la Carrera</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="#" class="form-horizontal">
                                            <div class="form-body">
                                                <!--/row-->
                                                <!-- <h3 class="box-title">Address</h3> -->
                                                <!--/row-->
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-12">
                                                        <div class="form-group row">
                                                            <label class="control-label text-right col-md-3">Carrera</label>
                                                            <div class="col-md-9">
                                                                <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" id="carrera_id" name="carrera_id">
                                                                    <option value="">Seleccionar</option>
                                                                    @foreach($carreras as $carre)
                                                                    <option value="{{ $carre->id }}">{{ $carre->nombre }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-12">
                                                        <div class="form-group row">
                                                            <label class="control-label text-right col-md-3">Turno</label>
                                                            <div class="col-md-7">
                                                                <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" id="turno_id" name="turno_id">
                                                                    <option value="">Seleccionar</option>
                                                                    @foreach($turnos as $tur)
                                                                    <option value="{{ $tur->id }}">{{ $tur->descripcion }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-12">
                                                        <div class="form-group row">
                                                            <label class="control-label text-right col-md-3">Paralelo</label>
                                                            <div class="col-md-6">
                                                                <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" id="paralelo" name="paralelo">
                                                                    <option value="">Seleccionar</option>
                                                                <option value="A">A</option>
                                                                <option value="B">B</option>
                                                                <option value="C">C</option>
                                                                <option value="D">D</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-12">
                                                        <div class="form-group row">
                                                            <label class="control-label text-right col-md-5">Gesti&oacute;n</label>
                                                            <div class="col-md-7">
                                                                <input type="text" class="form-control" id="gestion" name="gestion">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/row-->
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-offset-3 col-md-9">
                                                                <button type="button" class="btn btn-info" onclick="ver();">Ver Asignaturas por Tomar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Row -->
                        {{-- fin Carrera --}}
                        
                        {{-- asignaturas por tomar --}}
                        <!-- Row -->
                        <div class="row">
                            <!-- Column -->
                            <div class="col-lg-4 col-md-6" id="mostrar_asig1" style="display:none;">
                                <div class="card card-inverse card-dark">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="mr-3 align-self-center">
                                                <h1 class="text-white"><i class="icon-graduation"></i></h1></div>
                                            <div>
                                                <h3 class="card-title" id="nom_asig1"> </h3>
                                                <h6 class="card-subtitle" id="gest1"> </h6> </div>
                                        </div>
                                        <div class="row">
                                            <!-- column -->
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table no-wrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>C&oacute;digo</th>
                                                                        <th>Asignatura</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="valor1">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <!-- Column -->
                            <div class="col-lg-4 col-md-6" id="mostrar_asig2" style="display:none;">
                                <div class="card card-inverse card-primary">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="mr-3 align-self-center">
                                                <h1 class="text-white"><i class="icon-notebook"></i></h1></div>
                                            <div>
                                                <h3 class="card-title" id="nom_asig2">Secretariado Administrativo</h3>
                                                <h6 class="card-subtitle" id="gest2"></h6> </div>
                                        </div>
                                        <div class="row">
                                            <!-- column -->
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table no-wrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>C&oacute;digo</th>
                                                                        <th>Asignatura</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="valor2">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <!-- Column -->
                            <div class="col-lg-4 col-md-6" id="mostrar_asig3" style="display:none;">
                                <div class="card card-inverse card-success">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="mr-3 align-self-center">
                                                <h1 class="text-white"><i class="icon-layers"></i></h1></div>
                                            <div>
                                                <h3 class="card-title" id="nom_asig3">Auxiliar Administrativo Financiero</h3>
                                                <h6 class="card-subtitle" id="gest3"> </h6> </div>
                                        </div>
                                        <div class="row">
                                            <!-- column -->
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table no-wrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>C&oacute;digo</th>
                                                                        <th>Asignatura</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="valor3">
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                        </div>
                        <!-- Row -->

                        {{-- fin de asignaturas --}}

                        <div class="row">
                            <div class="col-md-6">
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success">Guardar</button>
                            </div>
                            <!-- <div class="col-md-6">
                            <button type="button" class="btn waves-effect waves-light btn-block btn-inverse">Cancelar</button>
                            </div> -->
                        </div>

                    </div>
                    </form>
                    
                </div>
            </div>
        </div>
        <!-- Row -->
    </div>
    <!-- Column -->
</div>
@stop
@section('js')
<script>
    $('#trabaja').on('change', function(e){
            var trabaja = e.target.value;
            if (trabaja == 'Si') {
                $('#mostrar_ocultar').show('slow');
            }else{
                $('#mostrar_ocultar').hide('slow');
            }
    });
</script>

<script type="text/javascript">
    function mostrarMensaje(mensaje){
        $("#divmsg").empty();
        $("#divmsg").append("<p>"+mensaje+"</p>");
        $("#divmsg").show(500);
        $("#divmsg").hide(5000);
    }
    // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // al hacer clic en el boton GUARDAR, se procedera a la ejecucion de la funcion
    $(".btnenviar").click(function(e){
        e.preventDefault();     // Evita que la página se recargue
        var nombre = $('#nombre').val();    
        var nivel = $('#nivel').val();
        var semestre = $('#semestre').val();

        $.ajax({
            type:'POST',
            url:"{{ url('carrera/store') }}",
            data: {
                nom_carrera : nombre,
                desc_niv : nivel,
                semes : semestre
            },
            success:function(data){
                mostrarMensaje(data.mensaje);
                limpiarCampos();
            }
        });
    });
</script>
<script>
    // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function limpiarCampos1(){
        $("#mostrar_asig1").val('');
        $("#mostrar_asig2").val('');
    }
 
    // al elegir una Carrera, se ira a consultar por las Asignaturas que tiene que tomar en ese "SEMESTRE O AÑO".
    // en caso que sea "Contaduría General", se retorna las asignaturas de "Secretariado Administrativo" y de "Auxiliar Administrativo Financiero"
    function ver(){

        var asig = $('#carrera_id').val();
        $("#mostrar_asig1").val();
        $("#mostrar_asig2").val();
        $("#mostrar_asig3").val();  

        if (asig == '1') {
            $('#mostrar_asig1').show('slow');//para visualizar las asignaturas
            $('#mostrar_asig2').show('slow');//para visualizar las asignaturas
            $('#mostrar_asig3').show('slow');//para visualizar las asignaturas
            $.ajax({
                type:'GET',
                url:"{{ url('Inscripcion/contabilidad') }}",
                data: {
                    asignatura : asig
                },
                success:function(data){
                    $.each(data, function(index, value) {
                        $("#valor1").append('<tr>' +
                                    '<td>' + data[index].orden_impresion +'</td>' +
                                    '<td>' + data[index].codigo_asignatura + '</td>' +
                                    '<td>' + data[index].nombre_asignatura +'</td>' +
                                    '</tr>');
                    });
                    $("#nom_asig1").html('Contaduría General');
                    $("#gest1").html('Gestion ' + data[0].anio_vigente);

                }
            });

            $.ajax({
                type:'GET',
                url:"{{ url('Inscripcion/secretariado') }}",
                data: {
                    asignatura : asig
                },
                success:function(data){
                    $.each(data, function(index, value) {
                        $("#valor2").append('<tr>' +
                                    '<td>' + data[index].orden_impresion +'</td>' +
                                    '<td>' + data[index].codigo_asignatura + '</td>' +
                                    '<td>' + data[index].nombre_asignatura +'</td>' +
                                    '</tr>');
                    });
                    $("#nom_asig2").html('Secretariado Administrativo');
                    $("#gest2").html('Gestion ' + data[0].anio_vigente);
                }
            });

            $.ajax({
                type:'GET',
                url:"{{ url('Inscripcion/auxiliar') }}",
                data: {
                    asignatura : asig
                },
                success:function(data){
                   if(data == ''){
                    var mensaje = 'No tiene Asignaturas por Tomar'
                    $("#valor3").html(mensaje);
                   }else{
                    $.each(data, function(index, value) {
                        $("#valor3").append('<tr>' +
                                    '<td>' + data[index].orden_impresion +'</td>' +
                                    '<td>' + data[index].codigo_asignatura + '</td>' +
                                    '<td>' + data[index].nombre_asignatura +'</td>' +
                                    '</tr>');
                        });
                    $("#nom_asig3").html('Auxiliar Administrativo Financiero');
                    $("#gest3").html('Gestion ' + data[0].anio_vigente);
                    }
                   
                }
            });
        }else{
            $('#mostrar_asig1').show('slow');//para visualizar las asignaturas
            $('#mostrar_asig2').hide('slow');//para no mostrar las asignaturas
            $('#mostrar_asig3').hide('slow');//para no mostrar las asignaturas
            $.ajax({
                type:'GET',
                url:"{{ url('Inscripcion/busca_asignatura') }}",
                data: {
                    asignatura : asig
                },
                success:function(data){
                    $.ajax({
                            type:'GET',
                            url:"{{ url('Inscripcion/busca_carrera') }}",
                            data: {
                                id : asig
                            },
                            success:function(data){
                                    $("#nom_asig1").html(data[0].nombre);
                                    $("#gest1").html('Gestion ' + data[0].gestion);
                            }
                        });

                    $.each(data, function(index, value) {
                        $("#valor1").append('<tr>' +
                                    '<td>' + data[index].orden_impresion +'</td>' +
                                    '<td>' + data[index].codigo_asignatura + '</td>' +
                                    '<td>' + data[index].nombre_asignatura +'</td>' +
                                    '</tr>');
                    });
                }
            });
        }

        limpiarCampos1();
        // $('#valor1').replaceAll();//para limpiar los datos cada vez que se precione el boton asignaturas 
        // $('#valor2').replaceAll();//para limpiar los datos cada vez que se precione el boton asignaturas 
        // $('#valor3').replaceAll();//para limpiar los datos cada vez que se precione el boton asignaturas 

       
    } 
    
</script>
@endsection
