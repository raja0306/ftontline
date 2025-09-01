<form method="post" action="{{url('bookappointment')}}" id="bookappointment2">
@csrf
<div class="row mt-4">
    @if($appid>0)<h5 class="card-title text-primary mb-3">Modify appointment #{{$appid}} </h5> @endif
    @if(session('alert'))
    <div class="col-md-12 mb-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-all me-2"></i>
        {!! session('alert') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    </div>
    @endif
    <div class="col-md-3 mb-3">
        <div class="col-md-12">
            <label>Shipments:</label>
            @foreach($shipments as $shipment)
            <div class="form-check mb-3">
                <input class="form-check-input" name="shipment_ids[]" value="{{$shipment->id}}" type="checkbox" id="ship_{{$shipment->id}}" checked>
                <label class="form-check-label text-dark" for="ship_{{$shipment->id}}">{{$shipment->barcode}}-{{$shipment->description}}-
                {{$shipment->commodity_name}}</label>
            </div>
            @endforeach
        </div>
        <div class="row">
        <div class="col-md-12 mb-3">
            <div>
                <label>Date: <span class="text-info ms-3 app_counts"></span></label>
                <input class="form-control" type="date" name="appointment_date" id="appointment_date" value="{{$appoint->appointment_date}}" required>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div>
                <label>Select Slot:</label>
                    @foreach($slots as $slot)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="slot_id"
                            id="slot_id-{{$slot->id}}" value="{{$slot->id}}" @if($appoint->slot_id==$slot->id)checked @endif required>
                        <label class="form-check-label" for="slot_id-{{$slot->id}}">
                            {{$slot->name}}
                        </label>
                    </div>
                    @endforeach
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div>
                <label>Comments:</label>
                <textarea class="form-control" type="text" name="notes" id="notes">{{$appoint->notes}}</textarea>
            </div>
        </div>
        <input type="hidden" name="leadid" value="{{$leadid}}">
        <input type="hidden" name="user_id" value="{{$customer->id ?? 0}}">
        <input type="hidden" name="upload_id" value="{{$upload_id}}">
        <input type="hidden" name="edit_id" value="{{$appid}}">
        <input type="hidden" name="agent" value="{{$user}}">
        <input type="hidden" name="is_newaddress" id="is_newaddress" value="0">
        <input type="hidden" name="address_type" id="address_type" @if($appoint->address_type!=2)value="3" @else value="2" @endif>
        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitappbtn" data-type="2">Update Appointment</button>
        </div>
        </div>
    </div>
    <div class="col-md-9 mb-3">
    <div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified" role="tablist">
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link addrType addrType-1" data-id="1" data-bs-toggle="tab" href="#new_address" role="tab">
                <span class="">New Address</span> 
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link addrType @if($appoint->address_type==2) active @endif" data-id="2" data-bs-toggle="tab" href="#new_branch" role="tab">
                <span class="">Branch</span> 
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link addrType addrType-3 @if($appoint->address_type==1||$appoint->address_type==3) active @endif" data-id="3" data-bs-toggle="tab" href="#address_list" role="tab">
                <span class="">Address list</span>   
            </a>
        </li>
        </ul>
    </div>
    <div class="col-md-12 border shadow-none tab-content mt-2 py-3">
        <div class="tab-pane addrType-1" id="new_address" role="tabpanel">
                @include('customer.new_address')
        </div>
        <div class="branch_id tab-pane @if($appoint->address_type==2) active @endif" id="new_branch" role="tabpanel">
            <div>
                <label>Select Branch:</label><br>
                <select class="form-control select2" name="branch_id" id="branch_id" style="width:100%">
                    <option value="">Choose...</option>
                    @foreach($branches as $branch)
                        <option value="{{$branch->id}}" @if($appoint->branch_id==$branch->id)selected @endif>{{$branch->name}} - {{$branch->morning_branch_time}}, {{$branch->evening_branch_time}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="branch_id tab-pane addrType-3 @if($appoint->address_type==1||$appoint->address_type==3) active @endif" id="address_list" role="tabpanel">
            <div>
                <label>Select Address:</label>  
                @if($customer_addresses)     
                @foreach($customer_addresses as $addr)
                @if($customer)
                <div class="form-check mb-3">
                    <input class="form-check-input useraddress_id" type="radio" name="useraddress_id" id="addrslot_id-{{$addr->id}}" value="{{$addr->id}}" @if($appoint->useraddress_id==$addr->id)checked required @endif >
                    <label class="form-check-label" for="addrslot_id-{{$addr->id}}">
                        @if(!empty($addr->area_id)) Area:{{App\Area::Find($addr->area_id)->name}}@endif Block:{{$addr->block}} Street:{{$addr->street}} Avenue:{{$addr->avenue}} House:{{$addr->house_no}} Floor:{{$addr->floor_no}} Flat:{{$addr->flat_no}}
                    </label>
                    <a href="#" class="badge rounded-pill bg-dark float-end font-size-16 ms-3 edit_addr" data-area_id="{{$addr->area_id}}" data-block="{{$addr->block}}" data-street="{{$addr->street}}" data-avenue="{{$addr->avenue}}" data-house_no="{{$addr->house_no}}" data-floor_no="{{$addr->floor_no}}" data-pacii_no="{{$addr->pacii_no}}" data-landmark="{{$addr->landmark}}" data-flat_no="{{$addr->flat_no}}" data-lat="{{$addr->lat}}" data-longi="{{$addr->longi}}" data-flat_no="{{$addr->flat_no}}">Edit</a>
                </div>
                @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="col-md-3 mb-3">
        <div id="branch_id_h"></div>
    </div>
    <div class="col-md-3 mb-3 d-none">
        <div>
            <label>Phone Number:</label>
            <input class="form-control" type="text" value="{{$mobile}}" name="phone_number" id="phone_number" readonly>
        </div>
    </div>
    <div class="col-md-12 mb-3">
    </div>
</div>
</form>