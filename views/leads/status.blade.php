@extends('layouts.master')
@section('title', 'List Status Details')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">List Status Details</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Dialer Leads > </li>
                <li class="text-muted">Lists Details</li>
            </ol>
        </div>

    </div>
</div>
</div>

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-start">
                <h5 class="card-title mb-3 me-2">Subscribes</h5>
                <div class="dropdown ms-auto">
                <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal">Add New List <i class="mdi mdi-arrow-right ms-1"></i></button>
                </div>
            </div>
            @if(session('alert'))
                <div class="alert alert-success">{!! session('alert') !!}.</div>
            @endif
            <table id="datatable-buttons" class="table table-bordered w-100">
                <thead>
                    <tr>  
                        <th>List ID</th>
                        <th>List Name</th>
                        <th>Campaign ID</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($lists as $log)
                    <tr>
                        <td>{{$log->list_id}}</td>
                        <td>{{$log->list_name}}</td>
                        <td>{{$log->campaign_id}}</td>
                        <td>{{$log->list_description}}</td>
                        <td>@if($log->active == 'Y') <a href="{{url('/changestatus')}}/{{$log->list_id}}/{{$log->active}}" class="btn btn-sm btn-success waves-effect waves-light"> Active</a> @elseif($log->active == 'N') <a href="{{url('/changestatus')}}/{{$log->list_id}}/{{$log->active}}" class="btn btn-sm btn-danger waves-effect waves-light"> In Active</a> @endif</td>
                        <td><a href="#" class="btn btn-sm btn-outline-danger waves-effect waves-light" onclick="deletelist({{$log->list_id}});"> Delete</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="myModalLabel">Add New List</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form  action="{{url('/addnewlist')}}" method="post">
{{csrf_field()}}  
<div class="modal-body">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Campaign ID</label>
                    <select class="form-control" name="campaign" required="">
                        {!!App\VicidialCampaign::Options('All')!!}
                    </select>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>List Name</label>
                    <input class="form-control" type="text" placeholder="Enter List Name" value="" name="listname" required="">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>List ID</label>
                    <input class="form-control" type="text" value="" placeholder="Enter List ID" name="listid" required="">
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div>
                    <label>Description</label>
                    <textarea class="form-control" type="text" value="" placeholder="Enter Description" name="list_description"></textarea>
                </div>
            </div>

        </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
<button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
</div>
</form>
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
<script type="text/javascript">
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.submitbtn').click(function() {
    var action = $(this).data("action");
    var campaign = $("#campaign").val();
    var status = $("#status").val();
    var listid = $("#listid").val();
    var submiturl = "{{url('/leadstatus')}}/"+campaign+"/"+status;
    window.location.href = submiturl;
});
function deletelist(idval) {
    var confirm_result = confirm("Are you sure you want to Delete list and its records?");
    if (confirm_result == true) {
    var submiturl = "{{url('/deletelist')}}/"+idval;
    window.location.href = submiturl;
    }
}
</script>
@stop







