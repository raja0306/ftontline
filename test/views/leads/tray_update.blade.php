@extends('layouts.master')
@section('title', 'Uploads')
@section('StylePage')
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Tray update</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Card Shipments > </li>
                <li class="text-muted">Tray update</li>
            </ol>
        </div>

    </div>
</div>
</div>
<div class="row">
<ul class="nav nav-pills bg-light" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#" role="tab" onclick="updateType(1);">Scan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#" role="tab" onclick="updateType(2);">Transfer</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#" role="tab" onclick="updateType(3);">Upload</a>
    </li>
</ul>
<div class="card overflow-hidden">
    <div class="card-body pt-0">
        @if (\Session::has('msg'))<div class="alert alert-success my-3" role="alert"> {!! \Session::get('msg') !!}! </div>@endif
        <div class="row">
            <div class="col-sm-12 pt-4"> 
                <form  action="{{url('/apppointments')}}" method="post">
                {{csrf_field()}} 
                <div class="row">
                    <div class="col-lg-3">
                        <div>
                            <label>Select Tray:</label>
                            <select class="form-select select2" id="tray_id" name="tray_id">
                                <option value="">Choose</option>
                                @foreach($trays as $try)
                                <option value="{{$try->id}}">{{$try->name}}</option>
                                @endforeach
                            </select>
                            <p class="text-danger select_tray mt-1 d-none">Please select tray</p>
                        </div>
                    </div>
                    <div class="col-lg-3 tray-t tray-t2">
                        <div>
                            <label>Transfer to Tray:</label>
                            <select class="form-select select2" id="tray_id2" name="tray_id2">
                                <option value="">Choose</option>
                                @foreach($trays as $try)
                                <option value="{{$try->id}}">{{$try->name}}</option>
                                @endforeach
                            </select>
                            <p class="text-danger select_tray2 mt-1 d-none">Please select tray</p>
                        </div>
                    </div>
                    <div class="col-lg-3 tray-t tray-t1">
                        <div>
                            <label>Barcode:</label>
                            <input class="form-control" type="text" id="barcodeInput" value="" placeholder="Enter barcode item" autofocus>
                            <p class="text-danger select_bar mt-1 d-none">No barcode available</p>
                        </div>
                    </div>
                    <div class="col-lg-3 tray-t tray-t3">
                        <div>
                            <label>Upload document:</label>
                            <input class="form-control" type="file" id="csvFile" name="csvFile" accept=".csv">
                            <p class="text-danger select_doc mt-1 d-none">Please choose document</p>
                        </div>
                    </div>
                    <div class="col-lg-1 text-center mt-4">
                        <input type="hidden" id="traytype" value="1">
                        <button type="button" class="btn btn-outline-info waves-effect waves-light" id="set_tray_update">Get</button>

                        
                        <a href="{{asset('/assets')}}/upload/barcode.csv" download="tray_update.csv" class="btn btn-outline-success waves-effect waves-light tray-t tray-t3"><i class="mdi mdi-download ms-1"></i></a>

                    </div>
                    <div class="col-lg-2 text-center mt-4">
                        <a href="{{url('/update_trays')}}/{{$session}}" class="btn btn-success waves-effect waves-light tray-t confirm_tray">Confirm Tray Update</a>
                    </div>
                </form>
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Shipment #</th>
                        <th>Reference</th>
                        <th>Consignee</th>
                        <th>Phone</th>
                        <th>Barcode</th>
                        <th>Tray</th>
                        <th>From Tray</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody class="listHtml">
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
@stop
@section('ScriptPage')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
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
updateType(1);
function updateType(type) {
    $('.tray-t').addClass('d-none');
    $('.tray-t'+type).removeClass('d-none');
    if($('#traytype').val()!=type){
        $('#traytype').val(type);
        clearTray();
    }
}

$('#set_tray_update').click(function() {
    var type = $('#traytype').val();
    if($("#tray_id").val()==''){ $('.select_tray').removeClass('d-none');}
    else{ $('.select_tray').addClass('d-none');
    if(type==1){
        fetchProductByBarcode($("#barcodeInput").val()); 
    }
    else if(type==2){
        if($("#tray_id2").val()==''){ $('.select_tray2').removeClass('d-none');}
        else{ $('.select_tray2').addClass('d-none'); fetchTrayShipments($("#tray_id2").val());  }
    } 
    else if(type==3){
        uploadShipments(); 
    }
    }
});

$("#barcodeInput").keypress(function(e) {
if (e.key === 'Enter') {
    e.preventDefault();
    const barcode = e.target.value.trim();
    fetchProductByBarcode(barcode);
    e.target.value = '';
}
}); 

function fetchProductByBarcode(barcode) {
if($("#barcodeInput").val()==''){ $('.select_bar').removeClass('d-none');}
else{ $('.select_bar').addClass('d-none'); 
$.ajax({ url: "{{url('/barcodeshipment')}}/"+barcode+"?tray="+$("#tray_id").val()+"&session={{$session}}",
type: "get", cache: false, dataType: 'json', 
success: function (data) { 
    $('.listHtml').html(data.inhtml);
    if(data.is_avail==false){ $('.select_bar').removeClass('d-none');}
    else{ $('.select_bar').addClass('d-none'); }
    if(data.inhtml!=''){ $('.confirm_tray').removeClass('d-none'); }
}
})
}
}

function fetchTrayShipments(totray) {
$.ajax({ url: "{{url('/transfershipments')}}/"+totray+"?tray="+$("#tray_id").val()+"&session={{$session}}",
type: "get", cache: false, dataType: 'json', 
success: function (data) { 
    $('.listHtml').html(data.inhtml);
    if(data.inhtml!=''){ $('.confirm_tray').removeClass('d-none'); }
}
})
}

function uploadShipments(argument) {
    var file = $('#csvFile')[0].files[0];
    var tray_id = $('#tray_id').val();

    if (!file) {
        alert("Please select a CSV file first.");
        return;
    }

    let formData = new FormData();
    formData.append('csvFile', file);
    formData.append('tray_id', tray_id);
    formData.append('session', {{$session}});
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "{{ route('upload.trays') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('.listHtml').html(response.inhtml);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            $('#response').html('<p style="color:red;">Upload failed!</p>');
        }
    });
}

function clearTray() {
    $.ajax({ url: "{{url('/clear_trays')}}/{{$session}}",
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $('.listHtml').html(data.inhtml);
    }
    })
}

function removeTray(idval) {
    $.ajax({ url: "{{url('/remove_tray')}}/"+idval+"?session={{$session}}",
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $('.listHtml').html(data.inhtml);
    }
    })
}

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
