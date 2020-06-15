@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endsection

@section('content')

<!-- inicio modal content -->
<div id="modal_asigna" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">ASIGNACION DE MATERIA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5><b>Sigla: </b><span id="modal_sigla_materia"></span> &nbsp;&nbsp;&nbsp;&nbsp;<b>Nombre: </b><span id="modal_nombre_materia"></span></h5>
                            <h5><b>Carrera: </b><span id="modal_carrera_materia"></span></h5>
                        </div>
                    </div>
                    <form action="#" method="POST" id="formulario_modal_asignacion">
                        @csrf
                        <input type="hidden" name="asignatura_id" id="fm_asignatura_id" value="">
                        <input type="hidden" name="user_id" id="fm_user_id" value="{{ $datos_persona->id }}">
                        
                        <div class="row">

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label class="control-label">Turno</label>
                                    <select name="turno_id" id="turno_id" class="form-control custom-select" required>
                                        @foreach ($turnos as $t)
                                            <option value="{{ $t->id }}">{{ $t->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Paralelo</label>
                                    <select name="paralelo" id="paralelo" class="form-control custom-select" >
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                            
                                <div class="form-group">
                                    <label class="control-label">A&ntilde;o</label>
                                    <input type="number" name="anio_vigente" id="anio_vigente" class="form-control" value="{{ date('Y') }}">
                                </div>
                            </div>

                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button class="btn waves-effect waves-light btn-block btn-success" onclick="guarda_asignacion()">ASIGNAR</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- fin modal -->

<div class="card">
    <div class="card-body">

        <div class="row">
            <div class="col-md-6">
                <h3>
                    Nombre: 
                    <span class="text-info">
                        {{ $datos_persona->apellido_paterno }}
                        {{ $datos_persona->apellido_materno }}
                        {{ $datos_persona->nombres }}
                    </span>
                </h3>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-6">
                
                <div class="card card-outline-primary">                                
                    <div class="card-header">
                        <h4 class="mb-0 text-white">ASIGNATURAS</h4>
                    </div>

                    <div class="table-responsive m-t-40">
                        <table id="tabla-asignaturas" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sigla</th>
                                    <th>Carrera</th>
                                    <th>Nombre</th>
                                    <th>A&ntilde;o</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asignaturas as $a)
                                    <tr>
                                        <td>{{ $a->codigo_asignatura }}</td>
                                        <td>{{ $a->carrera->nombre }}</td>
                                        <td>{{ $a->nombre_asignatura }}</td>
                                        <td class="text-center">{{ $a->gestion }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="asigna_materia('{{ $a->id }}', '{{ $a->nombre_asignatura }}', '{{ $a->codigo_asignatura }}', '{{ $a->carrera->nombre }}')"><i
                                                    class="fas fa-arrow-right"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="mb-0 text-white">ASIGNATURAS DEL DOCENTE</h4>
                    </div>
                
                    <div class="table-responsive m-t-40">
                        <table id="tabla-asignaturas-docente" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sigla</th>
                                    <th>Carrera</th>
                                    <th>Nombre</th>
                                    <th>Curso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asignaturas_docente as $ad)
                                <tr>
                                    <td>{{ $ad->asignatura->codigo_asignatura }}</td>
                                    <td>{{ $ad->asignatura->carrera->nombre }}</td>
                                    <td>{{ $ad->asignatura->nombre_asignatura }}</td>
                                    <td>{{ $ad->gestion }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger"
                                            onclick="elimina_asignacion('{{ $ad->id }}', '{{ $ad->asignatura->nombre_asignatura }}')"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweet-alert.init.js') }}"></script>

<script>

    var tabla_asignaturas;

    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        tabla_asignaturas = $('#tabla-asignaturas').DataTable();
    });

    $(function () {
        $('#tabla-asignaturas-docente').DataTable();
    });

    function asigna_materia(asignatura_id, nombre_asignatura, codigo_asignatura, nombre_carrera)
    {
        $("#modal_sigla_materia").html(codigo_asignatura);
        $("#modal_nombre_materia").html(nombre_asignatura);
        $("#modal_carrera_materia").html(nombre_carrera);
        $("#fm_asignatura_id").val(asignatura_id);
        $("#modal_asigna").modal('show');
        // console.log(nombre_asignatura);
    }

    function guarda_asignacion() {
        formulario_asignacion = $("#formulario_modal_asignacion").serializeArray();
        $.ajax({
            url: "{{ url('User/guarda_asignacion') }}",
            method: "POST",
            data: formulario_asignacion,
            cache: false,
            success: function(data)
            {
                if (data.error_duplicado == 1) 
                {
                    Swal.fire(
                        'Alerta!',
                        'La materia ya esta asignada al docente',
                        'warning'
                    ).then(function() {
                        $("#modal_asigna").modal('hide');
                    });
                } else {
                    Swal.fire(
                        'Bien!',
                        'La materia esta asignada al docente',
                        'success'
                    );
                    window.location.href = "{{ url('User/asigna_materias') }}/" + {{ $datos_persona->id }};
                }
            }
        })
    }

    function elimina_asignacion(np_id, nombre)
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

                $.ajax({
                    url: "{{ url('User/eliminaAsignacion') }}/"+np_id,
                    method: "GET",
                    cache: false,
                    success: function (data) {

                        Swal.fire(
                            'Excelente!',
                            'La materia fue eliminada',
                            'success'
                        );
                        window.location.href = "{{ url('User/asigna_materias') }}/" + data.usuario;
                    }
                });

            }
        })

    }
</script>
@endsection