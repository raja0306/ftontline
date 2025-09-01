@extends('layouts.master')
@section('title', 'Upload CSV')
@section('StylePage')
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Leads</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Leads > </li>
                <li class="text-muted">Upload Leads</li>
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
        <form  action="{{url('/autoupload/upload')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="row">
            <div class="col-sm-12 pt-3">
                <div class="row">
                    <div class="col-lg-3">
                        <div>
                            <label>Campaign</label>
                            <select class="form-control" name="campaign" id="campaign" onclick="getcampaignlists(this.value);">
                                <option value="">Choose...</option>
                                @foreach($campaigns as $camp)
                                <option value="{{$camp->campaign_id}}">{{$camp->campaign_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>List ID</label>
                            <select class="form-control" name="listid" id="listid" required>
                                <option value="">Select</option>
                                @foreach($lists as $list)
                                <option value="{{$list->list_id}}">{{$list->list_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Batch No.</label>
                            <input class="form-control" type="text" value="" name="batchno" id="batchno">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div>
                            <label>Upload File </label>
                            <input class="form-control" type="file" value=""  accept=".csv" name="import_file" id="import_file">
                        </div>
                    </div>
                    <div class="col-lg-3 mt-3">
                        <div>
                            <label>File format</label>
                            <select class="form-control" name="fileformat" id="fileformat" required>
                                
                                <option value="shipment">Shipment Format</option>
                                <option value="bulkupload">Bulk Upload Format</option>
				                <option value="reschedule">Reschedule</option>
                                <!-- <option value="sss">SSS Format</option>
                                <option value="pdss">PDSS Format</option>
                                <option value="cec">CEC Format</option> -->
                            </select>
                         </div>
                    </div>
                    <div class="col-lg-6 mt-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light mt-3">Upload <i class="mdi mdi-upload ms-1"></i></button>
                        <a href="{{url('/list/file/download')}}" class="btn btn-info waves-effect waves-light mt-3">Download Format<i class="mdi mdi-download ms-1"></i></a>
                        <a href="{{url('/shipment/bulkupload')}}" class="btn btn-success waves-effect waves-light mt-3">Bulk Uplod<i class="mdi mdi-file ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        </form>
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






