@extends('layouts.master')
@section('title', 'Shipment Dashboard')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/htmx.org@1.9.2"></script>
@stop
@section('pageBody')
<!-- start page title -->
<div class="row">
    <div class="col-xl-5">
    <div class="card overflow-hidden">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="">
                        <div class="row">
                          <div class="mb-3 col-md-5">
                              <label class="col-md-4 col-form-label">From :</label>
                                <input class="form-control" type="date" min="2024-08-14" value="{{$fromdate}}" name="fromdate" id="fromdate">
                          </div>
                          <div class="mb-3 col-md-5">
                              <label class="col-md-4 col-form-label">To :</label>
                                <input class="form-control" type="date" min="2024-08-14" value="{{$todate}}" name="todate" id="todate">
                          </div>
                          <div class="mt-3 text-center col-md-2"><br>
                              <button type="button" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search </button>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-xl-7">
        <div hx-get="{{url('/statusdashboard')}}?fromdate={{$fromdate}}&todate={{$todate}}" 
             hx-trigger="load, every 60s" 
             hx-target="#shipment_status_count">
             <div id="shipment_status_count">Loading content</div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-lg-12">
      <div hx-get="{{url('/dashboard/cardstatus')}}?fromdate={{$fromdate}}&todate={{$todate}}" 
           hx-trigger="load, every 60s" 
           hx-target="#shipment_card_status_count">
           <div id="shipment_card_status_count">Loading content</div>
      </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
      <div hx-get="{{url('/dashboard/driverstatus')}}?fromdate={{$fromdate}}&todate={{$todate}}" 
           hx-trigger="load, every 60s" 
           hx-target="#driver_delivery_status_count">
           <div id="driver_delivery_status_count">Loading content</div>
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
