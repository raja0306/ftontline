@extends('layouts.master')
@section('title', 'Upload Shipment')
@section('StylePage')
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Shipment Upload</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Card Shipments > </li>
                <li class="text-muted">Shipment Upload</li>
            </ol>
        </div>
    </div>
</div>
</div>
<div class="row">
<div class="card overflow-hidden">
    <div class="card-body pt-0">
        @if(session('alert'))
            <div class="alert alert-success mt-3">{!! session('alert') !!}.</div>
        @endif
        <form  action="{{url('/uploadshipment')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="row">
            <div class="col-sm-12 pt-3">
                <div class="row">
                    @if(Auth::user()->role_id!="4" )<div class="col-lg-3">@endif
                        
                            <label class="@if(Auth::user()->role_id=="4" ) d-none @endif">Bank</label>
                            <select class="form-control @if(Auth::user()->role_id=="4" ) d-none @endif" id="bank_id" name="bank_id" required="">
                                <option>Select Bank</option>
                                @foreach($banks as $bank)
                                    <option @if(Auth::user()->id==$bank->id) selected="" @endif  value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                       
                    @if(Auth::user()->role_id!="4" )</div>@endif
                    <div class="col-lg-3">
                        <div>
                            <label>Upload File </label>
                            <input class="form-control" type="file" value=""  accept=".csv" name="import_file" id="import_file">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light mt-3">Upload <i class="mdi mdi-upload ms-1"></i></button>
                        <!-- <a href="{{url('/list/file/download')}}" class="btn btn-info waves-effect waves-light mt-3">Download Format<i class="mdi mdi-download ms-1"></i></a> -->

                        <a href="{{asset('/assets')}}/upload/shipments-upload-new.csv" download="shipments-upload-new.csv" class="btn btn-outline-success waves-effect waves-light mt-3"><i class="mdi mdi-download ms-1"></i></a>

                        @if(App\Menurole::getMenuRole(Auth::user()->role_id,25))
                            <a href="{{url('/shipment/bulkupload')}}" class="btn btn-warning waves-effect waves-light mt-3">Shipment List<i class="bx bx-receipt ms-1"></i></a>
                        @endif

                        @if(App\Menurole::getMenuRole(Auth::user()->role_id,39))
                            <a href="{{url('/shipment/blocked')}}" class="btn btn-danger waves-effect waves-light mt-3">Blocked Shipments<i class="bx bx-block ms-1"></i></a>
                        @endif
                        <a href="{{url('/upload/shipment/print')}}" class="btn btn-outline-danger waves-effect waves-light mt-3">Print</a>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</div>

<div class="row">
<div class="card overflow-hidden">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/upload/shipment')}}" method="post">
                    {{csrf_field()}} 
                <div class="row">
                      
                    <div class="col-lg-2">
                        <div>
                            <label>From Date</label>
                            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>To Date</label>
                            <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                        </div>
                    </div>
                    
                    <div class="col-lg-2 d-none">
                        <div>
                            <label>List ID</label>
                            <select class="form-control" name="listid" id="listid">
                                {!!App\VicidialLists::Options($listid)!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div>
                            <label>Search Type</label>
                            <select class="form-control" name="searchtype" id="searchtype">
                                <option value="">Select</option>
                                <option value="consignee_phone_upload">Mobile</option>
                                <option value="alternate_phone_upload">Alt Mobile</option>
                                <option value="barcode">Barcode</option>
                                <option value="customer_civil_Id">Customer Civil ID</option>
                                <option value="receiver_civil_id">Receiver Civil ID</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label>Search</label>
                        <input type="text" name="searchval" class="form-control">
                    </div>
                    <div class="col-lg-2 mt-4">
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
                        <th>S.No</th>
                        <th>Upload ID</th>
                        <th>Bank</th>
                        <th>Upload Count</th>
                        <th>Uploaded Count</th>
                        <th>Wrong Mobile No Count</th>
                        <th>Appointment Count</th>
                        <th>Created at</th>
                        <th>Created by</th>
                        <th>Shipments</th>
                    </tr>
                </thead>           
                <tbody>
                    @php $x=1; @endphp
                    @foreach ($lists as $log)
                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$log->id}}</td>
                        <td>{{$log->bank->name ?? 'N/A'}}</td>
                        <td>{{$log->upload_count}}</td>
                        <td>{{$log->uploaded_count}}</td>
                        <td>{{$log->mobile_count}}</td>
                        <td>{{$log->appointments->count()}}</td>
                        <td>{{$log->created_at}}</td>
                        <td>{{$log->user->name ?? ''}}</td>
                        <td>
                            <a href="{{url('/upload/shipment/view')}}/{{$log->id}}" class="btn btn-sm btn-outline-warning edit-list">View</a>
                            <a href="{{url('/upload/shipment/print')}}/{{$log->id}}" class="btn btn-sm btn-outline-dark">Print</a>
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
<script type="text/javascript">
function getcampaignlists(idval) {
    var exportlisturl = "{{url('/getcampaignlists')}}/"+idval;
    $.getJSON(exportlisturl, function(data) {
        $("#listid").html(data.dropdownhtml);
    });
}
</script>
@stop






