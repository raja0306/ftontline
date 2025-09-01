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
                <img src="{{asset('/assets')}}/images/logo-light.png" alt="" height="39">
            </span>
            </a>
          </div>
        <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <i class="fa fa-fw fa-bars"></i>
        </button>
    </div>
<div class="d-flex">
    <form class="app-search d-none d-lg-block">
        <div class="position-relative">
            <input type="text" class="form-control" placeholder="Search..." id="searchid">
            <span class="bx bx-search-alt"></span>
        </div>
    </form>
<div class="btn-group m-3">
    <button type="button" class="btn btn-danger dropdown-toggle searchby" data-bs-toggle="dropdown" aria-expanded="false">Search By <i class="mdi mdi-chevron-down"></i></button>
    <div class="dropdown-menu" style="">
        <a class="dropdown-item btnsearch" data-action="mobile" href="#">Mobile No.</a>
        <a class="dropdown-item btnsearch" data-action="fileno" href="#">File No.</a>
    </div>
</div>                                        
</div>
</div>
</header> 

<div class="topnav">
<div class="container-fluid">
<nav class="navbar navbar-light navbar-expand-lg topnav-menu">
<div class="collapse navbar-collapse" id="topnav-menu-content">
  <div class="col-xl-10">
    <div class="card mb-0">
      <div class="card-body">
        <ul class="nav nav-pills nav-justified" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link active" data-bs-toggle="tab" href="#c-info" role="tab"><i class="fas fa-user-circle me-1"></i> Customer & Shipments
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#enq-info" role="tab"><i class="fas fa-list-alt me-1"></i> Appointment Lists</a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#c-form" role="tab"><i class="fas fa-atlas me-1"></i> Call Logs</a>
            </li>
        </ul>
      </div>
    </div>
</div>
<div class="col-xl-2"> 
</div>
</div>
</nav>
</div>
</div>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid" style="max-width: 100%;">
      <div class="row">
        <div class="col-md-12">
          <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
            <div class="tab-pane fade active show" id="c-info" role="tabpanel" aria-labelledby="c-info">
                @include('customer.ext_customer')
            </div>
            <div class="tab-pane fade" id="enq-info" role="tabpanel" aria-labelledby="enq-info">
                @include('customer.appointment_info')
            </div>
            <div class="tab-pane fade" id="c-form" role="tabpanel" aria-labelledby="c-form">
                @include('customer.ext_calls')
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
<script type="text/javascript">
$(document).ready(function() {

});


$('.editenable').click(function() {
            $(".customer-form .cusinfo").prop('disabled', false);
});

$('.btnsearch').click(function() {
    var searchby = $(this).data("action");
    $(".searchby").html(searchby);
    var searchid = $("#searchid").val();
    if (searchid == '') {searchid = '0'; }
    searchid = searchid.replace("/", "__"); 
    if (searchby == 'mobile') {
        var seachurl = "{{url('/customerappointment')}}/"+searchid+"/{{$listid}}/{{$leadid}}";
    }
    else if (searchby == 'fileno') {
        var seachurl = "{{url('/customerfilesearch')}}/"+searchid+"/{{$listid}}/{{$leadid}}";
    }
    window.location.href = seachurl;
});

</script>
</body>
</html>