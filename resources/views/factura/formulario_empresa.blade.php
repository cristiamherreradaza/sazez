@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<form action="" method="POST" id="formularioEmpresa">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card border-info">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">DATOS DE LA EMPRESA</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="empresa_id" id="empresa_id" value="{{ $empresa->id }}">
                                <input type="hidden" name="almacene_id" id="almacene_id" value="{{ $empresa->almacene_id }}">
                                <input type="hidden" name="validador_autorizacion" id="validador_autorizacion" value="0">
                                <input type="hidden" name="validador_dosificacion" id="validador_dosificacion" value="0">
                                <input type="hidden" name="validador_inicial" id="validador_inicial" value="0">
                                <input type="hidden" name="validador_emision" id="validador_emision" value="0">
                                <label class="control-label">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $empresa->nombre }}" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">DIRECCION</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" value="{{ $empresa->direccion }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">ACTIVIDAD ECONOMICA</label>
                                <input type="text" name="actividad" id="actividad" class="form-control" value="{{ $empresa->actividad }}" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">LEYENDA DERECHO DEL CONSUMIDOR</label>
                                <input type="text" name="leyenda_consumidor" id="leyenda_consumidor" class="form-control" value="{{ $empresa->leyenda_consumidor }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">NIT</label>
                                <input type="text" name="nit" id="nit" class="form-control" value="{{ $empresa->nit }}" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">TELEFONO</label>
                                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $empresa->telefono }}" >
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">FAX</label>
                                <input type="text" name="fax" id="fax" class="form-control" value="{{ $empresa->fax }}" >
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">EMAIL</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $empresa->email }}" >
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">TELEFONO FIJO</label>
                                <input type="text" name="telefono_fijo" id="telefono_fijo" class="form-control" value="{{ $empresa->telefono_fijo }}" >
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">CIUDAD</label>
                                <select name="ciudad" id="ciudad" class="form-control">
                                    <option value="La Paz" {{ ($empresa->ciudad=="La Paz")?'selected':'' }}>La Paz</option>
                                    <option value="Cochabamba" {{ ($empresa->ciudad=="Cochabamba")?'selected':'' }}>Cochabamba</option>
                                    <option value="Santa Cruz" {{ ($empresa->ciudad=="Santa Cruz")?'selected':'' }}>Santa Cruz</option>
                                    <option value="Oruro" {{ ($empresa->ciudad=="Oruro")?'selected':'' }}>Oruro</option>
                                    <option value="Tarija" {{ ($empresa->ciudad=="Tarija")?'selected':'' }}>Tarija</option>
                                    <option value="Potosi" {{ ($empresa->ciudad=="Potosi")?'selected':'' }}>Potosi</option>
                                    <option value="Sucre" {{ ($empresa->ciudad=="Sucre")?'selected':'' }}>Sucre</option>
                                    <option value="Beni" {{ ($empresa->ciudad=="Beni")?'selected':'' }}>Beni</option>
                                    <option value="Pando" {{ ($empresa->ciudad=="Pando")?'selected':'' }}>Pando</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn waves-effect waves-light btn-block btn-primary" onclick="muestraFormularioParametros();">NUEVOS PARAMETROS PARA FACTURA</button>
                    </div>

                    <div id="bloqueParametros" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label class="control-label text-primary ">
                                    Para el registro de los parametros, todas las casillas deben ser llenadas y coincidir respectivamente.
                                </label>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">NUMERO DE AUTORIZACION</label>
                                    <input type="text" name="numero_autorizacion" id="numero_autorizacion" onchange="validaAutorizacion()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">NUMERO DE AUTORIZACION <span class="text-warning">(CONFIRMACION)</span></label>
                                    <input type="text" name="numero_autorizacion_verificacion" id="numero_autorizacion_verificacion" onchange="validaAutorizacion()" class="form-control">
                                    <div class="invalid-feedback" id="v_autorizacion" style="display: none;">Los valores de numero de autorizacion y su confirmacion no coinciden</div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">LLAVE DE DOSIFICACION</label>
                                    <input type="text" name="llave_dosificacion" id="llave_dosificacion" onchange="validaDosificacion()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">LLAVE DE DOSIFICACION <span class="text-warning">(CONFIRMACION)</span></label>
                                    <input type="text" name="llave_dosificacion_verificacion" id="llave_dosificacion_verificacion" onchange="validaDosificacion()" class="form-control">
                                    <div class="invalid-feedback" id="v_dosificacion" style="display: none;">Los valores de llave de dosificacion y su confirmacion no coinciden</div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">NUMERO DE FACTURA INICIAL</label>
                                    <input type="text" name="numero_factura" id="numero_factura" onchange="validaInicial()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">NUMERO DE FACTURA INICIAL <span class="text-warning">(CONFIRMACION)</span></label>
                                    <input type="text" name="numero_factura_verificacion" id="numero_factura_verificacion" onchange="validaInicial()" class="form-control">
                                    <div class="invalid-feedback" id="v_inicial" style="display: none;">Los valores de numero de factura inicial y su confirmacion no coinciden</div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">FECHA LIMITE DE EMISION</label>
                                    <input type="date" name="fecha_limite" id="fecha_limite" onchange="validaEmision()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">FECHA LIMITE DE EMISION <span class="text-warning">(CONFIRMACION)</span></label>
                                    <input type="date" name="fecha_limite_verificacion" id="fecha_limite_verificacion" onchange="validaEmision()" class="form-control">
                                    <div class="invalid-feedback" id="v_emision" style="display: none;">Los valores de fecha limite de emision y su confirmacion no coinciden</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn waves-effect waves-light btn-block btn-success" onclick="guardaDatos()">GUARDAR DATOS EMPRESA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="mb-0 text-white">LISTADO PARAMETROS FACTURA</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="tablaPedido" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th>N. AUTORIZACION</th>
                                    <th>LLAVE DOSIFICACION</th>
                                    <th>NUMERO FACTURA</th>
                                    <th>FECHA LIMITE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parametros as $parametro)
                                    <tr>
                                        <td>{{ $parametro->id }}</td>
                                        <td>{{ $parametro->numero_autorizacion }}</td>
                                        <td>{{ $parametro->llave_dosificacion }}</td>
                                        <td>{{ $parametro->numero_factura }}</td>
                                        <td>{{ $parametro->fecha_limite }}</td>
                                        <td></td>
                                    </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>
<script>
    $.ajaxSetup({
        // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Funcion para seteo de atributos al iniciar la pagina
    // $( function() {
    //     $("#cliente").prop("disabled", true);
    //     $("#email").prop("disabled", true);
    //     $("#tipo_envio").val("");

    //     $("#tipo_envio").change( function() {
    //         if ($(this).val() == "1") {
    //             $("#cliente").prop("disabled", false);
    //             $("#email").prop("disabled", true);
    //         }
    //         if ($(this).val() == "2") {
    //             $("#cliente").prop("disabled", true);
    //             $("#email").prop("disabled", false);
    //         }
    //     });
    // });

    function muestraFormularioParametros()
    {
        $("#bloqueParametros").toggle('slow');
    }

    function guardaDatos()
    {
        let almacen_id = $("#almacene_id").val();
        if ($("#formularioEmpresa")[0].checkValidity()) {
            let datosFormularioEmpresa = $("#formularioEmpresa").serializeArray();
            $.ajax({
                url: "{{ url('Factura/guarda_formulario') }}",
                data: datosFormularioEmpresa,
                type: 'POST',
                success: function(data) {
                    Swal.fire({
                        type: 'success',
                        title: 'Excelente',
                        text: 'Se guardaron los datos de la Empresa.'
                    });
                    window.location.href = "{{ url('Factura/formulario_empresa') }}/"+almacen_id;
                }
            });

        }else{
            $("#formularioEmpresa")[0].reportValidity();
        }
    }

    function validaAutorizacion()
    {
        let numero_autorizacion = $("#numero_autorizacion").val();
        let numero_autorizacion_verificacion = $("#numero_autorizacion_verificacion").val();
        if(numero_autorizacion == numero_autorizacion_verificacion){
            // Muestra barrita verde
            $("#numero_autorizacion_verificacion").removeClass("is-invalid");
            $("#numero_autorizacion_verificacion").addClass("is-valid");
            // Oculta si estuviera desplegado la barra de texto
            $("#v_autorizacion").hide();
            // Habilita el id validador para el envio al controlador
            $("#validador_autorizacion").val('1');
        }else{
            // Muestra barrita roja
            $("#numero_autorizacion_verificacion").removeClass("is-valid");
            $("#numero_autorizacion_verificacion").addClass("is-invalid");
            // Muestra que texto que no son iguales
            $("#v_autorizacion").show();
            // Coloca el id validador en 0
            $("#validador_autorizacion").val('0');
        }
    }

    function validaDosificacion()
    {
        let llave_dosificacion = $("#llave_dosificacion").val();
        let llave_dosificacion_verificacion = $("#llave_dosificacion_verificacion").val();
        if(llave_dosificacion == llave_dosificacion_verificacion){
            // Muestra barrita verde
            $("#llave_dosificacion_verificacion").removeClass("is-invalid");
            $("#llave_dosificacion_verificacion").addClass("is-valid");
            // Oculta si estuviera desplegado la barra de texto
            $("#v_dosificacion").hide();
            // Habilita el id validador para el envio al controlador
            $("#validador_dosificacion").val('1');
        }else{
            // Muestra barrita roja
            $("#llave_dosificacion_verificacion").removeClass("is-valid");
            $("#llave_dosificacion_verificacion").addClass("is-invalid");
            // Muestra que texto que no son iguales
            $("#v_dosificacion").show();
            // Coloca el id validador en 0
            $("#validador_dosificacion").val('0');
        }
    }

    function validaInicial()
    {
        let numero_factura = $("#numero_factura").val();
        let numero_factura_verificacion = $("#numero_factura_verificacion").val();
        if(numero_factura == numero_factura_verificacion){
            // Muestra barrita verde
            $("#numero_factura_verificacion").removeClass("is-invalid");
            $("#numero_factura_verificacion").addClass("is-valid");
            // Oculta si estuviera desplegado la barra de texto
            $("#v_inicial").hide();
            // Habilita el id validador para el envio al controlador
            $("#validador_inicial").val('1');
        }else{
            // Muestra barrita roja
            $("#numero_factura_verificacion").removeClass("is-valid");
            $("#numero_factura_verificacion").addClass("is-invalid");
            // Muestra que texto que no son iguales
            $("#v_inicial").show();
            // Coloca el id validador en 0
            $("#validador_inicial").val('0');
        }
    }

    function validaEmision()
    {
        let fecha_limite = $("#fecha_limite").val();
        let fecha_limite_verificacion = $("#fecha_limite_verificacion").val();
        if(fecha_limite == fecha_limite_verificacion){
            // Muestra barrita verde
            $("#fecha_limite_verificacion").removeClass("is-invalid");
            $("#fecha_limite_verificacion").addClass("is-valid");
            // Oculta si estuviera desplegado la barra de texto
            $("#v_emision").hide();
            // Habilita el id validador para el envio al controlador
            $("#validador_emision").val('1');
        }else{
            // Muestra barrita roja
            $("#fecha_limite_verificacion").removeClass("is-valid");
            $("#fecha_limite_verificacion").addClass("is-invalid");
            // Muestra que texto que no son iguales
            $("#v_emision").show();
            // Coloca el id validador en 0
            $("#validador_emision").val('0');
        }
    }

</script>
@endsection