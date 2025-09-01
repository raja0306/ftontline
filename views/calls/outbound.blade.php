@extends('layouts.master')
@section('title', 'Outgoing Calls')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Outgoing Details</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Call Logs > </li>
                <li class="text-muted">Outgoing Calls</li>
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
                            <label>List ID</label>
                            <select class="form-control show-tick" name="campaign" id="campaign">
                                {!!App\VicidialLists::Options($campaign)!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Phone Number</label>
                            <input class="form-control" type="text" value="{{$phone}}" name="phone" id="phone">
                            <input class="form-control" type="hidden" value="{{$status}}" name="status" id="status">
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
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Lead #</th>
                        <th>Phone</th>
                        <th>List ID</th>
                        <th>Call Date</th>
                        <th>Status</th>
                        <th>User</th>
                        <th>Length</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($dial_logs as $log)
                    <tr>
                        <td>{{$log->lead_id}}</td>
                        <td>{{$log->phone_number}}</td>
                        <td>{{App\VicidialLists::FindName($log->list_id)}}</td>
                        <td>{{$log->call_date}}</td>
                        <td>{{App\VicidialList::Status($log->status)}}  </td>
                        <td>{{$log->user}}</td>
                        <td>{{App\Average::toMinutes($log->length_in_sec)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end col -->

<div class="card overflow-hidden audioform" style="display: none;">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-sm-12 pt-3">
                <div class="row">
                    <div id="waveform"></div>
                    <div class="text-center mt-2">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" id="play"><i class="bx bx-play label-icon"></i> Play</button>
                        <button type="button" class="btn btn-warning waves-effect btn-label waves-light" id="pause"><i class="bx bx-pause label-icon"></i> Pause</button>
                        <button type="button" class="btn btn-danger waves-effect btn-label waves-light" id="stop"><i class="bx bx-stop label-icon"></i> Stop</button>
                        <button type="button" class="btn btn-secondary waves-effect btn-label waves-light" id="skipBackward"><i class="bx bx-rewind label-icon"></i> Backward</button>
                        <button type="button" class="btn btn-secondary waves-effect btn-label waves-light" id="skipForward"><i class="bx bx-fast-forward label-icon"></i> Forward</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- end row -->
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
    var campaign = $("#campaign").val();
    var phone = $("#phone").val();
    var status = $("#status").val();
    var submiturl = "{{url('/calls/outbound')}}?status="+status+"&fromdate="+fdate+"&todate="+todate+"&phone="+phone+"&campaign="+campaign;
    window.location.href = submiturl;
});
</script>
@stop

