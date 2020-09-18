<h3>
    Nombre: <span class="text-info">{{ $datosProducto->nombre }}</span>
</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="bg-info text-white">
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

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col">ESCALA</th>
                <th scope="col">PRECIO VENTA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($precios as $p)
            <tr>
                <td scope="col">{{ $p->escala->nombre }}</td>
                <td scope="col">{{ $p->precio }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>