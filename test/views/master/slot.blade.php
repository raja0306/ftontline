@extends('layouts.master')
@section('title','Slot List')
@section('StylePage')
    <link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('pageBody')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-16">Slot List</h4>
            <div class="page-title-right">
                @if(App\Menurole::getMenuRole(Auth::user()->role_id,30,'create'))
                    <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#createArea" aria-expanded="false" aria-controls="createArea" onclick="$('#editid').val(0);">Create Slot</a>
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
                        <form  action="{{route('master.slot.store')}}" method="post">
                        {{csrf_field()}}  
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Slot Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" id="name" required="">
                                    <input type="hidden" name="editid" id="editid" value="0">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Slot Start Time <span class="text-danger">*</span></label>
                                    <input class="form-control" type="time" name="start_time" id="start_time" required="">
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Slot End Time <span class="text-danger">*</span></label>
                                    <input class="form-control" type="time" name="end_time" id="end_time" required="">
                                </div>
                            </div>   
                            <div class="col-lg-3 mb-3">
                                <div>
                                    <label>Number of Slots <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="quantity" id="quantity" required="">
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
            <th>Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Number of Slots </th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($slots as $list)
        <tr>
            <td>{{$list->id}}</td>
            <td>{{$list->name}}</td>
            <td>{{$list->start_time}}</td>
            <td>{{$list->end_time}}</td>
            <td>{{$list->quantity}}</td>
            <td>
                <div class="d-flex gap-3">
                    @if(App\Menurole::getMenuRole(Auth::user()->role_id,30,'edit'))
                        <a href="#" class="btn btn-sm btn-outline-secondary edit-list" data-id="{{$list->id}}" data-name="{{$list->name}}" data-start_time="{{$list->start_time}}"  data-end_time="{{$list->end_time}}" data-quantity="{{$list->quantity}}">Edit</a>
                    @endif
                    @if(App\Menurole::getMenuRole(Auth::user()->role_id,30,'delete'))
                        <a href="{{route('master.slot.delete',[$list->id])}}" class="btn btn-sm btn-outline-danger">Delete</a>
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
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$(".edit-list").click(function() {
    var idval = $(this).data('id');
    $('#editid').val(idval);
    $('#name').val($(this).data('name'));
    $('#start_time').val($(this).data('start_time'));
    $('#end_time').val($(this).data('end_time'));
    $('#quantity').val($(this).data('quantity'));
    $('#createArea').collapse('show');
});

</script>
@stop