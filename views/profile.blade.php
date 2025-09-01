@extends('layouts.master')
@section('title', 'User Profile')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .form-check-label{ margin-right: 20px; margin-left: 5px; }
</style>
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">User Profile</h4>
    </div>
</div>
</div>

@php 
$campaigns = DB::connection('mysql2')->table('vicidial_campaigns')->select("campaign_id", "campaign_name")->get();
$lists = DB::connection('mysql2')->table('vicidial_lists')->select("list_id", "list_name")->get();
$ingroups = DB::connection('mysql2')->table('vicidial_inbound_groups')->select("group_id", "group_name")->get();
$agents = DB::connection('mysql2')->table('vicidial_users')->select("user", "full_name")->get();
@endphp

<div class="row">
<div class="card overflow-hidden">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <h5 class="font-size-15 text-truncate">{{Auth::user()->name}}</h5>
                <p class="text-muted mb-0 text-truncate">Name</p>
            </div>
            <div class="col-sm-4">
                <h5 class="font-size-15 text-truncate">{{Auth::user()->email}}</h5>
                <p class="text-muted mb-0 text-truncate">Username</p>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row">
<div class="card overflow-hidden">
    <div class="card-body pt-0">
		<form  action="{{url('/user_update')}}" method="post">
		{{csrf_field()}}  
		<div class="row">
		<div class="col-xl-3 col-sm-6">
		<div class="mt-4">
		    <h5 class="font-size-14 mb-4">Campaigns
		    </h5>
		    @php
			    $selectedcampaigns = \App\UserDetails::getColumn('campaign');
			    $selectedcampaignsArray = explode(',', $selectedcampaigns); 
			@endphp
			<h4 class="d-none">{{$selectedcampaigns}}</h4>
		    @foreach($campaigns as $camp)
		    <div class="form-check mb-3">
		        <input class="form-check-input" name="campaign[]" value="{{$camp->campaign_id}}" type="checkbox" id="campaign_{{$camp->campaign_id}}"
		        @if(in_array($camp->campaign_id, $selectedcampaignsArray)) checked @endif>
		        <label class="form-check-label" for="campaign_{{$camp->campaign_id}}">
		            {{$camp->campaign_name}} - {{$camp->campaign_id}}
		        </label>
		    </div>
		    @endforeach
		</div>
		</div>
		<div class="col-xl-3 col-sm-6">
		<div class="mt-4">
		    <h5 class="font-size-14 mb-4">Ingroups
		    </h5>
		    @php
			    $selectedingroups = \App\UserDetails::getColumn('ingroup');
			    $selectedingroupsArray = explode(',', $selectedingroups); 
			@endphp
			<h4 class="d-none">{{$selectedingroups}}</h4>
		    @foreach($ingroups as $camp)
		    <div class="form-check mb-3">
		        <input class="form-check-input" name="ingroup[]" value="{{$camp->group_id}}" type="checkbox" id="ingroup_{{$camp->group_id}}"
		        @if(in_array($camp->group_id, $selectedingroupsArray)) checked @endif>
		        <label class="form-check-label" for="ingroup_{{$camp->group_id}}">
		            {{$camp->group_name}} - {{$camp->group_id}}
		        </label>
		    </div>
		    @endforeach
		</div>
		</div>
		<div class="col-xl-3 col-sm-6">
		<div class="mt-4">
		    <h5 class="font-size-14 mb-4">Lists
		    </h5>
		    @php
			    $selectedlists = \App\UserDetails::getColumn('list_id');
			    $selectedlistsArray = explode(',', $selectedlists); 
			@endphp
			<h4 class="d-none">{{$selectedlists}}</h4>
		    @foreach($lists as $camp)
		    <div class="form-check mb-3">
		        <input class="form-check-input" name="list_id[]" value="{{$camp->list_id}}" type="checkbox" id="list_{{$camp->list_id}}"
		        @if(in_array($camp->list_id, $selectedlistsArray)) checked @endif>
		        <label class="form-check-label" for="list_{{$camp->list_id}}">
		            {{$camp->list_name}} - {{$camp->list_id}}
		        </label>
		    </div>
		    @endforeach
		</div>
		</div>
		<div class="col-xl-3 col-sm-6">
		<div class="mt-4">
		    <h5 class="font-size-14 mb-4">Agents
		    </h5>
		    @php
			    $selectedusers = \App\UserDetails::getColumn('agent');
			    $selectedusersArray = explode(',', $selectedusers); 
			@endphp
			<h4 class="d-none">{{$selectedusers}}</h4>
		    @foreach($agents as $camp)
		    <div class="form-check mb-3">
		        <input class="form-check-input" name="agent[]" value="{{$camp->user}}" type="checkbox" id="user_{{$camp->user}}"
		        @if(in_array($camp->user, $selectedusersArray)) checked @endif>
		        <label class="form-check-label" for="user_{{$camp->user}}">
		            {{$camp->full_name}} - {{$camp->user}}
		        </label>
		    </div>
		    @endforeach
		</div>
		</div>
	    <div class="col-lg-12 text-center mb-3">
	        <button type="submit" class="btn btn-success waves-effect waves-light mt-3">Update</button>
	    </div>
		</div>
		</form>
    </div>
</div>
</div>



@stop
@section('ScriptPage')
</script>
@stop