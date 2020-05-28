@extends('layouts.app')

@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('css')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}"> --}}
{{-- <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" /> --}}
{{-- <link href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" /> --}}

  <link rel='stylesheet' href='https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css'>
<link rel='stylesheet' href='https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css'>
<link rel='stylesheet' href='https://www.gyrocode.com/wp/wp-content/cache/minify/7db04.css'>
{{-- <link type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js" rel="stylesheet" /> --}}
@endsection

@section('content')


<div class="card card-outline-info">
    <form action="{{ url('Escala/guarda_multiple') }}" method="POST">
        @csrf

    <div class="row">
        <div class="col-md-12" id="listaProductosAjax">
            <div class="card card-outline-warning">
                <div class="card-header">
                    <h4 class="mb-0 text-white">PRODUCTOS PARA PEDIDO</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="example" class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Extn.</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>$320,800</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>63</td>
                                    <td>$170,750</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ashton Cox</td>
                                    <td>Junior Technical Author</td>
                                    <td>San Francisco</td>
                                    <td>66</td>
                                    <td>$86,000</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Donna Snider</td>
                                    <td>Customer Support</td>
                                    <td>New York</td>
                                    <td>27</td>
                                    <td>$112,000</td>
                                    <td>$320,800</td>
                                </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Extn.</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button type="submit" class="btn waves-effect waves-light btn-block btn-success">GUARDAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

@stop

@section('js')
{{-- <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script> --}}
<!-- Sweet-Alert  -->
{{-- <script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script> --}}

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script src='https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js'></script>
<script src='https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js'></script>
{{-- <script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script> --}}

<script>
$(document).ready(function (){
        var table = $('#example').DataTable({
            // 'ajax': 'https://gyrocode.github.io/files/jquery-datatables/arrays_id.json',
            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                },
              {
            "targets": -1,
            "data": null
        }
            ],
            'select': {
                'style': 'multi',
              selector: ':not(:last-child)'
            },
            'order': [[1, 'asc']]
        });
  
  $('#example tbody').on( 'click', 'button', function () {
        var data = table.row( $(this).parents('tr') ).data();
        alert( data[1] + ' ' + data[2] + ' ' + data[3] + ' ' + data[4] + ' ' + data[5] + ' ' + data[6]);
    } );


        // Handle form submission event
        $('#frm-example').on('submit', function(e){
            var form = this;

            var rows_selected = table.column(0).checkboxes.selected();

            // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, rowId){
                // Create a hidden element
                $(form).append(
                    $('<input>')
                        .attr('type', 'text')
                        .attr('name', 'id[]')
                        .val(rowId)
                );
            });
        });
    });
</script>



@endsection