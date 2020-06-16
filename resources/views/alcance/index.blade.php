@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/c3/c3.min.css') }}">
<link href="{{ asset('assets/libs/morris.js/morris.css') }}" rel="stylesheet">
@endsection

@section('content')
<!-- inicio modal nuevo almacen -->
<div id="nuevo_almacen" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">NUEVO ALCANCE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Vendedor</label>

                                    <select name="user_id" id="user_id" class="select2 form-control custom-select"
                                        style="width: 100%; height:36px;" required>
                                        @foreach($usuarios as $usu)
                                        <option value="{{ $usu->id }}"> {{ $usu->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Alcance Monetario(Bs.)</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="alcance_max" type="text" id="alcance_max" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <span class="text-danger">
                                        <i class="mr-2 mdi mdi-alert-circle"></i>
                                    </span>
                                    <input name="fecha" type="month" id="fecha" class="form-control" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="guardar()">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin modal nuevo almacen -->



    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-md-5 align-self-center">
                <h4 class="page-title">Alcance de los Vendedores</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        {{-- <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol> --}}
                    </nav>
                </div>
            </div>
            <div class="col-md-7 align-self-center d-none d-md-block">
                <button class="btn float-right btn-success" onclick="crear();"><i class="mdi mdi-plus-circle"></i> Crear</button>
                <div class="dropdown float-right mr-2 hidden-sm-down">
                    <input type="month" id="anio_mes" class="form-control" value="{{ date('Y-m') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12" id="grafico_alcance">
        {{-- <div class="card">
            <div class="card-body analytics-info">
                <h4 class="card-title">Ventas y Alcances</h4>
                <div id="bar-basic" style="height:400px;"></div>
            </div>
        </div> --}}
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-info">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">
                        VENDEDORES
                    </h4>
                </div>
                <div class="card-body" id="lista">
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $n = 1;
                                @endphp
                                @foreach($usuarios as $usu)
                                    <tr>
                                        <td>{{ $n++ }}</td>
                                        <td>{{ $usu->name }}</td>
                                        <td>
                                            <button onclick="ver_ventas('{{ $usu->id }}', '{{ $usu->name }}', '{{ date('Y-m') }}')" class="btn btn-info"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        
        <!-- Column -->
       <div class="col-lg-8" id="grafico_meses">

        </div>
    </div>
@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<!-- chartist chart -->
<script src="{{ asset('assets/libs/echarts/dist/echarts-en.min.js') }}"></script>
<script src="{{ asset('assets/libs/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets/libs/morris.js/morris.min.js') }}"></script>

<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        $('#myTable').DataTable({
            "paging":   true,
            "ordering": false,
            "info":     false,
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },

        });
    });

</script>
<script>
    function crear()
    {
        $(".select2").select2();
        $("#nuevo_almacen").modal('show');
    }
</script>
<script>
    function guardar()
    {
        var user_id = $("#user_id").val();
        var alcance_max = $("#alcance_max").val();
        var fecha = $("#fecha").val();
        $.ajax({
            type:'POST',
            url:"{{ url('Alcance/guarda') }}",
            data: {
                tipo_user : user_id, tipo_alcance : alcance_max, tipo_fecha : fecha
            },
            success:function(data){
                if (data.mensaje == 'si') {
                    Swal.fire(
                        'Excelente!',
                        'Se creo correctamente.',
                        'success'
                    )
                     Swal.fire(
                            'Excelente!',
                            'Se creo correctamente.',
                            'success'
                        ).then(function() {
                            $.ajax({
                                type:'GET',
                                url:"{{ url('Alcance/ajax_alcance') }}",
                                data: {
                                    tipo : fecha
                                },
                                success:function(data){
                                    $("#grafico_alcance").html(data);
                                    // $("#nuevo_almacen").show('slow');
                                }
                            });
                        });
                }
            }
        });
    }
</script>
<script>
    $(document).ready(function(){
        var fecha = $('#anio_mes').val();
        $.ajax({
            type:'GET',
            url:"{{ url('Alcance/ajax_alcance') }}",
            data: {
                tipo : fecha
            },
            success:function(data){
                // $("#grafico_alcance").show('slow');
                $("#grafico_alcance").html(data);
            }
        });
    });

    $('#anio_mes').on('change', function(e){
        var fecha = e.target.value;
        $.ajax({
            type:'GET',
            url:"{{ url('Alcance/ajax_alcance') }}",
            data: {
                tipo : fecha
            },
            success:function(data){
                // $("#grafico_alcance").show('slow');
                $("#grafico_alcance").html(data);
            }
        });

    });
</script>                   

<script>
    function ver_ventas(id, name, fecha)
    {
        $.ajax({
            type:'GET',
            url:"{{ url('Alcance/ajax_ventas_meses') }}",
            data: {
                tipo_id : id, tipo_name : name, tipo_fecha : fecha
            },
            success:function(data){
                // $("#grafico_alcance").show('slow');
                $("#grafico_meses").html(data);
            }
        });
       
    }
</script>
@endsection