<div class="table-responsive">
    <table class="table table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th scope="col">ESCALA</th>
                <th scope="col">PRECIO VENTA</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($precios as $p)
            <tr>
                <td scope="col">{{ $p->escala->nombre }}</td>
                <td scope="col">{{ $p->precio }}</td>
                <td scope="col">
                    <button class="btn btn-danger" onclick="elimina_precio_escala('{{ $p->id }}', '{{ $p->producto->id }}')" title="Elimina precio"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>