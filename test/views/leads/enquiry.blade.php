@extends('layouts.master')
@section('title', 'Enquiry')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Enquiry Details</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Leads > </li>
                <li class="text-muted">Enquiry Details</li>
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
                    
                    <div class="col-lg-3">
                        <div>
                            <label>Category</label>
                            <select class="form-control" name="category" id="category">
                                <option value="">Choose</option>
                                @foreach($categories as $cat)
                                <option value="{{$cat->id_enquiry_category}}" @if($category==$cat->id_enquiry_category) selected @endif>{{$cat->category_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Phone Number</label>
                            <input class="form-control" type="text" value="{{$phone}}" name="phone" id="phone">
                        </div>
                    </div>
                    <div class="col-lg-3 mt-4">
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>

                        <button type="button" class="btn btn-info waves-effect waves-light" id="resetbtn">Reset <i class="mdi mdi-refresh ms-1"></i></button>
                    </div>
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
            <table id="datatable-buttons" class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lead Id</th>
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Batch No</th>
                        <th>Tray No</th>
                        <th>Mobile</th>
                        <th>Enquiry</th>
                        <th>Note</th>
                        <th>Followup Date</th>
                        <th>Created By</th>
                        <th>Reset Status</th>
                    </tr>
                </thead>           
                <tbody>
                    @foreach ($lists as $app)
                    @php
                        $shipment=App\Shipment::where('consignee_phone_upload',$app->mobile)->first();
                    @endphp
                    <tr>
                        <td>{{$app->id}}</td>
                        <td>{{$app->lead_id}}</td>
                        
                        <td>{{$shipment->consignee_name ?? ''}}</td>
                        <td>{{$shipment->barcode ?? ''}}</td>
                        <td>{{$shipment->reference ?? ''}}</td>
                        <td>{{$shipment->tray_no ?? ''}}</td>
                        <td>{{$app->mobile}}</td>
                        <td>{{$app->enquirycategory->category_name ?? ''}}</td>
                        <td>{{$app->description}}</td>
                        <td>{{$app->appointment_date}}</td>
                        <td>{{$app->user}}</td>
                        <td>{{$app->is_reset}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end col -->
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
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('#submitbtn').click(function() {
    var submiturl = "{{url('/enquiries')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&category="+$("#category").val()+"&phone="+$("#phone").val()+"&reset=false";
    window.location.href = submiturl;
});

$('#resetbtn').click(function() {
    var confirm_result = confirm("Are you sure you want to Reset?");
    if (confirm_result == true) {
        var submiturl = "{{url('/enquiries')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&category="+$("#category").val()+"&phone="+$("#phone").val()+"&reset=true";
        window.location.href = submiturl;
    }
});
</script>
@stop