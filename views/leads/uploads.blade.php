@extends('layouts.master')
@section('title', 'Uploads')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<style> .dataTables_info, .dataTables_paginate {display: block;} </style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Upload Logs</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Leads > </li>
                <li class="text-muted">Upload Logs</li>
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
                <form  action="{{url('/shipment/upload')}}" method="post">
                    {{csrf_field()}} 
                <div class="row">
                      
                    <div class="col-lg-2">
                        <div>
                            <label>From Date</label>
                            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>To Date</label>
                            <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div>
                            <label>List ID</label>
                            <select class="form-control" name="listid" id="listid">
                                {!!App\VicidialLists::Options($listid)!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>Search Type</label>
                            <select class="form-control" name="searchtype" id="searchtype">
                                <option value="">Select</option>
                                <option value="consignee_phone_upload">Mobile</option>
                                <option value="alternate_phone_upload">Alt Mobile</option>
                                <option value="barcode">Barcode</option>
                                <option value="customer_civil_Id">Customer Civil ID</option>
                                <option value="receiver_civil_id">Receiver Civil ID</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label>Search</label>
                        <input type="text" name="searchval" class="form-control">
                    </div>
                    <div class="col-lg-2 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Batch No</th>
                        <th>Upload ID</th>
                        <th>ListName</th>
                        <th>Upload Count</th>
                        <th>Uploaded Count</th>
                        <th>Already Count</th>
                        <th>Wrong Mobile No Count</th>
                        <th>Appointment Count</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Shipments</th>
                    </tr>
                </thead>           
                <tbody>
                    @php $x=1; @endphp
                    @foreach ($lists as $log)
                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$log->batchno}}</td>
                        <td>{{$log->id}}</td>
                        <td>{{App\VicidialLists::FindName($log->list_id)}}</td>
                        <td>{{$log->upload_count}}</td>
                        <td>{{$log->uploaded_count}}</td>
                        <td>{{$log->already_count}}</td>
                        <td>{{$log->mobile_count}}</td>
                        <td>{{$log->appointments->count()}}</td>
                        <td>{{$log->created_at}}</td>
                        <td>{{$log->user->name ?? ''}}</td>
                        <td><a href="{{url('/upload/shipment/view')}}/{{$log->id}}" class="btn btn-sm btn-outline-warning edit-list">View</a></td>
                    </tr>
                    @endforeach
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
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});
var extravals = '';
$('#resetbtn').click(function() {
    var confirm_result = confirm("Are you sure you want to Reset?");
    if (confirm_result == true) {
    var extravals = "&reset=True";
        submiturl(extravals);
    }
});
$('#clearbtn').click(function() {
    var confirm_result = confirm("Are you sure you want to Clear?");
    if (confirm_result == true) {
    var extravals = "&clear=True";
        submiturl(extravals);
    }
});
$('#submitbtn').click(function() {
    submiturl(extravals);
});
$('#exportbtn').click(function() {
    submiturl('export');
});
function submiturl(extra){
    var statuss = $("input[name='statuss']:checked").map(function() {
        return this.value;
    }).get().join(',');
    if(extra=='export'){
    var submiturl = "{{url('/listexport')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&datetype="+$("#datetype").val()+"&batchno="+$("#batchno").val()+"&listid="+$("#listid").val()+"&status="+statuss+"&phone="+$("#phone").val();
    }
    else{
    var submiturl = "{{url('/leads')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&datetype="+$("#datetype").val()+"&batchno="+$("#batchno").val()+"&listid="+$("#listid").val()+"&status="+statuss+"&phone="+$("#phone").val()+extra;
    }
    window.location.href = submiturl;
}
</script>
@stop



