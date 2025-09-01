@extends('layouts.master')
@section('title', 'Enquiry Details')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .form-check-label{ margin-right: 20px; margin-left: 5px; }
</style>
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Enquiry Leads</h4>
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
                            <label>List ID</label>
                            <select class="form-control" name="listid" id="listid">
                                {!!App\VicidialLists::Options($listid)!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Main Enquiry</label>
                            <select class="form-control" name="category" id="category">
                                <option value="All">Select</option>
                                @foreach($enquirylists as $categories)
                                <option value="{{$categories->id_enquiry_category}}" @if($categories->id_enquiry_category == $category) selected="" @endif>{{$categories->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <button type="button" class="btn btn-primary waves-effect waves-light mt-3 submitbtn">Search <i class="mdi mdi-arrow-right ms-1"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body table-responsive">
            <table id="datatable-buttons" class="table table-bordered">
                <thead>
                <tr>
                    <th>Enq #</th>
                    <th>Date Added</th>
                    <th>List</th>
                    <th>Mobile #</th>
                    <th>MainCategory</th>
                    <th>SubCategory</th>
                    <th>SubCategory2</th>
                    <th>Agent</th>
                    <th>Description</th>

                    <th>Vin #</th> 
                    <th>Lead ID</th> 
                    <th>Phone Number</th>
                    <th>ListName</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Entry Date</th>
                    <th>Modify Date</th>
                </tr>
                </thead>           
                <tbody>
                @foreach ($enquiries as $app)
                <tr>
                    <td>{{$app->id_opp}}</td>
                    <td>{{$app->date_add}}</td>
                    <td>{{App\VicidialLists::FindName($app->id_list)}}</td>
                    <td>{{$app->mobile_number}}</td>
                    <td>{{App\Enquiry::FindName($app->enquiry_category)}}</td>
                    <td>{{App\Enquiry::FindName($app->enquiry_subcategory)}}</td>
                    <td>{{App\Enquiry::FindName($app->enquiry_subcategory2)}}</td>
                    <td>{{$app->id_agent}}</td>
                    <td>{{$app->description}}</td>
                    <td>{{$app->chassis}}</td>
                    {!!App\Enquiry::CECDetails($app->id_process_lead)!!}
                </tr>
                @endforeach
                </tbody>
            </table>
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
<script type="text/javascript">
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.submitbtn').click(function() {
    var fdate = $("#fromdate").val();
    var todate = $("#todate").val();
    var listid = $("#listid").val();
    var category = $("#category").val();
    var submiturl = "{{url('/cec/enquiries')}}?fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val()+"&category="+$("#category").val()+"&listid="+$("#listid").val();
    window.location.href = submiturl;
});
</script>
@stop






