@extends('layouts.master')
@section('title', 'Print')
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
        <h4 class="mb-sm-0 font-size-18">Print Appointments</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Leads > </li>
                <li class="text-muted">Print Logs</li>
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
                <form  action="{{url('/apppointments/labels')}}" method="post">
                    {{csrf_field()}} 
                <div class="row">
                    <div class="col-lg-3">
                        <div>
                            <label>Appointment Date</label>
                            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Is Printed ?</label>
                            <select class="form-select" name="is_print">
                                <option value="All">All</option>
                                <option value="0" @if($is_print=='0') selected @endif>Not printed</option>
                                <option value="1" @if($is_print=='1') selected @endif>Printed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                        <a href="#" class="btn btn-outline-danger waves-effect waves-light d-none StatusDiv" onclick="$('#labelgenerate').submit();">Print</a>
                        <span class="btn btn-dark waves-effect waves-light ids_count d-none StatusDiv">0</span>
                    </div>
                    <div class="col-lg-4 mt-4 text-end">
                        <div class="d-flex">
                            <div class="w-100">
                                <input type="text" class="form-control" placeholder="Search for label list" id="searchbtn">
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<form method="post" action="{{url('generate/apppointments/labels')}}" id="labelgenerate">
    @csrf
    <input type="hidden" name="selected_ids" id="selected-ids">
    <input type="hidden" name="fromdate" value="{{$fromdate}}">
</form>
<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered labelsdiv">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>App #</th>
                        <th>Barcode</th>
                        <th>Name</th>
                        <th>Civil ID</th>
                        <th>Phone</th>
                        <th>Appointment Date</th>
                        <th>Tray #</th>
                        <th>Pickup #</th>
                        <th>Batch #</th>
                        <th>Is Printed</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sn=1; ?>
                    @foreach ($shipments as $log)
                    <tr class="label-lists">
                        <td><input type="checkbox" class="checkbox-item" value="{{ $log->id }}"></td>
                        <td>{{ $sn }}</td>
                        <td>{{ $log->appointment_id }}</td>
                        <td>{{ $log->barcode }}</td>
                        <td>{{ $log->consignee_name }}</td>
                        <td>{{ $log->customer_civil_Id }}</td>
                        <td>{{ $log->consignee_phone }}</td>
                        <td>{{ $log->appointment_date }} {{ $log->appointment->slot->name ?? '' }}</td>
                        <td>{{ $log->tray_no }}</td>
                        <td>{{ $log->pickup_date }}</td>
                        <td>{{ $log->reference }}</td>
                        <td>@if($log->is_print==0)<span class="text-danger">No</span>@else<span class="text-success">Yes</span>@endif</td>
                        <td>
                            <a href="{{url('/book/apppointment/view')}}/{{$log->appointment_id}}" class="btn btn-sm btn-outline-warning edit-list">View</a>
                        </td>
                    </tr>
                    <?php $sn++; ?>
                    @endforeach
                </tbody>
            </table>
           </div>
        </div>
    </div>
</div>
@stop
@section('ScriptPage')
<script>
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
        if (selectedIds.length > 0) { $('.StatusDiv').removeClass('d-none'); 
            $('.ids_count').html(selectedIds.length); }
        else { $('.StatusDiv').addClass('d-none'); }
    }

    $("#searchbtn").keyup(function() {
      var filter = $(this).val(),count = 0;
      $('.labelsdiv .label-lists').each(function() {
        if ($(this).text().search(new RegExp(filter, "i")) < 0) { $(this).hide(); } else { $(this).show();  count++; }
      });

    });
});
</script>
@stop



