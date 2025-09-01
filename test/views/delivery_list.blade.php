@extends('layouts.master')
@section('title', 'Delivery List')
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
        <h4 class="mb-sm-0 font-size-18">Delivery List</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Shipments > </li>
                <li class="text-muted">Delivery List</li>
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
                <form  action="{{url('/delivery/list')}}" method="post">
                    {{csrf_field()}} 
                <div class="row">
                      
                    <div class="col-lg-3">
                        <div>
                            <label>Appointment Date</label>
                            <input class="form-control" type="date" value="{{$appointment_date}}" name="appointment_date" id="appointment_date">
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
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
                        <th>Driver</th>
                        <th>Delivery Sheet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drivers as $log)
                    <tr>
                        <td>{{ $log->driver->name ?? ''}}</td>
                        <td>
                            <a href="{{url('/delivery/sheet')}}/{{$log->driver_id}}/{{$appointment_date}}" class="btn btn-sm btn-outline-danger edit-list">Print</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
           </div>
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



