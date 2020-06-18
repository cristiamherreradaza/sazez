@foreach ($imagenes_producto as $ip)

<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Imagen</h4>
            <img src="{{ asset('imagenesProductos')."/".$ip->imagen }}" alt="" height="200">
            <br />
            <button type="button" class="btn waves-effect waves-light btn-danger"
                onclick="elimina_imagen({{ $ip->id }}, {{ $ip->producto_id }})"> <i class="fas fa-trash-alt"></i>
                Eliminar</button>
        </div>
    </div>
</div>

@endforeach