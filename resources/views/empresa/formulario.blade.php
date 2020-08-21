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

<form action="{{ url('Envio/guarda') }}" method="POST">
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
                                <label class="control-label">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">DIRECCION</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">ACTIVIDAD ECONOMICA</label>
                                <input type="text" name="actividad" id="actividad" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">LEYENDA DERECHO DEL CONSUMIDOR</label>
                                <input type="text" name="derechos" id="derechos" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">NIT</label>
                                <input type="text" name="nit" id="nit" class="form-control" required>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">TEFEFONO</label>
                                <input type="text" name="telefono" id="telefono" class="form-control" >
                            </div>
                        </div>
                    
                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">FAX</label>
                                <input type="text" name="fax" id="fax" class="form-control" >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">EMAIL</label>
                                <input type="text" name="email" id="email" class="form-control" >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label class="control-label">TELEFONO FIJO</label>
                                <input type="text" name="fax" id="fax" class="form-control" >
                            </div>
                        </div>
                        
                    </div>
                        <p>&nbsp;</p>
                        <div class="row">
                            <div class="col-md-12 text-center"><h2 class="text-info font-weight-bold">PARAMETROS DE CONTROL (FACTURACION)</h2></div>
                        </div>
                        <p>&nbsp;</p>

                    <div class="row">
                        <div class="col-md-2"></div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE AUTORIZACION</label>
                                <input type="text" name="numero" id="numero" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE AUTORIZACION <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-2"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">LLAVE DE DOSIFICACION</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">LLAVE DE DOSIFICACION <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-2"></div>
                    
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE FACTURA INICIAL</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">NUMERO DE FACTURA INICIAL <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-2"></div>
                    
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">FECHA LIMITE DE EMISION</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">FECHA LIMITE DE EMISION <span class="text-warning">(CONFIRMACION)</span></label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required>
                            </div>
                        </div>
                    
                        <div class="col-md-2"></div>
                    
                    </div>

                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="submit" class="btn waves-effect waves-light btn-block btn-success">ENVIAR</button>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="mb-0 text-white">PRODUCTOS PARA ENVIO</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="tablaPedido" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Tipo</th>
                                    <th>Modelo</th>
                                    <th>Colores</th>
                                    <th>Stock</th>
                                    <th style="width: 5%">Cantidad</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit"
                                class="btn waves-effect waves-light btn-block btn-success">ENVIAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header bg-primary" onclick="muestra_formulario_importacion()">
                <h4 class="mb-0 text-white">IMPORTAR EXCEL PRODUCTOS</h4>
            </div>
            <div class="card-body" id="bloque_formulario_importacion" style="display: none;">
                <form method="post" enctype="multipart/form-data" id="upload_form" class="upload_form">
                    @csrf
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ARCHIVO</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="select_file" id="select_file" class="custom-file-input"
                                            accept=".xlsx" required>
                                        <label class="custom-file-label" for="inputGroupFile01">Seleccione...</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <input type="submit" name="upload" id="upload"
                                class="btn btn-success waves-light btn-block float-lg-right"
                                value="Importar archivo excel">
                            {{--  <button type="submit" id="btnEnviaExcel" onclick="enviaExcel();"
                                        class="btn waves-effect waves-light btn-block btn-success">Importar archivo
                                        excel</button> --}}
                            <button class="btn btn-primary btn-block" type="button" id="btnTrabajandoExcel" disabled
                                style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                &nbsp;&nbsp;Estamos trabajando, ten pasciencia ;-)
                            </button>

                        </div>
                        <div class="col-md-3">
                            <a href="{{ asset('excels/prototipo_pedidos.xlsx') }}" rel="noopener noreferrer">
                                <button type="button"
                                    class="btn waves-effect waves-light btn-block btn-warning">Descargar formato
                                    excel</button>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <span style="background-color: #ea6274; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            Colocar NT en caso de no tener informacion
                        </div>
                        <div class="col-md-3">
                            <span style="background-color: #67ff67; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            Solo introducir numeros
                        </div>
                        <div class="col-md-3">
                            <span style="background-color: #8065a9; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            Colocar 0 si no tienen el dato
                        </div>
                        <div class="col-md-3">
                            <span style="background-color: #62adea; width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            No dejar celdas vacias ni cambiar el orden
                        </div>
                        <div class="col-md-12">
                            {{-- <img src="{{ asset('assets/images/muestra_excel_productos.png') }}"
                            class="img-thumbnail" alt=""> --}}
                            <span class='zoom' id='ex1'>
                                <img src='{{ asset('assets/images/muestra_excel_envios.png') }}' class="img-thumbnail"
                                    alt='Daisy on the Ohoopee' />
                            </span>
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
@endsection