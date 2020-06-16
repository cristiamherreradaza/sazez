@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">
@endsection

@section('content')


<div class="card card-outline-info">
    <div class="card-header">
        <h4 class="mb-0 text-white">
            ENVIO DE PRODUCTOS
        </h4>
    </div>
    <div class="card-body">
        {{-- <form method="post" id="upload_form" enctype="multipart/form-data" class="upload_form float-left">
                @csrf
                <input type="file" name="select_file" id="select_file">
                <input type="submit" name="upload" id="upload" class="btn btn-rounded btn-success float-lg-right" value="Importar">
            </form> --}}
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form float-left">
                        <div class="card-body">
                            @csrf
                            <input type="submit" name="upload" id="upload" class="btn btn-rounded btn-success float-lg-right" value="Importar">
                            {{-- <button type="submit" class="btn btn-rounded btn-success float-lg-right">Importar</button> --}}
                            <h4 class="card-title">Importar Envio</h4>
                            <label for="input-file-disable-remove">Seleccione un archivo en formato EXCEL.</label>
                            <input type="file" name="select_file" id="select_file" class="dropify" data-show-remove="true" />
                        </div>
                    </form>
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
<script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script>
// Script de importacion de excel
$(document).ready(function() {
    $('.upload_form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ url('Entrega/ajax_importar') }}",
            method: "POST",
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
                    //.then(function() {
                    //     location.reload();
                    //     $('#select_file').val('');
                    // });
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
</script>
<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour reemplazar',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>
@endsection