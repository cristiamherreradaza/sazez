<h3>
    Codigo: <span class="text-primary">{{ $datosProducto->codigo }}</span>
    &nbsp;&nbsp;&nbsp;&nbsp;Nombre: <span class="text-info">{{ $datosProducto->nombre }}</span>
</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ALMACEN</th>
                <th scope="col">EXISTENCIAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cantidadTotal as $ct)
                <tr>
                    <td scope="col">{{ $ct->almacen }}</td>
                    <td scope="col">{{ $ct->total }}</td>
                </tr>    
            @endforeach
        </tbody>
    </table>
</div>