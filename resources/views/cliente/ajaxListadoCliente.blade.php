<table id="myTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>CI</th>
            <th>Correo Electronico</th>
            <th>Celular</th>
            <th>Razón Social</th>
            <th>Nit</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clientes as $key => $cliente)
            <tr>
                <td>{{ ($key+1) }}</td>
                <td>{{ $cliente->name }}</td>
                <td>{{ $cliente->ci }}</td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->celulares }}</td>
                <td>{{ $cliente->razon_social }}</td>
                <td>{{ $cliente->nit }}</td>
                <td>
                    <button type="button" class="btn btn-warning" title="Editar cliente"  onclick="editar('{{ $cliente->id }}', '{{ $cliente->name }}', '{{ $cliente->ci }}', '{{ $cliente->email }}', '{{ $cliente->celulares }}', '{{ $cliente->nit }}', '{{ $cliente->razon_social }}')"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-info" title="Cambiar contraseña"  onclick="contrasena({{ $cliente->id }})"><i class="fas fa-key"></i></button>
                    <button type="button" class="btn btn-danger" title="Eliminar cliente"  onclick="eliminar('{{ $cliente->id }}', '{{ $cliente->name }}')"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<script>
    $(function () {
        $('#myTable').DataTable({
            language: {
                url: '{{ asset('datatableEs.json') }}'
            },
            searching: false,
            lengthChange: false,
        });
    });
</script>
