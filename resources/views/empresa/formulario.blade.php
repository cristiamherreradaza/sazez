@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')
<link href="{{ asset('assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}"
    rel="stylesheet" />

<form action="{{ url('Empresa/guarda') }}" method="POST" id="formularioEmpresa">
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
                                <input type="hidden" name="id" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->id }}">
                                <label class="control-label">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->nombre }}" required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">DIRECCION</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->direccion }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">ACTIVIDAD ECONOMICA</label>
                                <input type="text" name="actividad" id="actividad" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->actividad }}" required>
                            </div>
                        </div>
                    
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">LEYENDA DERECHO DEL CONSUMIDOR</label>
                                <input type="text" name="derechos" id="derechos" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->leyenda_consumidor }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">NIT</label>
                                <input type="text" name="nit" id="nit" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->nit }}" required>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">TELEFONO</label>
                                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->telefono }}" >
                            </div>
                        </div>
                    
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">FAX</label>
                                <input type="text" name="fax" id="fax" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->fax }}" >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">EMAIL</label>
                                <input type="text" name="email" id="email" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->email }}" >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">TELEFONO FIJO</label>
                                <input type="text" name="fax" id="telefono_fijo" class="form-control" value="{{ ($datosEmpresa==null)?"":$datosEmpresa->telefono_fijo }}" >
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="btn waves-effect waves-light btn-block btn-primary" onclick="muestraFormularioParametros();">NUEVOS PARAMATROS PARA FACTURA</button>
                    </div>

                    <div id="bloqueParametros" style="display: none;">

                    <div class="row">
                        <div class="col-md-2"></div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE AUTORIZACION</label>
                                <input type="text" name="numero_autorizacion" id="numero_autorizacion" class="form-control">
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE AUTORIZACION <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="numero_autorizacion_verificacion" id="numero_autorizacion_verificacion" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">LLAVE DE DOSIFICACION</label>
                                <input type="text" name="llave_dosificacion" id="llave_dosificacion" class="form-control">
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">LLAVE DE DOSIFICACION <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="llave_dosificacion_verificacion" id="llave_dosificacion_verificacion" class="form-control">
                            </div>
                        </div>
                    
                        <div class="col-md-2"></div>
                    
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE FACTURA INICIAL</label>
                                <input type="text" name="numero_factura" id="numero_factura" class="form-control">
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE FACTURA INICIAL <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="numero_factura_verificacion" id="numero_factura_verificacion" class="form-control">
                            </div>
                        </div>
                    
                        <div class="col-md-2"></div>
                    
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">FECHA LIMITE DE EMISION</label>
                                <input type="text" name="fecha_limite" id="fecha_limite" class="form-control">
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">FECHA LIMITE DE EMISION <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="fecha_limite_verficacion" id="fecha_limite_verficacion" class="form-control">
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
                                @forelse ($datosParametros as $lp)
                                    <tr>
                                        <td>{{ $lp->id }}</td>
                                        <td>{{ $lp->numero_autorizacion }}</td>
                                        <td>{{ $lp->llave_dosificacion }}</td>
                                        <td>{{ $lp->numero_factura }}</td>
                                        <td>{{ $lp->fecha_limite }}</td>
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

    function muestraFormularioParametros()
    {
        $("#bloqueParametros").toggle('slow');
    }

    function guardaDatos()
    {
        if ($("#formularioEmpresa")[0].checkValidity()) {
            let datosFormularioEmpresa = $("#formularioEmpresa").serializeArray();

            $.ajax({
                url: "{{ url('Empresa/guarda') }}",
                data: datosFormularioEmpresa,
                type: 'POST',
                success: function(data) {

                    Swal.fire({
                        type: 'success',
                        title: 'Excelente',
                        text: 'Se realizo la venta'
                    });

                    window.location.href = "{{ url('Empresa/formulario') }}";

                }
            });

        }else{
            $("#formularioEmpresa")[0].reportValidity();
        }
    }

</script>
@endsection