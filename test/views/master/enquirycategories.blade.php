@extends('layouts.master')
@section('title','Enquiry Category')
@section('StylePage')
    <link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('pageBody')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-16">Enquiry Category List</h4>
            <div class="page-title-right">
                @if(App\Menurole::getMenuRole(Auth::user()->role_id,37,'create'))
                    <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#createArea" aria-expanded="false" aria-controls="createArea" onclick="$('#editid').val(0);">Create Enquiry Category</a>
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
                        <form  action="{{route('master.enquirycategory.store')}}" method="post">
                        {{csrf_field()}}  
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" id="name" required="">
                                    <input type="hidden" name="editid" id="editid" value="0">
                                </div>
                            </div> 
                            <div class="col-lg-4 mb-3">
                                <label>Is Block Shipment? <span class="text-danger">*</span></label>
                                <select class="form-control" id="is_block_shipment" name="is_block_shipment">
                                    <option>Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div> 
                            <div class="col-lg-4 mb-3">
                                <label>Is Notified? <span class="text-danger">*</span></label>
                                <select class="form-control" id="is_notifed" name="is_notifed">
                                    <option>Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>  
                            <div class="col-lg-4 text-center">
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
            <th>Is Block Shipment?</th>
            <th>Is Notified?</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($enquirycategories as $list)
        <tr>
            <td>{{$list->id_enquiry_category}}</td>
            <td>{{$list->category_name}}</td>
            <td>@if($list->is_block_shipment=='0') No @else Yes @endif</td>
            <td>@if($list->is_notifed=='0') No @else Yes @endif</td>
            <td>
                <div class="d-flex gap-3">
                    @if(App\Menurole::getMenuRole(Auth::user()->role_id,33,'edit'))
                        <a href="#" class="btn btn-sm btn-outline-secondary edit-list" data-id="{{$list->id_enquiry_category}}" data-name="{{$list->category_name}}" data-is_block_shipment="{{$list->is_block_shipment}}" data-is_notifed="{{$list->is_notifed}}">Edit</a>
                    @endif
                    @if(App\Menurole::getMenuRole(Auth::user()->role_id,33,'delete'))
                        <a href="{{route('master.enquirycategory.delete',[$list->id_enquiry_category])}}" class="btn btn-sm btn-outline-danger">Delete</a>
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
    $('#is_block_shipment').val($(this).data('is_block_shipment'));
    $('#is_notifed').val($(this).data('is_notifed'));
    $('#createArea').collapse('show');
});

</script>
@stop