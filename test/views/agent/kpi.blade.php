@extends('layouts.master')
@section('title', 'Agent Log Details')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">AgentTime Log Details</h4>

<div class="page-title-right">
    <ol class="breadcrumb m-0">
        <li class="text-muted">Agent Reports > </li>
        <li class="text-muted">Log Details</li>
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
                    <label>Agent Name</label>
                    <select class="form-control show-tick" name="campaign" id="campaign">
                        <option value="All">Select</option>
                        @foreach($agents as $agent)
                        <option value="{{$agent->user}}" @if($agent->user == $user) selected @endif>{{$agent->full_name}}</option>
                        @endforeach
                    </select>
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
    @if(!empty($agentname))<h4 class="text-dark text-center">{{$agentname}}</h4>@endif
    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
        <thead>
            <tr>
                <tr>   
                    <th>Date</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Total</th>
                </tr>
            </tr>
        </thead>           
        <tbody>
            @for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) 
            <?php
                $event_login = '';
                $event_logout = '';
                $logdiff = '';
                $timediff = 0;
                $thisDate = date( 'Y-m-d', $i );
                $timediff = 0;
                $event_login =App\VicidialUserLog::Login($thisDate,$user);
                $event_logout =App\VicidialUserLog::Logout($thisDate,$user);
            ?>
            @if(!empty($event_login) && !empty($event_logout))
            <?php 
                $timediff = strtotime($event_logout)-strtotime($event_login);
                $logdiff = App\Average::toMinutes($timediff);
            ?>
            <tr>
                <td>{{$thisDate}}</td>
                <td>{{$event_login}}</td>
                <td>{{$event_logout}}</td>
                <td>{{$logdiff}}</td>
            </tr>
            @endif
            @endfor
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

$('#submitbtn').click(function() {
var fdate = $("#fromdate").val();
var todate = $("#todate").val();
var user = $("#campaign").val();
var submiturl = "{{url('/agent/kpi')}}/"+fdate+"/"+todate+"/"+user;
window.location.href = submiturl;
});
</script>
@stop
