@extends('layouts.master')
@section('title', $title)
@section('StylePage')
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@stop
@section('pageBody')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">{{$title}}</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="text-muted">Card Shipments > </li>
                <li class="text-muted">{{$title}}</li>
            </ol>
        </div>

    </div>
</div>
</div>
<div class="row">

<div class="card">
    <ul class="nav nav-pills bg-light" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#" role="tab" onclick="updateType(1);">Scan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#" role="tab" onclick="updateType(3);">Upload</a>
        </li>
    </ul>
    <div class="card-body pt-0">
        @if (\Session::has('msg'))<div class="alert alert-success my-3" role="alert"> {!! \Session::get('msg') !!}! </div>@endif
        <div class="row">
            <div class="col-sm-12 pt-4"> 
                {{csrf_field()}} 
                <div class="row">
                    @if($statustype!=1)
                    <div class="col-lg-3">
                        <div>
                            <label>Driver:</label>
                            <select class="form-control" id="driver_id" name="driver_id">
                                <option value="0">Select</option>
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-3 tray-t tray-t1">
                        <div>
                            <label>Barcode:</label>
                            <input class="form-control" type="text" id="barcodeInput" value="" placeholder="Enter barcode item" autofocus>
                            <p class="text-danger select_bar mt-1 d-none">No barcode available</p>
                            <p class="text-danger driver_bar mt-1 d-none">for this selected driver</p>
                        </div>
                    </div>
                    <div class="col-lg-3 tray-t tray-t3">
                        <div>
                            <label>Upload document:</label>
                            <input class="form-control" type="file" id="csvFile" name="csvFile" accept=".csv">
                            <p class="text-danger select_doc mt-1 d-none">Please choose document</p>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center mt-4">
                        <input type="hidden" id="traytype" value="1">
                        <input type="hidden" id="statustype" value="{{$statustype}}">
                        
                        <button type="button" class="btn btn-outline-info waves-effect waves-light" id="set_tray_update">Get</i></button>

                        <a href="{{asset('/assets')}}/upload/barcode.csv" download="barcode.csv" class="btn btn-outline-success waves-effect waves-light tray-t tray-t3"><i class="mdi mdi-download ms-1"></i></a>

                        <span class="btn btn-outline-warning waves-effect waves-light">Count - <span class="countspan">0</span></span>
                        @if($statustype==2)
                        <a href="{{url('/delivery/list')}}" class="btn btn-outline-primary waves-effect waves-light">Run Sheet</i></a>
                        @endif
                    </div>
                    <div class="col-lg-2 text-center mt-4">
                        <a href="{{url('/update_sipment')}}/{{$session}}/{{$statustype}}" class="btn btn-success waves-effect waves-light tray-t confirm_tray">Confirm {{$title}}</a>
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
            <table class="table table-bordered listHtml">
                
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
    if(type==1){
        fetchProductByBarcode($("#barcodeInput").val(),$("#driver_id").val()); 
    }
    else if(type==3){
        uploadShipments(); 
    }
});

$("#barcodeInput").keypress(function(e) {
if (e.key === 'Enter') {
    e.preventDefault();
    const barcode = e.target.value.trim();
    driver_id = $("#driver_id").val();
    fetchProductByBarcode(barcode,driver_id);
    e.target.value = '';
}
}); 

function fetchProductByBarcode(barcode,driver_id) {
if($("#barcodeInput").val()==''){ $('.select_bar').removeClass('d-none'); $('.driver_bar').removeClass('d-none');}
else{ $('.select_bar').addClass('d-none'); 
$.ajax({ url: "{{url('/fetchshipment')}}/"+barcode+"/"+driver_id+"?session={{$session}}"+"&statustype={{$statustype}}",
type: "get", cache: false, dataType: 'json', 
success: function (data) { 
    $('.listHtml').html(data.inhtml);
    $('.countspan').text(data.countspan);

    if(data.is_avail==false){ $('.select_bar').removeClass('d-none');}
    else{ $('.select_bar').addClass('d-none'); }

    if(data.driver_match==true){ $('.driver_bar').removeClass('d-none');}
    else{ $('.driver_bar').addClass('d-none'); }

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

function uploadShipments() {
    var file = $('#csvFile')[0].files[0];
    var driver_id = $('#driver_id').val();
    var process_id = $('#statustype').val();
    if (!file) {
        alert("Please select a CSV file first.");
        return;
    }

    let formData = new FormData();
    formData.append('csvFile', file);
    formData.append('driver_id', driver_id);
    formData.append('process_id', process_id);
    formData.append('session', {{$session}});
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "{{ route('update_shipment_process') }}",
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

function removeShipmentStatus(idval) {
    $.ajax({ url: "{{url('/removeShipmentStatus')}}/"+idval+"?session={{$session}}"+"&statustype={{$statustype}}",
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $('.listHtml').html(data.inhtml);
    }
    })
}

</script>
@stop
