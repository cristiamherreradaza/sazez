@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <!-- <p class="text-right">{{ auth()->user()->name }}</p> -->
                <!-- @if(auth()->user()->image)
                    <img src="{{ auth()->user()->image }}" alt="">
                @else
                    <p class="text-right">No tiene imagen</p>
                @endif -->
                
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Bienvenido!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
