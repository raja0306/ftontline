@extends('layouts.master')
@section('title', 'Edit Shipment')
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
        <h4 class="mb-sm-0 font-size-18">Edit Shipment</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Shipments > </li>
                <li class="text-muted">Edit Shipment</li>
            </ol>
        </div>

    </div>
</div>
</div>

<div class="row">
	<div class="col-12">
    	<div class="card" style="overflow-x: auto;">
        	<div class="card-body">
        		<h5 class="mb-3">Edit {{$shipment->barcode}}</h5>
        		<form  action="{{route('shipmentedit')}}" method="post">
                        {{csrf_field()}} 
        		<div class="row">
        			<div class="col-md-3 mb-3"><label>Consignee Name <span class="text-danger">*</span></label>
                		<input type="text" name="consignee_name" id="consignee_name" class="form-control" value="{{ $shipment->consignee_name }}" required="">
                		<input type="hidden" name="edit_id" value="{{$shipment->id}}">
               		</div>

               		<div class="col-md-3 mb-3"><label>Customer Civil ID <span class="text-danger">*</span></label>
                		<input type="text" name="customer_civil_Id" id="customer_civil_Id" class="form-control" value="{{ $shipment->customer_civil_Id }}" required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Consignee Phone <span class="text-danger">*</span></label>
                		<input type="text" name="consignee_phone" id="consignee_phone" class="form-control" value="{{ $shipment->consignee_phone }}" required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Alternate Phone <span class="text-danger">*</span></label>
                		<input type="text" name="alternate_phone" id="alternate_phone" class="form-control" value="{{ $shipment->alternate_phone }}"required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Card Type <span class="text-danger">*</span></label>
                		<input type="text" name="description" id="description" class="form-control" value="{{ $shipment->description }}"required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Commodity Name <span class="text-danger">*</span></label>
                		<input type="text" name="commodity_name" id="commodity_name" class="form-control" value="{{ $shipment->commodity_name }}"required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Guardian Name</label>
                		<input type="text" name="guardian_name" id="guardian_name" class="form-control" value="{{ $shipment->guardian_name }}">
               		</div>

               		<div class="col-md-3 mb-3"><label>Receiver Civil ID</label>
                		<input type="text" name="receiver_civil_id" id="receiver_civil_id" class="form-control" value="{{ $shipment->receiver_civil_id }}">
               		</div>

               		<div class="col-md-3 mb-3"><label>Branch Name </label>
                		<input type="text" name="branch_name" id="branch_name" class="form-control" value="{{ $shipment->branch_name }}" >
               		</div>

               		<div class="col-md-3 mb-3"><label>Pickup Date <span class="text-danger">*</span></label>
                		<input type="date" name="pickup_date" id="pickup_date" class="form-control" value="{{ $shipment->pickup_date }}"required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Reference <span class="text-danger">*</span></label>
                		<input type="text" name="reference" id="reference" class="form-control" value="{{ $shipment->reference }}"required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Tray No <span class="text-danger">*</span></label>
                		<input type="text" name="tray_no" id="tray_no" class="form-control" value="{{ $shipment->tray_no }}"required="">
               		</div>

               		<div class="col-md-3 mb-3"><label>Manifest Number <span class="text-danger">*</span></label>
                		<input type="text" name="manifest_number" id="manifest_number" class="form-control" value="{{ $shipment->manifest_number }}"required="">
               		</div>
               		<div class="col-lg-2 text-center"><br>
                        <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">Save details</button>
                    </div>
                        
        		</div>
        	</form>
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

</script>
@stop



