@extends('layouts.master')
@section('title','Area Slots')
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
            <h4 class="mb-sm-0 font-size-16">{{App\Area::find($id)->name}} Area Slot List</h4><a href="{{route('master.areas')}}"> <- Back</a>
        </div>
    </div>
</div>
@if(session('alert'))
    <div class="alert alert-success">{!! session('alert') !!}.</div>
@endif
<div class="row">
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label><b>Days</b></label>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label><b>Slots</b></label>
                            </div>
                        </div>
                <div class="row">
                    <div class="col-sm-12 pt-3">
                        @foreach($areadayslots as $log)
                        <form  action="{{route('master.area.day.slot.store')}}" method="post">
                        {{csrf_field()}}  
                        
                        
                        @php
                            $selectedSlots = explode(',', $log->slot_ids ?? '');
                        @endphp
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>{{$log->day}}</label>
                                    <input type="hidden" name="editid" id="editid" value="{{$log->id}}">
                                </div>
                            </div> 
                            <div class="col-lg-4 mb-3">
                                <div>
                                    @foreach($slots as $slot)
                                    <div class="form-check">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input" 
                                            id="slot_{{ $slot->id }}" 
                                            name="slot_id[]" 
                                            value="{{ $slot->id }}"
                                            {{ in_array($slot->id, $selectedSlots) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="slot_{{ $slot->id }}">
                                            {{ $slot->name }}
                                        </label>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <center>
                                <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">Update</button>
                                </center>
                            </div>
                        </div>
                        
                            
                        </form>
                        @endforeach 
                    </div>
                </div>
                
            </div>
            
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

$('.sel').select2();
$(".edit-list").click(function() {
    var idval = $(this).data('id');
    $('#editid').val(idval);
    $('#name').val($(this).data('name'));
    $('#name_ar').val($(this).data('name_ar'));
    $('#governorate_id').val($(this).data('governorate_id'));
    $('#createArea').collapse('show');
});

</script>
@stop