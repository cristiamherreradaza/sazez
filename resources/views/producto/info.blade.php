@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')

@endsection

@section('content')

<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <img src="{{ asset('assets/images/info.jpg') }}" class="img-fluid rounded img-thumbnail" alt="informacion" />
    </div>
</div>
<!-- End Row -->
@stop

@section('js')

@endsection