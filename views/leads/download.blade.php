@extends('layouts.master')
@section('title', 'Upload File Format')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Upload File Format</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Leads > </li>
                <li class="text-muted">Upload > </li>
                <li class="text-muted">Upload File Format</li>
            </ol>
        </div>

    </div>
</div>
</div>


<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @php $x=1; @endphp
                    <tr>
                        <td>{{$x++}}</td>
                        <td>Shipment</td>
                        <td><a href="{{asset('/assets')}}/upload/shipments.csv" class="mb-2 btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-download"></i></a></td>
                    </tr>
                    <tr>
                        <td>{{$x++}}</td>
                        <td>Bulk Shipments</td>
                        <td><a href="{{asset('/assets')}}/upload/bulkshipments.csv" class="mb-2 btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-download"></i></a></td>
                    </tr>

                    <tr>
                        <td>{{$x++}}</td>
                        <td>Reschedule</td>
                        <td><a href="{{asset('/assets')}}/upload/reschedules.csv" class="mb-2 btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-download"></i></a></td>
                    </tr>
                    <tr>
                        <td>{{$x++}}</td>
                        <td>Others</td>
                        <td><a href="{{asset('/assets')}}/upload/others.csv" class="mb-2 btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-download"></i></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end col -->
@stop
@section('ScriptPage')
<script src="{{asset('/assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/jszip/jszip.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('#submitbtn').click(function() {
    var submiturl = "{{url('/enquiries')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&category="+$("#category").val()+"&listid="+$("#listid").val()+"&phone="+$("#phone").val();
    window.location.href = submiturl;
});
</script>
@stop