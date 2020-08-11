<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">EDITAR PERFIL</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <form action="{{ url('Perfil/actualizar') }}"  method="POST" >
        @csrf
        <div class="modal-body">        
            <input type="hidden" name="id" id="id" value="{{ $perfil->id }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <span class="text-danger">
                            <i class="mr-2 mdi mdi-alert-circle"></i>
                        </span>
                        <input name="nombre" type="text" id="nombre" class="form-control" value="{{ $perfil->nombre }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Descripcion</label>
                        <input name="descripcion" type="text" id="descripcion" class="form-control" value="{{ $perfil->descripcion }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Menus</label>
                        @foreach($menugeneral as $key => $menu)
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input cajas" value="{{ $menu->id }}" id="customCheck{{$key}}" name="menus[]">
                                    <label for="customCheck{{$key}}" class="custom-control-label">{{ $menu->nombre }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn waves-effect waves-light btn-block btn-success" onclick="actualizar_perfil()">ACTUALIZAR PERFIL</button>
        </div>
    </form>
</div>