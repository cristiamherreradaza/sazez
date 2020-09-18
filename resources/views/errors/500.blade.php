{{-- @extends('errors::minimal') --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <style>
        #outer {
            width: 100%;
            text-align: center;
            padding-top: 50px;
        }
    
        #inner {
            display: inline-block;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18pt;
        }

    </style>
</head>
<body>
    <div id="outer">
        <div id="inner">
            <img src="{{ asset('assets/images/fantasma_error.jpg') }}" alt="Problema" class="rounded-circle" width="476" />
            <p>UPPS!!! paso algo raro.</p>
            <p><a href="{{ URL::to('/') }}">Volver al sistema</a></p>
            
        </div>
    </div>
</body>
</html>
{{-- @section('title', __('Server Error')) --}}
{{-- @section('code', '500') --}}
{{-- @section('message', __('Server Error')) --}}

