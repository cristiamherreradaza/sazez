<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codigos Qr</title>
    
</head>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    .codigos{
        border-style: dotted;
        border-color: #e0e0e0;
        width: 90px;
        float: left;
        padding: 15px;
        display: block;
    }

    .qrs{

        margin-left: 5px;
    }

    .texto{
        text-align: center;
        font-size: 6pt;
    }

    .button {
        background-color: #008CBA;
        /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }

    .buttonImpresion {
        background-color: #529e3a;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }

    .pagina{
        height: 612px;
        width: 791px;
        display: block;
    }

    .btnImpresion{
        display: block;
    }

    @media print {
        #btnImpresion {
            display: none;
        }
    }
</style>
<body>
<div class="pagina">
@foreach ($qrsGenerados as $qr)
<div class="codigos">
    <canvas id="qr-code_{{ $qr->id }}" class="qrs"></canvas><br />
    <div class="texto">
        {{ $qr->producto->nombre }}-{{ $qr->id }}<br />
        {{ $qr->producto->tipo->nombre }}
    </div>
</div>
@endforeach
</div>
<p></p>
<div id="btnImpresion">
    <a href="#" class="buttonImpresion" onclick="imprime()">IMPRIMIR</a>
    <a href="{{ url()->previous() }}" class="button">REGRESAR</a>
</div>

</body>
<!--This page JavaScript -->
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script src="{{ asset('dist/js/qrious.min.js') }}"></script>
<script>
@foreach ($qrsGenerados as $qr)
    var qr_{{ $qr->id }};
    (function () {
        qr_{{ $qr->id }} = new QRious({
            element: document.getElementById('qr-code_{{ $qr->id }}'),
            size: 80,
            value: "{{ $qr->producto->codigo }}"
        });
    })();
@endforeach
// function para imprimir la pagina
function imprime()
{
    window.print();
}
</script>