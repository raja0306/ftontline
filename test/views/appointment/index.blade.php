<!DOCTYPE html>
<html lang="en">
 <head>
<meta charset="utf-8" />
<title>Customer Info Centrixplus</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Centrixplus - Callcenter CRM System" name="description" />
<meta content="Centrixplus" name="author" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{asset('/assets')}}/images/favicon.ico">
<link href="{{asset('/assets')}}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<script src="//unpkg.com/alpinejs" defer></script>
<style type="text/css">
    .page-content { padding: calc(50px + 24px) calc(24px / 2) 60px calc(24px / 2); }
    .selectnav .nav-link.active{ background-color: #009688; }
    .selectnav .nav-link{ color: #009688; border: 1px solid #009688; margin: 5px; }
    .select_hide{ display: none; }
    .select_show{ display: block; }
</style>
</head>
<body>
  <div id="layout-wrapper">
    <header id="page-topbar">
      <div class="navbar-header">
        <div class="d-flex">
          <div class="navbar-brand-box">
            <a href="#" class="logo" target="_blank">
            <span class="logo-lg">
                <img src="{{asset('/assets')}}/img/logo.png" alt="" height="39">
            </span>
            </a>
          </div>
        <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <i class="fa fa-fw fa-bars"></i>
        </button>
        <div class="mt-3">
            @if($listid==999)<span class="badge badge-pill badge-soft-info font-size-16 p-2 float-end">Incoming - {{$listname}}</span>@else<span class="badge badge-pill badge-soft-success font-size-16 p-2 float-end">Outgoing - {{$listname}}</span>@endif
        </div>
    </div>
<div class="d-flex">
    <form class="app-search d-none d-lg-block">
        <div class="position-relative">
            <input type="text" class="form-control" placeholder="Search..." id="searchid">
            <span class="bx bx-search-alt"></span>
        </div>
    </form>
<div class="btn-group m-3">
    <button type="button" class="btn btn-success dropdown-toggle searchby" data-bs-toggle="dropdown" aria-expanded="false">Search By <i class="mdi mdi-chevron-down"></i></button>
    <div class="dropdown-menu" style="">
        <a class="dropdown-item btnsearch" data-action="mobile" href="#">Mobile No.</a>
        <a class="dropdown-item btnsearch" data-action="civilid" href="#">Civil ID.</a>
    </div>

    <button type="button" style="margin-left: 5px;" class="btn btn-danger" id="close-tab-btn">Close</button>
</div>                                        
</div>
</div>
</header> 
<div class="main-content ms-0">
  <div class="page-content mt-0">
    <div class="container-fluid" style="max-width: 100%;">
      <div class="row">
        <div class="col-md-12">
          <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
            <div class="tab-pane fade active show" id="c-info" role="tabpanel" aria-labelledby="c-info">
                <div class="row mt-2">
                <div class="col-sm-3">
                    <div class="card">
                    <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="card-title"> <i class="bx bx-user me-1"></i>{{$customer->name ?? ''}}</div>
                        <div class="card-title"><i class="bx bx-mobile-alt"></i>{{$mobile}} </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-group input-group-sm float-end w-50">
                            <label class="input-group-text bg-dark text-white"><i class="bx bx-bookmark"></i></label>
                            <select class="form-select form-select-sm mark_customer">
                                <option value="{{$customer->flag}}" selected="">{{ucfirst($customer->flag) ?? 'Mark'}}</option>
                                <option value="special">Special</option>
                                <option value="complaint">Complaint</option>
                            </select>
                        </div>
                        <div>@if(!empty($lead)&&($lead->comments))<span class="text-info font-size-12"> {{$lead->comments}} </span>@endif</div>
                    </div>

                    <div class="dropdown-divider"></div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @php $ship=1; @endphp
                            @foreach($shipments as $shipment)
                            <a class="nav-link mb-2 font-size-12 @if($ship==1)active @endif" id="v-pills-{{$shipment->id}}-tab" data-bs-toggle="pill" href="#v-pills-{{$shipment->id}}" role="tab" aria-controls="v-pills-{{$shipment->id}}" aria-selected="true">{{$shipment->barcode}}  {{$shipment->description}}
                            {{$shipment->commodity_name}} - {{App\Shipment::getAge($shipment->customer_civil_Id)}}</a>
                            @php $ship++; @endphp
                            @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row bg-secondary bg-soft py-2">
                        <div class="col-md-12">
                            <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                                @php $ship=1; @endphp
                                @foreach($shipments as $shipment)
                                <div class="tab-pane fade @if($ship==1)show active @endif" id="v-pills-{{$shipment->id}}" role="tabpanel" aria-labelledby="v-pills-{{$shipment->id}}-tab">
                                    @include('appointment.shipment')
                                </div>
                                @php $ship++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="area_id2" value="">
                    </div>
                    </div>
                </div>
                <div class="col-sm-9">
                <div class="card">
                    <div class="card-body">        
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#appt_info" role="tab">
                                    Appointment Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#add_enquiry" role="tab">
                                    Add Status
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#appt_calls" onclick="getCallLogs();" role="tab">
                                    Call Logs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#appt_logs" onclick="getAppLogs();" role="tab">
                                    Appointment Logs
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="appt_info" role="tabpanel">
                                @if($appid>0)@include('appointment.appointment_edit') @else @include('appointment.appointment') @endif
                            </div>
                            <div class="tab-pane" id="add_enquiry" role="tabpanel">
                                @include('appointment.enquiry')
                            </div>
                            <div class="tab-pane" id="appt_calls" role="tabpanel">
                                <div class="Logs_call"></div>
                            </div>
                            <div class="tab-pane" id="appt_logs" role="tabpanel">
                                <div class="Logs_appt"></div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                </div>

            </div>
        </div>
      </div>
    </div>
</div>
</div>
</div>

</div>

<div class="right-bar">
</div>
<div class="rightbar-overlay"></div>

<script src="{{asset('/assets')}}/libs/jquery/jquery.min.js"></script>
<script src="{{asset('/assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('/assets')}}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{asset('/assets')}}/libs/simplebar/simplebar.min.js"></script>
<script src="{{asset('/assets')}}/libs/node-waves/waves.min.js"></script>
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script type="text/javascript">
$('.btnsearch').click(function() {
    var searchby = $(this).data("action");
    $(".searchby").html(searchby);
    var searchid = $("#searchid").val();
    if (searchid == '') {searchid = '0'; }
    searchid = searchid.replace("/", "__"); 
    if (searchby == 'mobile') {
        var seachurl = "{{url('/book/customer')}}/"+searchid+"/{{$user}}/{{$listid}}/{{$campaignid}}/{{$ingroup}}/{{$leadid}}";
    }
    else if (searchby == 'civilid') {
        var seachurl = "{{url('/customersearch')}}/"+searchid+"/{{$user}}/{{$listid}}/{{$campaignid}}/{{$ingroup}}/{{$leadid}}";
    }
    window.location.href = seachurl;
});

$('#area_id').select2();
$('#branch_id').select2();

$('.mark_customer').change(function () {
    $.ajax({ url: "{{url('mark_customer')}}/{{$customer->id ?? '0'}}/"+$(this).val(),
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $("#mark_customer").html(data.flag+' <i class="mdi mdi-chevron-down"></i>');
    },error:function(){}
});
});

$('#area_id').change(function () {
    $('#area_id2').val($(this).val());
    $('#appointment_date').trigger('change');
}); 
$('.useraddress_id').click(function () {
    var idval = $(this).data('id');
    // alert(idval);
    $('#area_id2').val(idval);
    $('#appointment_date').trigger('change');
});  
$('#appointment_date').change(function () {
    // alert($('#area_id2').val());
    $.ajax({ url: "{{url('date_apps')}}/"+$('#appointment_date').val()+"?editid={{$appid}}"+"&area="+$('#area_id2').val(),
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $(".app_counts").html("Booked: "+data.count);
        if(data.is_date){
           $('#appointment_date').val(''); 
        }
        $('.slothtml').html(data.slothtml);
        $('.appdate_alert').html(data.alert);
    },error:function(){}
});
});


function getCallLogs() {
    $(".New_appt").hide();
    $.ajax({ url: "{{url('customer/calllogs')}}/{{$mobile}}",
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $(".Logs_call").show().html(data.ihtml);
    },error:function(){}
    })
}

function getAppLogs() {
    $(".New_appt").hide();
    $.ajax({ url: "{{url('customer/apptlogs')}}/{{$mobile}}?url={{$user}}/{{$listid}}/{{$campaignid}}/{{$ingroup}}/{{$leadid}}",
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $(".Logs_appt").show().html(data.ihtml);
    },error:function(){}
    })
}

@if($appid>0)
    $(".new_cus").removeAttr('required');
@endif

$('.addrType').click(function() {
    var type = $(this).data('id');
    $('#address_type').val(type);
    // console.log(type);
    if (type==3) {
        $(".new_cus").removeAttr('required');
        $(".useraddress_id").attr('required', 'true');
        $("#branch_id").removeAttr('required');
        $("#branch_id").val('');
    }
    else if (type==2) {
        $(".new_cus").removeAttr('required');
        $(".useraddress_id").removeAttr('required');
        $("#branch_id").attr('required', 'true');
        $('.slothtml').html('');
        $("#area_id").val('');
    }
    else {
        $(".new_cus").attr('required', 'true');
        $(".useraddress_id").removeAttr('required');
        $("#branch_id").removeAttr('required');
        $("#branch_id").val('');
    }
});

$('#close-tab-btn').click(function () { window.close(); });

$('#branch_id').on('change', function() {
    let selectedOption = $(this).find('option:selected');
    let idval = selectedOption.data('val');
    $('#area_id2').val(idval);
    // $('#appointment_date').trigger('change');
});

function checkDeliveryNoteLength(el) {
    let max = 100;
    let currentLength = el.value.length;
    if (currentLength > max) {
        el.value = el.value.substring(0, max);
        currentLength = max;
    }
    document.getElementById('charCount').innerText = currentLength + ' / ' + max;
}

$('.edit_addr').click(function () {
    $('.addrType-3').removeClass('active');
    $('.addrType-1').addClass('active');
    $('#address_type').val(1);
    $(".new_cus").removeAttr('required');
    $("#useraddress_id").removeAttr('required');
    $("#branch_id").removeAttr('required');
    //alert(""+$(this).data('block'));
    $('#block').val($(this).data('block'));
    $('#street').val($(this).data('street'));
    $('#avenue').val($(this).data('avenue'));
    $('#floor_no').val($(this).data('floor_no'));
    $('#flat_no').val($(this).data('flat_no'));
    $('#house_no').val($(this).data('house_no'));
    $('#pacii_no').val($(this).data('pacii_no'));
    $('#landmark').val($(this).data('landmark'));
    $('#lat').val($(this).data('lat'));
    $('#longi').val($(this).data('longi'));
    $("#area_id").val($(this).data('area_id')).trigger('change');
});
</script>
</body>
</html>