@if ($verificaRegistroCupon == 'Si')
    <h4 class="text-info text-center">YA FUE REGISTRADO</h4>
@else
    <h4 class="text-success text-center">REGISTRO EXITOSO</h4>
@endif
@php
    $utilidades = new App\librerias\Utilidades();
    $fechaRegistro = $utilidades->fechaHoraCastellano($datosCuponRegistrado->fecha_creacion);
@endphp
<h4>
    <span class="text-info">NOMBRE: </span> {{ $datosCuponRegistrado->cliente->name }}
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span class="text-info">CI: </span> {{ $datosCuponRegistrado->cliente->ci }}
</h4>
<h4><span class="text-info">REGISTRO: </span> {{ $fechaRegistro }}</h4>
<h2><span class="text-danger">CODIGO DEL CUPON: </span> <span class="font-weight-bold">{{ $datosCuponRegistrado->id }}</span></h2>
<hr>