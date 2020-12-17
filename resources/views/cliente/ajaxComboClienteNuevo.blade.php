<select name="cliente_id" id="cliente_id" class="select2 form-control custom-select" style="width: 100%; height:36px;"
    onchange="seleccionaCliente()">
    @foreach($clientes as $c)
        <option value="{{ $c->id }}" <?php echo ($c->id==$clienteSeleccionado)?'selected':'' ?>> {{ $c->nit }} - {{ $c->razon_social }} </option>
    @endforeach
</select>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript">
	$(".select2").select2();
</script>