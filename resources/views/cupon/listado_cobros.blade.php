@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-info">
            <div class="card-header bg-info">
                <h4 class="mb-0 text-white">COBRO DE CUPONES</h4>
            </div>
            <form action="{{ url('Cupon/ajaxBuscaCupon') }}" method="POST" id="formularioCupon">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Codigo Cupon</label>
                                <input type="number" name="codigo" id="codigo" class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Carnet Cliente</label>
                                <input type="number" name="ci" id="ci" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <button type="button" onclick="buscar()" class="btn btn-block btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="listadoProductosAjax"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="mostrar" style="display:none;">
        aqui
    </div>
</div>

@stop
@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/utilidades.js') }}"></script>
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $(".select2").select2();
    });

    function buscar()
    {
        let codigo = document.getElementById('codigo');
        let carnet = document.getElementById('ci');
        let formulario = document.getElementById('formularioCupon');

        let data = new FormData(formulario);
        let serialized = serialize(data);
        console.log(serialized);

        if (codigo.value.length == 0 && carnet.value.length == 0) {

            Swal.fire(
                'Alerta!',
                'Debes llenar unos de los campos.',
                'warning'
            )
            
        }else{

            $.ajax({
                url: "{{ url('Cupon/ajaxBuscaCupon') }}",
                data: serialized,
                type: 'post',
                success: function(data) {
                    $("#mostrar").html(data);
                    $("#mostrar").show('slow');
                }
            });
        }
    }
</script>

@endsection