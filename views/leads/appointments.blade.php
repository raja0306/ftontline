@extends('layouts.master')
@section('title', 'Uploads')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<style> .dataTables_info, .dataTables_paginate {display: block;} </style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Upload Logs</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Leads > </li>
                <li class="text-muted">Upload Logs</li>
            </ol>
        </div>

    </div>
</div>
</div>
<div class="row">
<div class="card overflow-hidden">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/apppointments')}}" method="post">
                    {{csrf_field()}} 
                <div class="row">
                      
                    <div class="col-lg-3">
                        <div>
                            <label>From Date</label>
                            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>To Date</label>
                            <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                        <a href="{{url('/apppointments/labels')}}" class="btn btn-outline-danger waves-effect waves-light">Print Label</a>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th><input type="checkbox" id="select-all"></th> -->
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Lead ID</th>
                        <th>Barcode</th>
                        <th>Appointment Date</th>
                        <th>Slot</th>
                        <th>Area</th>
                        <th>Address Type</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Created At</th>
                        <th>Agent</th>
                        <th>Edit</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                    <tr>
<!--                         <td><input type="checkbox" class="checkbox-item" value="{{ $appointment->id }}"></td> -->
                        <td>{{ $appointment->customer->name ?? ''}}</td>
                        <td>{{ $appointment->phone_number }}</td>
                        <td>{{ $appointment->lead_id }}</td>
                        <td>{{ $appointment->shipments->pluck('barcode')->implode(',') }}</td>
                        <td>{{ date('d M, Y',strtotime($appointment->appointment_date)) }}</td>
                        <td>{{ $appointment->slot->name ?? '-' }}</td>
                        <td>{{ $appointment->area->name ?? '' }}</td>
                        <td>@if($appointment->address_type==2) Branch @else Customer  @endif</td>
                        <td>{{ $appointment->branch->name ?? '-'}}</td>
                        
                        <td>{{ $appointment->appointmentstatus->name ?? '-'}}</td>

                        <td>{{ $appointment->notes }}</td>
                        <td>{{ date('d M, Y H:i A',strtotime($appointment->created_at)) }}</td>
                        <td>{{ $appointment->agent}}</td>
                        <td>
                            <a href="{{url('/book/customer')}}/{{$appointment->phone_number}}/{{$appointment->agent ?? '7001'}}/1000/OUTIN/OUTIN/{{$appointment->lead_id}}/{{$appointment->id}}" class="btn btn-sm btn-outline-danger edit-list">Edit</a>
                        </td>
                        <td>
                            <a href="{{url('/book/apppointment/view')}}/{{$appointment->id}}" class="btn btn-sm btn-outline-warning edit-list">View</a>
                        </td>
                    </tr>
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
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({pageLength: 10,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$(document).ready(function () {
    $('#select-all').on('change', function () {
        $('.checkbox-item').prop('checked', $(this).prop('checked'));
        updateSelectedIds();
    });
    $('.checkbox-item').on('change', function () {
        if (!$(this).prop('checked')) {
            $('#select-all').prop('checked', false);
        }
        if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
            $('#select-all').prop('checked', true);
        }
        updateSelectedIds();
    });
    function updateSelectedIds() {
        let selectedIds = [];
        $('.checkbox-item:checked').each(function () {
            selectedIds.push($(this).val());
        });
        $('#selected-ids').val(selectedIds.join(','));
        // if (selectedIds.length > 0) { $('.StatusDiv').removeClass('d-none'); }
        // else { $('.StatusDiv').addClass('d-none'); }
    }
});
var extravals = '';
$('#resetbtn').click(function() {
    var confirm_result = confirm("Are you sure you want to Reset?");
    if (confirm_result == true) {
    var extravals = "&reset=True";
        submiturl(extravals);
    }
});
$('#clearbtn').click(function() {
    var confirm_result = confirm("Are you sure you want to Clear?");
    if (confirm_result == true) {
    var extravals = "&clear=True";
        submiturl(extravals);
    }
});
$('#submitbtn').click(function() {
    submiturl(extravals);
});
$('#exportbtn').click(function() {
    submiturl('export');
});
function submiturl(extra){
    var statuss = $("input[name='statuss']:checked").map(function() {
        return this.value;
    }).get().join(',');
    if(extra=='export'){
    var submiturl = "{{url('/listexport')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&datetype="+$("#datetype").val()+"&batchno="+$("#batchno").val()+"&listid="+$("#listid").val()+"&status="+statuss+"&phone="+$("#phone").val();
    }
    else{
    var submiturl = "{{url('/leads')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&datetype="+$("#datetype").val()+"&batchno="+$("#batchno").val()+"&listid="+$("#listid").val()+"&status="+statuss+"&phone="+$("#phone").val()+extra;
    }
    window.location.href = submiturl;
}
</script>
@stop



