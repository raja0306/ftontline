@extends('layouts.master')
@section('title', 'Agent Performance')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Agent Performance Details</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Agent Reports > </li>
                <li class="text-muted">Agent Performance</li>
            </ol>
        </div>

    </div>
</div>
</div>

<div class="row">
<div class="card overflow-hidden">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-sm-12 pt-3">
                <div class="row">
                    <div class="col-lg-3">
                        <div>
                            <label>From Date</label>
                            <input class="form-control" type="date" min="2024-08-14" value="{{$fromdate}}" name="fromdate" id="fromdate">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>To Date</label>
                            <input class="form-control" type="date" min="2024-08-14" value="{{$todate}}" name="todate" id="todate">
                        </div>
                    </div>
                    <div class="col-lg-3 mt-3">
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>Agent Name</th>
                        <th>Calls</th>
                        <th>Inbounds</th>
                        <th>Outbounds</th>
                        <th>Total Login Time</th>
                        <th>Pause</th>
                        <th>Wait</th>
                        <th>Talk</th>
                        <th>Talk Avg</th>
                        <th>Dead</th>
                        <th>Appointment Count</th>
                        <th>Customer</th>
                        <th>Branch</th>
                        <th>Service Level</th>
                        <th>Occupancy Time</th>
                    </tr>
                </thead>           
                <tbody>
                        {!!$divhtml!!}
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div> 

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-bordered w-100">
                <thead>
                    <tr>   
                        <th>Agent Name</th>
                        <th>Total(Login Days)</th>
                        <th>Total(Hours)</th>
                        <th>Total Paused</th>
                        <th>Total Non Paused</th>
                          {!!$divhtml1head!!}
                    </tr>
                </thead>           
                <tbody>
                        {!!$divhtml1!!}
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div> 

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
$(document).ready(function(){
    $("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
    $("#datatable-buttons1").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons1_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")
});

$('#submitbtn').click(function() {
    var submiturl = "{{url('/agent/performance')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val();
    window.location.href = submiturl;
});
</script>
@stop