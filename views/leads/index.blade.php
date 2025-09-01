@extends('layouts.master')
@section('title', 'All Calls')
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
        <h4 class="mb-sm-0 font-size-18">Call Details</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Call Logs > </li>
                <li class="text-muted">All Calls</li>
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
                            <label>Date Type</label>
                            <select class="form-control" name="datetype" id="datetype">
                                <option value="entry_date" @if($datetype=='entry_date') selected @endif>Entry date</option>
                                <option value="modify_date" @if($datetype=='modify_date') selected @endif>Modify date</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>List ID</label>
                            <select class="form-control" name="listid" id="listid">
                                {!!App\VicidialLists::Options($listid)!!}
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 mt-3">
                        <div>
                            <label>Status</label>
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="statuss" value="B,AB" type="checkbox" id="stat_B"
                                 @if(strpos('C_'.$status, 'C_B') !== false) checked="" @endif>
                                <label class="form-check-label" for="stat_B">Busy</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="statuss" value="NEW" type="checkbox" id="stat_NEW"
                                 @if(strpos($status, 'NEW') !== false) checked="" @endif>
                                <label class="form-check-label" for="stat_NEW">Dialable</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="statuss" value="DROP" type="checkbox" id="stat_DROP"
                                 @if(strpos($status, 'DROP') !== false) checked="" @endif>
                                <label class="form-check-label" for="stat_DROP">DROP</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="statuss" value="NA" type="checkbox" id="stat_NA"
                                 @if(strpos($status, 'NA') !== false) checked="" @endif>
                                <label class="form-check-label" for="stat_NA">No Answer</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" name="statuss" value="Answer" type="checkbox" id="stat_Answer"
                                 @if(strpos($status, 'Answer') !== false) checked="" @endif>
                                <label class="form-check-label" for="stat_Answer">Answer</label>
                            </div>
                            <input type="hidden" id="status" value="">
                        </div>
                    </div>
                    <div class="col-lg-3 mt-3">
                    <div>
                         <label>Batch No</label>
                         <input value="{{$batchno}}" class="form-control" type="text" value="" id="batchno" name="batchno">
                    </div>
                    </div>
                    <div class="col-lg-3 mt-3">
                        <div>
                            <label>Phone Number</label>
                            <input class="form-control" type="text" value="{{$phone}}" name="phone" id="phone">
                        </div>
                    </div>
                      <div class="col-lg-2 mt-3">
                        <div>
                            <label>Total Calls</label>
                            <h4>{{$listcount}}</h4>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light d-none" id="exportbtn">Export <i class="mdi mdi-arrow-up ms-1"></i></button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                        <button type="button" class="btn btn-warning waves-effect waves-light" id="resetbtn">Reset <i class="mdi mdi-reload ms-1"></i></button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" id="clearbtn">Clear <i class="mdi mdi-close ms-1"></i></button>
                        <input type="hidden" id="resetstatus" value="0">
                        <input type="hidden" id="resetstatus" value="0">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@if(count($lists)>0)
<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>Lead ID</th>
                        <th>Entry Date</th>
                        <th>Modify Date</th>
                        <th>ListName</th>
                        <th>Status</th>
                        <th>Phone Number</th>
                        <th>User</th>
                        <th>Caller Count</th>
                        <th>Batch No</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($lists as $log)
                    <tr>
                        <td>{{$log->lead_id}}</td>
                        <td>{{$log->entry_date}}</td>
                        <td>{{$log->modify_date}}</td>
                        <td>{{App\VicidialLists::FindName($log->list_id)}}</td>
                        <td>@if($log->status == 'B') Busy @elseif($log->status == 'AB') Phone Busy @elseif($log->status == 'DA') Pending  @elseif($log->status == 'NEW') Dialable @elseif($log->status == 'DROP') Drop @elseif($log->status == 'ADC' || $log->status == 'ERI' || $log->status == 'NA') No Answer @else Answer @endif</td>
                        <td>{{$log->phone_number}}</td>
                        <td>{{$log->user}}</td>
                        <td>{{$log->called_count}}</td>
                        <td>{{$log->batchno}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
           
            
        </div>
    </div>
</div>
@endif
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



