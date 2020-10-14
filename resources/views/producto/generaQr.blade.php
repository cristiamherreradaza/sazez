@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="card card-body">
    <div class="invoice-123" id="printableArea">
        <div class="row">
          @foreach ($qrsGenerados as $qr)
            <div class="col-md-2">
              <canvas id="qr-code_{{ $qr->id }}"></canvas><br />
              <div style="font-size: 10pt;">
                {{ $qr->producto->nombre }}-{{ $qr->id }}<br />
                {{ $qr->producto->tipo->nombre }}
              </div>
            </div>
          @endforeach
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
<script src="{{ asset('dist/js/qrious.min.js') }}"></script>
<script>
  @foreach ($qrsGenerados as $qr)
  var qr_{{ $qr->id }};
  (function () {
      qr_{{ $qr->id }} = new QRious({
          element: document.getElementById('qr-code_{{ $qr->id }}'),
          size: 120,
          value: "{{ $qr->numero }}\n{{ $qr->producto->nombre }}\n{{ $qr->producto->tipo->nombre }}"
      });
  })();
  @endforeach
</script>
@endsection