@extends('layouts.master')
@section('title', 'All Recordings')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<style type="text/css">
tr.ex2:hover, a.ex2:active {cursor: pointer; background-color:#adf7a9  ! important;font-size: 115%;}
tr.selected {background-color:#adf7a9  ! important;}
</style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">All Recordings</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">All Recordings > </li>
                <li class="text-muted">Recordings</li>
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
                            <label>Phone Number</label>
                            <input class="form-control" type="text" value="{{$phone}}" name="phone" id="phone">
                        </div>
                    </div>
                    <div class="col-lg-3 text-center mt-3">
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
                        <th>#</th>
                        <th>Leadid</th>
                        <th>Phone</th>
                        <th>Call Date</th>
                        <th>User</th>
                        <th>Length</th>
                        <th>Recordings</th>
                    </tr>
                </thead>           
                <tbody>
                    @if(!empty($logs))
                    @foreach ($logs as $log)
                    <?php 
                        $call_date = date("Y-m-d",strtotime($log->start_time));
                        if($call_date>='2024-11-21'){ $wav_url = '/RECORDINGS/MP3/'.$call_date.'/'.$log->filename.'-all.mp3'; }
                        else{ $wav_url = '/RECORDINGS/MP3/'.$log->filename.'-all.mp3'; }
                    ?>
                    @if(!empty($wav_url))
                    <tr class="ex2" onclick="playwav('{{$wav_url}}');">
                        <td>{{$log->recording_id}}</td>
                        <td>{{$log->lead_id}}</td>
                        <td>{{$phone}}</td>
                        <td>{{date("Y-m-d H:i:s",strtotime($log->start_time))}}</td>
                        <td>{{$log->user}}</td>
                        <td>{{App\Average::toMinutes($log->length_in_sec)}}</td>
                        <td>@if(!empty($wav_url))
                            <a href="{{$wav_url}}" download="" class="btn btn-outline-danger waves-effect waves-light btn-sm p-1 font-size-18"><i class="mdi mdi-download"></i></a>
                        @endif</td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div> 

<div class="card overflow-hidden audioform" style="display: none;">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-sm-12 p-0">
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
<script src="https://unpkg.com/wavesurfer.js"></script>
<script type="text/javascript">
var wavesurfer = WaveSurfer.create({
    container: '#waveform',
    waveColor: 'violet',
    progressColor: 'purple'
});
$('#play').click(function() { wavesurfer.play(); });
$('#pause').click(function() { wavesurfer.pause(); });
$('#stop').click(function() { wavesurfer.stop(); });
$('#skipBackward').click(function() { wavesurfer.skipBackward(); });
$('#skipForward').click(function() { wavesurfer.skipForward(); });
function playwav(audio) {
    $(".audioform").show(); 
    wavesurfer.stop();
    wavesurfer.empty();
    wavesurfer.load(audio);
}
$("tr").click(function() {
  $(this).parent().children().removeClass("selected");
    $(this).addClass("selected");
    $('html, body').animate({
        scrollTop: $('.audioform').offset().top
    }, 'slow');
});
</script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('#submitbtn').click(function() {
    var submiturl = "{{url('/recordings_all')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&phone="+$("#phone").val();
    window.location.href = submiturl;
});
</script>
@stop


