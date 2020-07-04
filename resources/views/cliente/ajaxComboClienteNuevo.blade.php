<select name="cliente_id" id="cliente_id" class="select2 form-control custom-select" style="width: 100%; height:36px;"
    onchange="seleccionaCliente()">
    @foreach($clientes as $c)
        <option value="{{ $c->id }}" <?php echo ($c->id==$clienteSeleccionado)?'selected':'' ?>> {{ $c->name }} </option>
    @endforeach
</select>