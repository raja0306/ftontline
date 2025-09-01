@extends('layouts.master')
@section('title', 'Pickup Request')
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
        <h4 class="mb-sm-0 font-size-18">Pickup Request</h4>
        <div class="page-title-right">
                <a class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" href="#createArea" aria-expanded="false" aria-controls="createArea" onclick="$('#editid').val(0);">Create Pickup Request</a>
        </div>

    </div>
</div>
</div>

<div id="variantModal" class="modal fade variant-item" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="variant-title">Assign Driver</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body variant-body">
                <form  action="{{url('/pickuprequests/assign')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                    <div class="col-lg-12 mb-3">
                         <label>Driver <span class="text-danger">*</span></label>
                        <select class="form-control" id="driver_id" name="driver_id" required="">
                            <option value="">Select</option>
                            @foreach($drivers as $driver)
                                <option value="{{$driver->id}}">{{$driver->name}} - {{$driver->vechicle->name ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="pickuprequest_id" id="pickuprequest_id">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 collapse" id="createArea">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 pt-3">
                        <form  action="{{route('ecom.pickuprequest.store')}}" method="post">
                        {{csrf_field()}}  
                        <div class="row">
                            
                            <div class="col-lg-4 mb-3 @if(Auth::user()->role_id==7) d-none @endif" >
                                <div>
                                    <label>Vendor <span class="text-danger">*</span></label>
                                    <select class="form-control" id="vendor_id" name="vendor_id" required="">
                                        <option value="">Select</option>
                                        @foreach($vendors as $vendor)
                                            <option  @if($vendor->id == Auth::user()->id ) selected="" @endif value="{{$vendor->id}}">{{$vendor->name}} - {{$driver->vechicle->name ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>Number of Packages <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="no_of_packages" id="no_of_packages" required="">
                                </div>
                            </div>   
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>Pickup Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="pickup_date" id="pickup_date" required="">
                                </div>
                            </div> 
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>Pickup Time <span class="text-danger">*</span></label>
                                    <input class="form-control" type="time" name="pickup_time" id="pickup_time" required="">
                                </div>
                            </div> 
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>Notes </label>
                                    <textarea class="form-control" id="notes" name="notes"></textarea>
                                </div>
                            </div> 
                            @if(Auth::user()->role_id!=7)
                            <div class="col-lg-4 mb-3">
                                <div>
                                    <label>Driver </label>
                                    <select class="form-control" id="driver_id" name="driver_id">
                                        <option value="0">Select</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{$driver->id}}">{{$driver->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
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

<div class="row">
<div class="card overflow-hidden">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/pickuprequest')}}" method="post">
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
                        <th>Vendor</th>
                        <th>No.of.Packages</th>
                        <th>Pickup Date</th>
                        <th>Pickup Time</th>
                        <th>Driver</th>
                        <th>Note</th>
                        <th>Created At</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pickuprequests as $log)
                    <tr>
                        <td>{{ $log->vendor->name ?? ''}}</td>
                        <td>{{ $log->no_of_packages }}</td>
                        <td>{{ date('d M, Y',strtotime($log->pickup_date)) }}</td>
                        <td>{{ date('h:i:s A',strtotime($log->pickup_time)) }}</td>
                        <td>@if($log->driver) 
                                {{ $log->driver->name ?? ''}}
                            @else
                                @if(Auth::user()->role_id!=7)
                                    <button type="button" class="mb-2 btn-sm btn-primary waves-effect waves-light btn-sm assign" data-id="{{$log->id}}"> <i class="bx bx-plus ms-1"></i>Assign Driver</button>
                                @endif   
                            @endif
                        </td>
                        <td>{{ $log->notes }}</td>
                        <td>{{ date('d M, Y H:i A',strtotime($log->created_at)) }}</td>
                        <td>{{ $log->createduser->name ?? '-' }}</td>
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

$('.assign').click(function() {
    var id = $(this).data("id"); 
    $('#pickuprequest_id').val(id);
    $("#variantModal").modal('show');

});
    
</script>
@stop



