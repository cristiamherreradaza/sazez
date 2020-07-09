<form action="#" method="POST" id="formularioAjaxEditaCliente">
    @csrf
    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <span class="text-danger">
                        <i class="mr-2 mdi mdi-alert-circle"></i>
                    </span>
                    <input type="hidden" name="clienteId" value="{{ $datosCliente->id }}">
                    <input name="nombre_usuario" type="text" id="nombre_usuario" class="form-control" value="{{ $datosCliente->name }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Correo Electrónico</label>
                    <span class="text-danger">
                        <i class="mr-2 mdi mdi-alert-circle"></i>
                    </span>
                    <input name="email_usuario" type="email" id="email_usuario" onchange="validaEmail()" class="form-control" value="{{ $datosCliente->email }}" readonly>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Celular(es)</label>
                    <input name="celular_usuario" type="text" id="celular_usuario" class="form-control" value="{{ $datosCliente->celulares }}">
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Razón Social</label>
                    <input name="razon_social_usuario" type="text" id="razon_social_usuario" class="form-control" value="{{ $datosCliente->razon_social }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Nit</label>
                    <input name="nit_usuario" type="text" id="nit_usuario" class="form-control" value="{{ $datosCliente->nit }}">
                </div>
            </div>
        </div>

    <div class="modal-footer">
        <a class="btn waves-effect waves-light text-white btn-block btn-success" onclick="guardaAjaxCLienteEdicion()" id="btnGuardaCliente">EDITAR CLIENTE</a>
    </div>
</form>