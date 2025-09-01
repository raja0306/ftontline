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
    <style type="text/css">
        .selectnav .nav-link.active{ background-color: #009688; }
        .selectnav .nav-link{ color: #009688; border: 1px solid #009688; margin: 5px; }
        .select_hide{ display: none; }
        .select_show{ display: block; }
    </style>
</head>
<body data-topbar="dark" data-layout="horizontal">
  <div id="layout-wrapper">
    <header id="page-topbar">
      <div class="navbar-header">
        <div class="d-flex">
          <div class="navbar-brand-box">
            <a href="#" class="logo logo-light" target="_blank">
            <span class="logo-lg">
                <img src="{{asset('/assets')}}/img/logo.png" alt="" height="39">
            </span>
            </a>
          </div>
        <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <i class="fa fa-fw fa-bars"></i>
        </button>
        <div class="mt-3">
            @if($listid==999)<span class="badge rounded-pill bg-info mx-2 font-size-16 p-2 float-end">Incoming - {{$listname}}</span>@else<span class="badge rounded-pill bg-success mx-2 font-size-16 p-2 float-end">Outgoing - {{$listname}}</span>@endif
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
<div class="main-content">
  <div class="page-content mt-0">
    <div class="container-fluid" style="max-width: 100%;">
      <div class="row">
        <div class="col-md-12">
          <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
            <div class="tab-pane fade active show" id="c-info" role="tabpanel" aria-labelledby="c-info">
                @include('customer.ext_customer')
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
$(document).ready(function() {

});

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

// $('#address_type').change(function() {
//     $('.branch_id').addClass('d-none');
//     if($(this).val()=='2'){
//         $('.branch_id').removeClass('d-none');
//     }
// });

// $('.useraddress_id').click(function() {
//     var is_new=$('#is_newaddress').val();
//     if(is_new=='0'){
//         $('#is_newaddress').val('1');
//         $(".new_cus").attr('required', 'true');
//         $("#useraddress_id").val('').removeAttr('required');
//     }
//     else{
//         $('#is_newaddress').val('0');
//         $(".new_cus").removeAttr('required');
//         $("#useraddress_id").attr('required', 'true');
//     }
// });
$('#area_id').select2();
$('#branch_id').select2();

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
    console.log(type);
    if (type==3) {
        $(".new_cus").removeAttr('required');
        $(".useraddress_id").attr('required', 'true');
        $("#branch_id").removeAttr('required');
    }
    else if (type==2) {
        $(".new_cus").removeAttr('required');
        $(".useraddress_id").removeAttr('required');
        $("#branch_id").attr('required', 'true');
    }
    else {
        $(".new_cus").attr('required', 'true');
        $(".useraddress_id").removeAttr('required');
        $("#branch_id").removeAttr('required');
    }
});

$('#close-tab-btn').click(function () {
            window.close();
        });

$('#appointment_date').change(function () {
    $.ajax({ url: "{{url('date_apps')}}/"+$(this).val()+"?editid={{$appid}}",
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $(".app_counts").html("Booked: "+data.count);
        if(data.is_date){
           $('#appointment_date').val(''); 
        }
    },error:function(){}
});
});


$('.edit_addr').click(function () {
    $('.addrType-3').removeClass('active');
    $('.addrType-1').addClass('active');
    $('#address_type').val(1);
    $(".new_cus").removeAttr('required');
    $("#useraddress_id").attr('required', 'true');
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

$('#submitappbtn').click(function () {
    // $(this).attr("disabled", "disabled");   
});
</script>
</body>
</html>