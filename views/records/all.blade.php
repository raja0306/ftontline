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
        <h4 class="mb-sm-0 font-size-18">@if($call_type=='out')Outgoing @else Incoming @endif Recordings</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Call Recordings > </li>
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
                    <div class="col-lg-3 d-none">
                        <div>
                            <label>Call Type</label>
                            <select class="form-control" name="call_type" id="call_type">
                                <option value="in" @if($call_type=='in')selected @endif>Incoming</option>
                                <option value="out" @if($call_type=='out')selected @endif>Outgoing</option>
                            </select>
                        </div>
                    </div>
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
                            <label>Campaign</label>
                            <select class="form-control" name="group" id="group">
                                {!!App\VicidialIngroup::Options($group)!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>List ID</label>
                            <select class="form-control" name="listid" id="listid">
                                {!!App\VicidialLists::Options($list_id)!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-3">
                        <div>
                            <label>Phone Number</label>
                            <input class="form-control" type="text" value="{{$phone}}" name="phone" id="phone">
                        </div>
                    </div>
                    <div class="col-lg-3 mt-4">
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
                        <th>Campaign</th>
                        <th>List</th>
                        <th>Call Date</th>
                        <th>Status</th>
                        <th>User</th>
                        <th>Length</th>
                        <th>Download</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($logs as $log)
                    <?php 
                        $wav_url =App\VicidialRecord::FindURLRCD($log['lead_id'],$log['end_epoch'],$log['call_date'],$log['phone_number']); 
                    ?>
                    @if(!empty($wav_url))
                    <tr class="ex2" onclick="playwav('{{$wav_url}}');">
                        <td>{{$log['lead_id']}}</td>
                        <td>{{$log['phone_number']}}</td>
                        <td>{{$log['campaign_id']}}</td>
                        <td>{{App\VicidialLists::FindName($log['list_id'])}}</td>
                        <td>{{$log['call_date']}}</td>
                        <td>{{App\VicidialList::Status($log['status'])}}</td>
                        <td>{{$log['user']}}</td>
                        <td>{{App\Average::toMinutes($log['length_in_sec'])}}</td>
                        <td>@if(!empty($wav_url))
                            <a href="{{$wav_url}}" download="" class="btn btn-outline-danger waves-effect waves-light btn-sm p-1 font-size-18"><i class="mdi mdi-download"></i></a>
                        @endif</td>
                    </tr>
                    @endif
                    @endforeach
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
    @if($call_type=='out')
        var murl = "{{url('/recordings/outbound')}}";
    @else
        var murl = "{{url('/recordings/inbound')}}";
    @endif
    var submiturl = murl+"?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&phone="+$("#phone").val()+"&call_type="+$("#call_type").val()+"&list_id="+$("#listid").val()+"&group="+$("#group").val();
    window.location.href = submiturl;
});
</script>
@stop


