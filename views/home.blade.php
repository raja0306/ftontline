@extends('layouts.master')
@section('title', 'Dashboard')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/htmx.org@1.9.2"></script>
@stop
@section('pageBody')
<!-- start page title -->
<div class="row">
    <div class="col-xl-4">
    <div class="card overflow-hidden">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="pt-4">
                        <div class="row">
                          <div class="mb-3 row">
                              <label class="col-md-4 col-form-label">From :</label>
                              <div class="col-md-8">
                                <input class="form-control" type="date" min="2024-08-14" value="{{$fromdate}}" name="fromdate" id="fromdate">
                              </div>
                          </div>
                          <div class="mb-3 row">
                              <label class="col-md-4 col-form-label">To :</label>
                              <div class="col-md-8">
                                <input class="form-control" type="date" min="2024-08-14" value="{{$todate}}" name="todate" id="todate">
                              </div>
                          </div>
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-xl-8">
        <div hx-get="{{url('/dashboard')}}?fromdate={{$fromdate}}&todate={{$todate}}" hx-trigger="load, every 60s" hx-target="#calls_count"><div id="calls_count">Loading content</div></div>
    </div>
</div>

<div class="row">
<div class="col-md-6">
<div class="card overflow-hidden">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-aftercadmissed_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseaftercadmissed_summary" aria-expanded="true" aria-controls="flush-collapseaftercadmissed_summary">
    Working Hours - Missed Calls
    </button>
</h2>
<div id="flush-collapseaftercadmissed_summary" class="accordion-collapse collapse show" aria-labelledby="flush-aftercadmissed_summary">
    <div hx-get="{{url('/gmafternetmissed')}}?fromdate={{$fromdate}}&todate={{$todate}}&type=work" hx-trigger="load, every 120s" hx-target="#gmafternetmissed_work"><div id="gmafternetmissed_work" class="gm_user"></div></div>
</div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="card overflow-hidden">
<div class="accordion-item">
<h2 class="accordion-header" id="flush-chevmissed_summary">
    <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsechevmissed_summary" aria-expanded="true" aria-controls="flush-collapsechevmissed_summary">
    AfterWork Hours - Missed Calls
    </button>
</h2>
<div id="flush-collapsechevmissed_summary" class="accordion-collapse collapse show" aria-labelledby="flush-chevmissed_summary">
    <div hx-get="{{url('/gmafternetmissed')}}?fromdate={{$fromdate}}&todate={{$todate}}&type=after" hx-trigger="load, every 120s" hx-target="#gmafternetmissed_after"><div id="gmafternetmissed_after" class="gm_user"></div></div>
</div>
</div>
</div>
</div>
</div>
<div hx-get="{{url('/outbound_summary')}}?fromdate={{$fromdate}}&todate={{$todate}}" hx-trigger="load, every 30s" hx-target="#outbound_summary"><div id="outbound_summary"></div></div>
<div hx-get="{{url('/agent_summary')}}?fromdate={{$fromdate}}&todate={{$todate}}" hx-trigger="load, every 30s" hx-target="#agent_summary"><div id="agent_summary"></div></div>
<div hx-get="{{url('/hourly_calls')}}?fromdate1={{$fromdate}}" hx-trigger="load" hx-target="#hourly_calls"><div id="hourly_calls"></div></div>
<div hx-get="{{url('/queue_calls')}}" hx-trigger="load, every 5s" hx-target="#queue_calls"><div id="queue_calls"></div></div>
<div hx-get="{{url('/live_calls')}}" hx-trigger="load, every 5s" hx-target="#live_calls"><div id="live_calls"></div></div>
<!-- <div hx-get="{{url('/list_dashboards')}}?fromdate={{$fromdate}}&todate={{$todate}}" hx-trigger="load" hx-target="#list_details"><div id="list_details"></div></div> -->

<div hx-get="{{url('/list_dashboards1')}}?fromdate={{$fromdate}}&todate={{$todate}}" hx-trigger="load, every 30s" hx-target="#list_details1"><div id="list_details1"></div></div>
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
<script src="{{asset('/assets')}}/apexcharts/dist/apexcharts.js"></script>

<script type="text/javascript">
$('#submitbtn').click(function() {
    var fdate = $("#fromdate").val();
    var todate = $("#todate").val();
    var submiturl = "{{url('/')}}?fromdate="+fdate+"&todate="+todate;
    window.location.href = submiturl;
});
</script>         
@stop
