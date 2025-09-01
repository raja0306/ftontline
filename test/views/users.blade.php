@extends('layouts.master')
@section('title', 'User add')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .form-check-label{ margin-right: 20px; margin-left: 5px; }
    .small-image {
    width: 100px;
    height: 100px;
    object-fit: cover; /* Crop and keep it nicely fit */
    border-radius: 6px; /* Optional: for rounded corners */
    border: 1px solid #ccc; /* Optional: border */
}
</style>
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">User Create</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted"><a data-bs-toggle="collapse" href="#addUser" aria-expanded="false" aria-controls="collapseExample" class="btn btn-sm btn-success">Add Users</a></li>
            </ol>
        </div>

    </div>
</div>
</div>
<style type="text/css">.w-75{ width:175px; } .w-475{ width:575px; } .w-175{ width:575px; }</style>
@php 
$campaigns = DB::connection('mysql2')->table('vicidial_campaigns')->select("campaign_id", "campaign_name")->get();
$lists = DB::connection('mysql2')->table('vicidial_lists')->select("list_id", "list_name")->get();
$ingroups = DB::connection('mysql2')->table('vicidial_inbound_groups')->select("group_id", "group_name")->get();
$agents = DB::connection('mysql2')->table('vicidial_users')->select("user", "full_name")->get();
$campaigns_1 = DB::connection('mysql2')->table('vicidial_campaigns')->select("campaign_id", "campaign_name")->get();
$lists_1 = DB::connection('mysql2')->table('vicidial_lists')->select("list_id", "list_name")->get();
$ingroups_1 = DB::connection('mysql2')->table('vicidial_inbound_groups')->select("group_id", "group_name")->get();
$agents_1 = DB::connection('mysql2')->table('vicidial_users')->select("user", "full_name")->get();
@endphp
<div class="row collapse" id="addUser">
<div class="card">
<div class="card-body">
<form  action="{{url('/user_store')}}" method="post" enctype="multipart/form-data">
{{csrf_field()}}  
    <div class="row  py-3">
    <div class="col-lg-3  mb-3">
        <div>
            <label>Code</label><br/>
            <input class="form-control" type="text" placeholder="Enter Code" value="" name="code" required="">
        </div>
    </div>
    <div class="col-lg-3  mb-3">
        <div>
            <label>Name</label><br/>
            <input class="form-control" type="text" placeholder="Enter Name" value="" name="name" required="">
        </div>
    </div>
    <div class="col-lg-3  mb-3">
        <div>
            <label>User name</label><br/>
            <input class="form-control" type="text" placeholder="Enter User Name" value="" name="email" required="">
        </div>
    </div>
    <div class="col-lg-3  mb-3">
        <div>
            <label>Password</label><br/>
            <input class="form-control" type="password" placeholder="Enter Password" value="" name="password" required="">
        </div>
    </div>
    <div class="col-lg-3  mb-3">
        <div>
            <label>Role</label><br/>
            <select class="form-control" name="role_id" required>
                <option value="">Select Role</option>
                @foreach($roles as $role_log)
                <option value="{{$role_log->id}}">{{$role_log->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 mb-3">
        <div>
            <label>Parent</label><br/>
            <select class="form-control" name="parent_id">
                <option value="">Select Parent</option>
                @foreach($users as $log)
                <option value="{{$log->id}}">{{$log->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <label>Mobile</label><br/>
        <input class="form-control" type="number" placeholder="Enter Mobile" value="" name="mobile">
    </div>

    <div class="col-lg-3 mb-3">
        <label>Image</label><br/>
        <input class="form-control" type="file" value="" name="file_image">
    </div>

    <div class="col-lg-3 mb-3">
        <label>Address</label><br/>
        <textarea class="form-control" name="address"></textarea>
    </div>

    </div>
    <div class="row d-none py-3">
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Campaigns</label><br/>
            <select class="select2 form-control w-175" name="campaign[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($campaigns as $camp)
                <option selected="" value="{{$camp->campaign_id}}">{{$camp->campaign_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Lists</label><br/>
            <select class="select2 form-control w-175" name="list_id[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($lists as $list)
                <option selected="" value="{{$list->list_id}}">{{$list->list_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Ingroups</label><br/>
            <select class="select2 form-control w-175" name="ingroup[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($ingroups as $group)
                <option selected="" value="{{$group->group_id}}">{{$group->group_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Agent</label><br/>
            <select class="select2 form-control w-175" name="agent[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($agents as $agent)
                <option selected="" value="{{$agent->user}}">{{$agent->full_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    </div>
    <div class="row pt-3">
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Campaigns</label><br/>
            <select class="select2 form-control w-175" name="campaign_1[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($campaigns_1 as $camp)
                <option selected="" value="{{$camp->campaign_id}}">{{$camp->campaign_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Lists</label><br/>
            <select class="select2 form-control w-175" name="list_id_1[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($lists_1 as $list)
                <option selected="" value="{{$list->list_id}}">{{$list->list_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Ingroups</label><br/>
            <select class="select2 form-control w-175" name="ingroup_1[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($ingroups_1 as $group)
                <option selected="" value="{{$group->group_id}}">{{$group->group_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6  mb-3 d-none">
        <div>
            <label>Agent</label><br/>
            <select class="select2 form-control w-175" name="agent_1[]" multiple="multiple" required>
                <option value="All">All</option>
                @foreach($agents_1 as $agent)
                <option selected="" value="{{$agent->user}}">{{$agent->full_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col-lg-4 mb-3">
        <button type="submit" class="btn btn-primary waves-effect waves-light mt-3">Submit</button>
    </div>
    </div>
</form>
</div>
</div>
</div>

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body" style="overflow-x: auto;">
            <div class="d-flex flex-wrap align-items-start">
                <h5 class="card-title mb-3 me-2">Users</h5>
            </div>
            @if(session('alert'))
                <div class="alert alert-success">{!! session('alert') !!}.</div>
            @endif
            <table id="datatable-buttons" class="table table-bordered">
                <thead>
                    <tr>  
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>User Name</th>
                        <th>Role</th>
                        <th>Parent</th>
                        <th>Mobile</th>
                        <th>Image</th>
                        <!-- <th>Status</th> -->
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($users as $log)
                    @php 
                    $userd = App\UserDetails::where('user_id',$log->id)->where('delete_status',0)->first();
                    $parent = App\User::where('id',$log->parent_id)->first();
                    @endphp
                    @if(!empty($userd))
                    <tr>
                        <td>{{$log->id}}</td>
                        <td>{{$log->code}}</td>
                        <td>{{$log->name}}</td>
                        <td>{{$log->email}}</td>
                        <td>{{$log->role->name ?? '-'}}</td>
                        <td>{{$parent->name ?? 'N/A'}}</td>
                        <td>{{$log->mobile}}</td>
                        <td><img class="small-image" src="{{ asset('/' . $log->imgae_file) }}"></td>
                        <!-- <td>
                            <p class="w-475"><strong>Campaign: </strong> {{$userd->campaign}}</p>
                            <p class="w-475"><strong>Lists: </strong> {{$userd->list_id}}</p>
                            <p class="w-475"><strong>Ingroups: </strong> {{$userd->ingroup}}</p>
                            <p class="w-475"><strong>Agents: </strong> {{$userd->agent}}</p>
                        </td> -->
                        <!-- <td><a href="{{url('/changestatusproject')}}/{{$log->id}}/1" class="btn btn-sm btn-success waves-effect waves-light"> Active</a></td> -->
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
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


<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>

<!-- form advanced init -->
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script type="text/javascript">
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});
</script>
@stop