<div class="col">
	<div class="form-group">
		<label>Usuarios</label>
		<select name="usuario_id" id="usuario_id" class="form-control">
			<option value="todos">Todos</option>
			@foreach($vendedores as $v)
			<option value="{{ $v->id }}">{{ $v->name }}</option>
			@endforeach
		</select>
	</div>
</div>
