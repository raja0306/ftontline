@extends('layouts.master')
@section('title', 'Centrix Metrics Data')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Centrix Metrics Data</h4>

        <div class="page-title-right">
            <a href="{{url('/generate_metrics')}}?month={{date('m', strtotime('-1 months'))}}&year={{date('Y', strtotime('-1 months'))}}" class="btn btn-sm btn-success">Generate</a>
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
                            <label>Month</label>
                            <select class="form-control" name="month" id="month">
                                <option value="">Choose</option>
                                <option value="1" @if($month==1) selected @endif>January</option>
                                <option value="2" @if($month==2) selected @endif>February</option>
                                <option value="3" @if($month==3) selected @endif>March</option>
                                <option value="4" @if($month==4) selected @endif>April</option>
                                <option value="5" @if($month==5) selected @endif>May</option>
                                <option value="6" @if($month==6) selected @endif>June</option>
                                <option value="7" @if($month==7) selected @endif>July</option>
                                <option value="8" @if($month==8) selected @endif>Augest</option>
                                <option value="9" @if($month==9) selected @endif>September</option>
                                <option value="10" @if($month==10) selected @endif>October</option>
                                <option value="11" @if($month==11) selected @endif>November</option>
                                <option value="12" @if($month==12) selected @endif>December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Year</label>
                            <select class="form-control" name="year" id="year">
                                <option value="">Choose</option>
                                @for($yr=date("Y"); $yr>=2021;$yr--)
                                    <option value="{{$yr}}" @if($year==$yr) selected @endif>{{$yr}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-3">
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="searchbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                    </div>
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
            <table id="datatable-buttons" class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Outbound Call List</th>
                        <th>Call Attempts</th>
                        <th>Avg # of attempts</th>
                        <th>Successful Connected</th>
                        <th>Total Incoming Calls</th>
                        <th>During working hours</th>
                        <th>After working hours</th>
                        <th>Total Abandon Calls</th>
                        <th>Abandon Ratio (%)</th>
                        <th>Missed during work hours</th>
                        <th>Missed after work hours</th>
                        <th>Net Abandon Calls</th>
                        <th>Net Abandon Calls (%)</th>
                        <th>Net Missed during work hours</th>
                        <th>Net Missed after work hours</th>
                        <th>Occupancy Level (%)</th>
                        <th>Service Level (%)</th>
                        <th>Waiting Time (%)</th>
                        <th>Queue Time (%)</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach($lists as $log)
                    <?php 
                        $cmonth = $log->year.'-'.$log->month.'-01';
                        $fdate = $cmonth; 
                    ?>
                    <tr class="gradeX">
                        <td>{{date("F", strtotime($cmonth))}}</td>
                        <td>{{$log->year}}</td>
                        <td>{{$log->attempts}}</td>
                        <td>{{$log->outbound}}</td>
                        <td>{{\App\Average::MathZDC($log->outbound,$log->attempts)}}</td>
                        <td>{{$log->outbound_connected}}</td>
                        <td>{{$log->inbounds+$log->missed_after2}}</td>
                        <td>{{$log->inbounds-$log->missed_after1}}</td>
                        <td>{{$log->missed_after}}</td>
                        <td>{{$log->missed+$log->missed_after2}}</td>
                        <td>{{\App\Average::MathPER(($log->missed+$log->missed_after2),$log->inbounds)}}</td>
                        <td>{{$log->missed-$log->missed_after}}</td>
                        <td>{{$log->missed_after}}</td>
                        <td>{{$log->net_missed}}</td>
                        <td>{{\App\Average::MathPER($log->net_missed,$log->inbounds)}}</td>
                        <td>{{$log->net_missed_after}}</td>
                        <td>{{$log->net_missed-$log->net_missed_after}}</td>
                        <td>{{$log->occperc}}</td>
                        <td>{{$log->slevelperc}}</td>
                        <td>{{App\Average::MathPER($log->wait_time,$log->log_time)}}</td>
                        <td>{{App\Average::MathPER($log->queuesecs,$log->talksecs)}}</td>
                    </tr>
                    @endforeach
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

$('#searchbtn').click(function() {
    var month = $("#month").val();
    var year = $("#year").val();
    var submiturl = "{{url('/centrix_metrics')}}?month="+month+"&year="+year;
    window.location.href = submiturl;
});
</script>
@stop