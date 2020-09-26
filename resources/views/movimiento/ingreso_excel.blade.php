@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card border-info">                                
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">EXPORTACION DE EXCEL</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Sucursal</label>
                                <select name="almacen" id="almacen" class="form-control">
                                    @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}"> {{ $almacen->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label>
                                <div class="input-group mb-3">
                                    <button type="button" onclick="descargaFormato()" class="btn btn-success btn-block"><span><i class="fa fa-arrow-alt-circle-down"></i> DESCARGAR FORMATO </span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="mb-0 text-white">IMPORTACION DE EXCEL</h4>
                </div>
                <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form">
                            @csrf
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Incluir Central</label>
                                            <select name="incluye_almacen" id="incluye_almacen" class="form-control">
                                                <option value="No" selected> No </option>
                                                <option value="Si"> Si </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Proveedor</label>
                                            <select name="proveedor" id="proveedor" class="form-control">
                                                <option value=""> No </option>
                                                @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}"> {{ $proveedor->nombre }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="control-label">&nbsp;</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">ARCHIVO</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="select_file" id="select_file" class="custom-file-input"  accept=".xlsx" required>
                                                    <label class="custom-file-label" for="inputGroupFile01"></label>
                                                </div>
                                                <input type="submit" name="upload" id="upload" class="btn btn-success float-lg-right" value="Importar Excel">
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

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script src="{{ asset('js/NumeroALetras.js') }}"></script>
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Funcion para habilitar/deshabilitar el input de Incluir Almacen Central
    $( function() {
        $("#incluye_almacen").prop('disabled', true);
        $("#incluye_almacen").val("No");
        $("#almacen").change( function() {
            if($(this).val() != 1){
                $("#incluye_almacen").prop('disabled', false);
            }else{
                $("#incluye_almacen").prop('disabled', true);
                $("#incluye_almacen").val("No");
            }
        });
    });

    // Funcion para importar excel
    $(document).ready(function() {
        $('.upload_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ url('Movimiento/importar_formato_ingreso') }}",
                method: "POST",
                //data: new FormData(this), incluye_almacen, proveedor,
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data)
                {
                    if(data.sw == 1){
                        Swal.fire(
                        'Hecho',
                        data.message,
                        'success'
                        )
                        .then(function() {
                            window.location.href = "{{ url('Producto/ver_ingreso') }}/"+data.numero;
                        });
                    }else{
                        Swal.fire(
                        'Oops...',
                        data.message,
                        'error'
                        )
                    }
                }
            })
        });
    });

    function descargaFormato()
    {
        let almacen_id = $("#almacen").val();
        window.location.href = "{{ url('Movimiento/exportar_formato_ingreso') }}/"+almacen_id;
    }

</script>
@endsection