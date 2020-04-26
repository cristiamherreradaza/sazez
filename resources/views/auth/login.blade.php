@extends('layouts.index')

@section('content')

<div class="card-body">
    <form class="form-horizontal form-material" method="POST" action="{{ route('login') }}">
        @csrf
        <h3 class="box-title mb-3 text-center">Inicio de Sesión</h3>
        <br>

        <div class="form-group ">
            <div class="col-xs-12">
                <input id="name" type="name" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nombre de Usuario"> 
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
            </div>
        </div>
        
        <div class="form-group text-center mt-3">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">
                    {{ __('Confirmar') }}
                </button>
            </div>
        </div>

        <div class="form-group mb-0">
            <div class="col-sm-12 text-center">
                <p>No tienes una cuenta? <a href="register.html" class="text-info ml-1"><b>Registrate</b></a></p>
            </div>
        </div>
    </form>
</div>
@endsection
