@extends('layouts.master')
@section('title', 'Bulk Upload Shipments')
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
        <h4 class="mb-sm-0 font-size-18">Bulk Upload Shipments</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Bulk Upload Shipments</li>
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
@if(session('alert'))
            <div class="alert alert-success mt-3">{!! session('alert') !!}.</div>
        @endif
<form  action="{{url('/shipment/bulkupload')}}" method="post">
    {{csrf_field()}} 
<div class="row">
      
    <div class="col-lg-3">
        <div>
            <label>From Date</label>
            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
        </div>
    </div>
    <div class="col-lg-3">
        <div>
            <label>To Date</label>
            <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
        </div>
    </div>

    <div class="col-lg-3">
        <div>
            <label>Commodity</label>
            <select class="form-control" name="commodity_id" id="commodity_id">
                <option value="">Select</option>
                @foreach($commodities as $commoditylog)
                    <option @if($commoditylog->commodity_id==$commodity_id) selected @endif  value="{{$commoditylog->commodity_id}}">{{$commoditylog->commodity->name ?? 'N/A'}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div>
            <label>Card Type</label>
            <select class="form-control" name="cardtype_id" id="cardtype_id">
                <option value="">Select</option>
                @foreach($cardtypes as $cardtypelog)
                    <option @if($cardtypelog->cardtype_id==$cardtype_id) selected @endif  value="{{$cardtypelog->cardtype_id}}">{{$cardtypelog->cardtype->name ?? 'N/A'}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3 mt-3">
        <div>
            <label>Tray</label>
            <select class="form-control" name="tray_id" id="tray_id">
                <option value="">Select</option>
                @foreach($trays as $traylog)
                    <option @if($traylog->tray_id==$tray_id) selected @endif  value="{{$traylog->tray_id}}">{{$traylog->tray->name ?? 'N/A'}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3 mt-3">
        <div>
            <label>Push Call </label>
            <select name="pushcall" id="pushcall" class="form-control" onchange="showbatch()">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>
        </div>
    </div>

    <div class="col-lg-3 mt-3 pushcall" style="display: none;">
        <div>
            <label>Batch No:</label>
            <input class="form-control" value="0" type="name" name="batchno" id="batchno" required="">
        </div>
    </div>

    <div class="col-lg-3 mt-3 pushcall" style="display: none;">
        <div>
            <label>List ID:</label>
            <select class="form-control" name="listid" id="listid" required="">
                    <option value="0">Select List</option>
                @foreach($vlists as $vlist)
                    <option value="{{$vlist->list_id}}">{{$vlist->list_name}}</option>
                @endforeach
                    <option>Select List</option>
            </select>
        </div>
    </div>

    <div class="col-lg-3 mt-3 pushcall" style="display: none;">
        <div>
            <label>Priority:</label>
            <select class="form-control" name="priority" id="priority" required="">
                <option value="0">Select Priority</option>
                <option value="10">1</option>
                <option value="9">2</option>
                <option value="8">3</option>
            </select>
        </div>
    </div>

    <div class="col-lg-3 mt-5">
        <div>
         <h5>Total Shipments: {{$lists->count()}}</h5>
         </div>
     </div>
    

    <div class="col-lg-3 mt-4">
        <button type="submit" class="btn btn-primary waves-effect waves-light">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
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
                        <th>Customer Name</th>
                        <th>Pickup Date</th>
                        <th>Reference</th>
                        <th>Consignee Name</th>
                        <th>Consignee Phone</th>
                        <th>Alternate Phone</th>
                        <th>Manifest Number</th>
                        <th>Commodity Name</th>
                        <th>Customer Civil ID</th>
                        <th>Receiver Civil ID</th>
                        <th>Guardian Name</th>
                        <th>Description</th>
                        <th>Branch Name</th>
                        <th>Barcode</th>
                        <th>Tray No</th>
                        <th>Call Status</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($lists as $log)
                    <tr>
                        <td>{{$log->user->name ?? ' '}}</td>
                        <td>{{ $log->pickup_date }}</td>
                        <td>{{ $log->reference }}</td>
                        <td>{{ $log->consignee_name }}</td>
                        <td>{{ $log->consignee_phone }}</td>
                        <td>{{ $log->alternate_phone }}</td>
                        <td>{{ $log->manifest_number }}</td>
                        <td>{{ $log->commodity->name ?? $log->commodity_name }}</td>
                        <td>{{ $log->customer_civil_Id }}</td>
                        <td>{{ $log->receiver_civil_id }}</td>
                        <td>{{ $log->guardian_name }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->branch_name }}</td>
                        <td>{{ $log->barcode }}</td>
                        <td>{{ $log->tary->name ?? $log->tray_no }}</td>
                        <td>{{App\VicidialList::Status($log->vicidialList->status ?? '')  ?? ''}}</td>
                        <td>
                            <a href="{{url('/book/shipment/view')}}/{{$log->id}}" class="btn btn-sm btn-outline-warning edit-list">View</a>
                        </td>
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

function showbatch() {
    cc=$('#pushcall').val()
    if(cc=='No'){
        $('.pushcall').hide();
        $('#batchno').val(0);
        $('#listid').val(0);
    }
    else{
        $('.pushcall').show();
        $('#batchno').val('');
        $('#listid').val('');
    }
}
</script>
@stop



