@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endsection

@section('content')
<div class="card card-outline-info">
    <div class="card-header">
        <h4 class="mb-0 text-white">
            COMBO NUEVO
        </h4>        
    </div>
    <div class="card-body">
        <form action="{{ url('Combo/guarda') }}" method="POST">
            @csrf
            <div class="row">         
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input type="text" name="nombre_combo" id="nombre_combo" class="form-control">
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Fecha Final</label>
                        <input type="date" name="fecha_final" id="fecha_final" class="form-control">
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-success"  onclick="guardar_combo()">CREAR</button>
                    </div>                    
                </div>
            </div>
        </form>
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
    function guardar_combo()
    {
        var nombre_combo = $("#nombre_combo").val();
        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_final = $("#fecha_final").val();

        if(nombre_combo.length>0 && fecha_inicio.length>0 && fecha_final.length>0){
            Swal.fire(
                'Excelente!',
                'Generando lista de Productos.',
                'success'
            )
        }else{
            Swal.fire(
                'Oops...',
                'Es necesario llenar todos los campos.',
                'error'
            )
        }
    }
</script>
@endsection
