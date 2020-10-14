@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')

<div id="loadingMessage">
    🎥 Acceso desahabilitado a la camara (por favor habilite su camara)
</div>

<canvas id="canvas" style="width: 100%;" hidden></canvas>
<div id="output" hidden>
    <div id="outputMessage">Qr no detectado.</div>
    <div hidden><span id="outputData"></span></div>
</div>

<div class="card card-body">
    <div class="invoice-123" id="printableArea">
        <div class="row">

            <div class="col-md-12">
                <div id="itemsAdicionados">


                </div>
                <div class="table-responsive m-t-40">
                    <table id="tablaPedido" class="tablesaw table-striped table-hover table-bordered table no-wrap">
                        <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>TIPO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@stop

@section('js')
<script src="{{ asset('assets/libs/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/datatable/custom-datatable.js') }}"></script>

<!--This page JavaScript -->
<script src="{{ asset('dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dist/js/pages/invoice/invoice.js') }}"></script>
<script src="{{ asset('dist/js/jsQR.js') }}"></script>
<script>
    var items = [];
    var indices = [];

    var t = $('#tablaPedido').DataTable({
        paging: false,
        searching: false,
        ordering:  false,
        info: false,
        language: {
            url: '{{ asset('datatableEs.json') }}'
        },
    });

    var video = document.createElement("video");
    var canvasElement = document.getElementById("canvas");
    var canvas = canvasElement.getContext("2d");
    var loadingMessage = document.getElementById("loadingMessage");
    var outputContainer = document.getElementById("output");
    var outputMessage = document.getElementById("outputMessage");
    var outputData = document.getElementById("outputData");

    function drawLine(begin, end, color) {
        canvas.beginPath();
        canvas.moveTo(begin.x, begin.y);
        canvas.lineTo(end.x, end.y);
        canvas.lineWidth = 4;
        canvas.strokeStyle = color;
        canvas.stroke();
    }

    // Use facingMode: environment to attemt to get the front camera on phones
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
        video.srcObject = stream;
        video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
        video.play();
        requestAnimationFrame(tick);
    });

    function tick() {
        loadingMessage.innerText = "⌛ Loading video..."
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            loadingMessage.hidden = true;
            canvasElement.hidden = false;
            outputContainer.hidden = false;

            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });
            if (code) {
                drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
                drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                outputMessage.hidden = true;
                outputData.parentElement.hidden = false;
                outputData.innerText = code.data;

                let indiceArray = code.data.split(/\r?\n/);
                let buscaIndice = indices.lastIndexOf(indiceArray[0]);

                if (buscaIndice < 0) { 
                    let cantidad = Math.floor(Math.random() * 100);

                    indices.push(indiceArray[0]);
                    t.row.add([
                        indiceArray[1],
                        indiceArray[2],
                        cantidad
                    ]).draw(false); 
                }

/*                for (i = 0; i < items.length; i++) {
                    let dato = items[i];
                    let datoArray = dato.split(/\r?\n/);
                    // document.writeln(datoArray[0]);
                    let buscaItem = items.lastIndexOf(datoArray[0]);
                    if (buscaItem < 0) {
                        items.push(code.data);
                    }
                }*/
                // $("#itemsAdicionados").html(indices);

            } else {
                outputMessage.hidden = false;
                outputData.parentElement.hidden = true;
            }
        }
        requestAnimationFrame(tick);
    }
</script>
@endsection