@if ($verificaRegistroCupon == 'Si')
    <h4 class="text-warning text-center">YA FUE REGISTRADO</h4>
@else
    <h4 class="text-success text-center">REGISTRO EXITOSO</h4>
@endif

<h3><span class="text-info">NOMBRE: </span> {{ $datosCuponRegistrado->cliente->name }}</h3>
<h3><span class="text-info">FECHA REGISTRO: </span> {{ $datosCuponRegistrado->fecha_creacion }}</h3>
<hr>