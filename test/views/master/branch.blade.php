@extends('layouts.master')
@section('title','Branch List')
@section('StylePage')
    <link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('pageBody')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-16">Branch List</h4>
            <div class="page-title-right">
                @if(App\Menurole::getMenuRole(Auth::user()->role_id,24,'create'))
                    <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#createArea" aria-expanded="false" aria-controls="createArea" onclick="$('#editid').val(0);">Create Branch</a>
                @endif
            </div>
        </div>
    </div>
</div>
@if(session('alert'))
    <div class="alert alert-success">{!! session('alert') !!}.</div>
@endif
<div class="row">
    <div class="col-md-12 collapse" id="createArea">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 pt-3">
                        <form  action="{{route('master.branch.store')}}" method="post">
                        {{csrf_field()}}  
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Bank <span class="text-danger">*</span></label>
                                    <select class="form-control" name="bank_id" id="bank_id" required="">
                                        <option value="">Select</option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" id="name" required="">
                                    <input type="hidden" name="editid" id="editid" value="0">
                                </div>
                            </div> 
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Governate <span class="text-danger">*</span></label>
                                    <input type="text" name="governate" class="form-control" id="governate" required="">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Working Days <span class="text-danger">*</span></label><br>
                                    <select style="width: 100%" multiple="" name="workingdays[]" id="workingdays" class="form-control select2" required="">
                                        <option value="">Select Days</option>
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wedensday">Wedensday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Branch Morning Start Time <span class="text-danger">*</span></label>
                                    <input type="time" name="morning_branch_start_time" class="form-control" id="morning_branch_start_time" required="">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Branch Morning End Time <span class="text-danger">*</span></label>
                                    <input type="time" name="morning_branch_end_time" class="form-control" id="morning_branch_end_time" required="">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Branch Evening Start Time</label>
                                    <input type="time" name="evening_branch_start_time" class="form-control" id="evening_branch_start_time">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Branch Evening End Time</label>
                                    <input type="time" name="evening_branch_end_time" class="form-control" id="evening_branch_end_time">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Note</label>
                                    <textarea class="form-control"  name="note" id="note">
                                        
                                    </textarea>
                                </div>
                            </div> 
                            <div class="col-lg-3 text-center">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">Save details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
<div class="card">
    <div class="card-body">
<div class="row">                                
    <div class="table-responsive mt-4">
        <table id="datatable-buttons" class="table align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>Bank</th>
            <th>Name</th>
            <th>Governate</th>
            <th>Branch Morning Time</th>
            <th>Branch Evening Time</th>
            <th>Working Days</th>
            <th>Note</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($branchs as $list)
        <tr>
            <td>{{$list->id}}</td>
            <td>{{$list->user->name ?? ''}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->governate}}</td>
            <td>{{date('h:i A',strtotime($list->morning_branch_start_time))}} - {{date('h:i A',strtotime($list->morning_branch_end_time))}}</td>
            <td>@if(!empty($list->evening_branch_start_time)){{date('h:i A',strtotime($list->evening_branch_start_time)) ?? '-'}}@endif - @if(!empty($list->evening_branch_end_time)){{date('h:i A',strtotime($list->evening_branch_end_time)) ?? '-'}}@endif</td>
            <th>{{$list->workingdays}}</th>
            <td>{{$list->note}}</td>
            <td>
                <div class="d-flex gap-3">
                    @if(App\Menurole::getMenuRole(Auth::user()->role_id,24,'edit'))
                        <a href="#" class="btn btn-sm btn-outline-secondary edit-list" data-id="{{$list->id}}" data-name="{{$list->name}}" data-bank_id="{{$list->bank_id}}" data-governate="{{$list->governate}}"
                        data-user_id="{{$list->user_id}}" data-morning_branch_start_time="{{$list->morning_branch_start_time}}" data-morning_branch_end_time="{{$list->morning_branch_end_time}}" data-evening_branch_start_time="{{$list->evening_branch_start_time}}" data-governate="{{$list->governate}}" data-evening_branch_end_time="{{$list->evening_branch_end_time}}" data-workingdays="{{$list->workingdays}}">Edit</a>
                    @endif
                    @if(App\Menurole::getMenuRole(Auth::user()->role_id,24,'delete'))
                    @if($list->delete_status==0)
                        <a href="{{route('master.branch.delete',[$list->id,1])}}" class="btn btn-sm btn-outline-success">De Activate</a>
                    @else
                    	<a href="{{route('master.branch.delete',[$list->id,0])}}" class="btn btn-sm btn-outline-danger">Activate</a>
                    @endif
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
</div>
</div>
</div>
@endsection
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
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$(".edit-list").click(function() {
    var idval = $(this).data('id');
    $('#editid').val(idval);
    $('#name').val($(this).data('name'));
    $('#governate').val($(this).data('governate'));
    $('#morning_branch_start_time').val($(this).data('morning_branch_start_time'));
    $('#morning_branch_end_time').val($(this).data('morning_branch_end_time'));
    $('#evening_branch_start_time').val($(this).data('evening_branch_start_time'));
    $('#evening_branch_end_time').val($(this).data('evening_branch_end_time'));
    daysString=$(this).data('workingdays');
    let selectedDays = daysString.split(',');
    $('#workingdays').val(selectedDays).trigger('change');
    $('#note').val($(this).data('note'));
    $('#bank_id').val($(this).data('user_id'));
    $('#createArea').collapse('show');
});

</script>
@stop