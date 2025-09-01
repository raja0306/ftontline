<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8" />
<title>@yield('title') | Centrixplus</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Centrixplus - Callcenter CRM System" name="description" />
<meta content="Centrixplus" name="author" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{asset('/assets')}}/img/favicon.ico">
<link href="{{asset('/assets')}}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
@yield('StylePage')
<style type="text/css">@if(Auth::user()->id=='11') .gm_user{ display:none !important; } @else .cec_user{ display:none !important; } @endif</style>
</head>

<body data-sidebar="dark">

<div id="layout-wrapper">
@include('layouts.head')
@include('layouts.left')

<div class="main-content">
<div class="page-content">
<div class="container-fluid">
	@yield('pageBody')
</div>
</div>
</div>

</div>

<div class="rightbar-overlay"></div>

<script src="{{asset('/assets')}}/libs/jquery/jquery.min.js"></script>
<script src="{{asset('/assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('/assets')}}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{asset('/assets')}}/libs/simplebar/simplebar.min.js"></script>
<script src="{{asset('/assets')}}/libs/node-waves/waves.min.js"></script>

<script src="{{asset('/assets')}}/js/app.js"></script>
@yield('ScriptPage')
</body>

</html>
