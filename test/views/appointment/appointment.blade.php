<form method="post" action="{{url('bookappointment')}}" id="bookappointment">
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
                {{$shipment->commodity_name}} - {{$shipment->cstatus}}</label>
            </div>
            @endforeach
        </div>
        <div class="row">
        <div class="col-md-12 mb-3">
            <div>
                <label>Date: <span class="text-info ms-3 app_counts"></span></label>
                <input class="form-control" type="date" name="appointment_date" id="appointment_date" required @if($appid==0) min="{{ date('Y-m-d') }}" @endif>
                <span class="text-danger font-size-11 appdate_alert"></span>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="slothtml"></div>
        </div>
        <div class="col-md-12 mb-3">
            <div>
                <label>Comments:</label>
                <textarea class="form-control" type="text" name="notes" id="notes"></textarea>
            </div>
        </div>
        <input type="hidden" name="leadid" value="{{$leadid}}">
        <input type="hidden" name="user_id" value="{{$customer->id ?? 0}}">
        <input type="hidden" name="upload_id" value="{{$upload_id}}">
        <input type="hidden" name="edit_id" value="{{$appid}}">
        <input type="hidden" name="agent" value="{{$user}}">
        <input type="hidden" name="is_newaddress" id="is_newaddress" value="0">
        <input type="hidden" name="address_type" id="address_type" value="1">
        @if(count($shipments)>0)
        <div class="col-md-12 mt-4 text-center">
            <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitappbtn">Save Appointment</button>
        </div>
        @endif
        </div>
    </div>
    <div class="col-md-9 mb-3">
    <div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified" role="tablist">
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link addrType active" data-id="1" data-bs-toggle="tab" href="#new_address" role="tab">
                <span class="">New Address</span> 
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link addrType" data-id="2" data-bs-toggle="tab" href="#new_branch" role="tab">
                <span class="">Branch</span> 
            </a>
        </li>
        <li class="nav-item waves-effect waves-light">
            <a class="nav-link addrType" data-id="3" data-bs-toggle="tab" href="#address_list" role="tab">
                <span class="">Address list</span>   
            </a>
        </li>
        </ul>
    </div>
    <div class="col-md-12 border shadow-none tab-content mt-2 py-3">
        <div class="tab-pane active" id="new_address" role="tabpanel">
                @include('customer.new_address')
        </div>
        <div class="branch_id tab-pane" id="new_branch" role="tabpanel">
            <div class="row">
            <div class="col-md-12 mb-3">
                <label>Select Branch:</label><br>
                <select class="form-control select2" name="branch_id" id="branch_id" style="width:100%">
                    <option value="">Choose...</option>
                    @foreach($branches as $branch)
                        <option value="{{$branch->id}}" data-val="{{$branch->area_id}}">{{$branch->name}} - {{$branch->morning_branch_time}}, {{$branch->evening_branch_time}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Preference:</label>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="preference"
                        id="preference-ladies" value="Ladies">
                    <label class="form-check-label" for="preference-ladies">
                        Ladies
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="preference"
                        id="preference-men" value="Men" >
                    <label class="form-check-label" for="preference-men">
                        Men
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <label>Request from:</label>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="reqfrom"
                        id="reqfrom-ladies" value="Client">
                    <label class="form-check-label" for="reqfrom-ladies">
                        Client
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="reqfrom"
                        id="reqfrom-men" value="Branch" >
                    <label class="form-check-label" for="reqfrom-men">
                        Branch
                    </label>
                </div>
            </div>
            </div>
        </div>
        <div class="tab-pane" id="address_list" role="tabpanel">
            <div>
                <label>Select Address:</label>  
                @if($customer_addresses)     
                @foreach($customer_addresses as $addr)
                @if($customer)
                <div class="form-check mb-3">
                    <input class="form-check-input useraddress_id" type="radio" name="useraddress_id" data-id="{{$addr->area_id}}" id="addrslot_id-{{$addr->id}}" value="{{$addr->id}}">
                    <label class="form-check-label" for="addrslot_id-{{$addr->id}}">
                        @if(!empty($addr->area_id)) Area:{{App\Area::Find($addr->area_id)->name}}@endif Block:{{$addr->block}} Street:{{$addr->street}} Avenue:{{$addr->avenue}} House:{{$addr->house_no}} Floor:{{$addr->floor_no}} Flat:{{$addr->flat_no}}
                    </label>
                </div>
                @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <label>Delivery remarks: <span class="text-info ms-1" id="charCount">0 / 100</span></label>
        <textarea class="form-control" type="text" name="branch_notes" id="branch_notes" oninput="checkDeliveryNoteLength(this)" maxlength="100"></textarea>
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