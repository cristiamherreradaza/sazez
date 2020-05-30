@extends('layouts.index')

@section('content')

<div class="card-body">
    <form class="form-horizontal form-material" method="POST" action="{{ route('login') }}">
        @csrf
        <h3 class="box-title mb-3 text-center">Inicio de Sesión</h3>
        <br>

        <div class="form-group ">
            <div class="col-xs-12">
                <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Correo Electrónico"> 
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
                    {{ __('Ingresar') }}
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 text-center">
                <div class="social">
                    <a href="{{ url('login/github') }}" class="btn btn-dark" data-toggle="tooltip" title="Acceder con Github"> <i aria-hidden="true" class="mdi mdi-github-circle"></i> </a>
                    <a href="{{ url('login/facebook') }}" class="btn btn-facebook" data-toggle="tooltip" title="Acceder con Facebook"> <i aria-hidden="true" class="fab fa-facebook"></i> </a>
                    <!-- <a href="javascript:void(0)" class="btn btn-twitter" data-toggle="tooltip" title="Acceder con Twitter"> <i aria-hidden="true" class="fab fa-twitter"></i> </a> -->
                    <a href="{{ url('login/google') }}" class="btn btn-googleplus" data-toggle="tooltip" title="Acceder con Google"> <i aria-hidden="true" class="fab fa-google-plus"></i> </a>
                </div>
            </div>
        </div>

        {{-- <div class="form-group mb-0">
            <div class="col-sm-12 text-center">
                <p>No tienes una cuenta? <a href="register.html" class="text-info ml-1"><b>Registrate</b></a></p>
            </div>
        </div> --}}
    </form>
</div>
@endsection
