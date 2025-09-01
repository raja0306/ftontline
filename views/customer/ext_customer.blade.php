<div class="row mt-2">
<div class="col-sm-3">
    <div class="card">
    <div class="card-body">
    <h4 class="card-title">
        <i class="bx bx-user me-1"></i>{{$customer->name ?? ''}}
        <span class="float-end"><i class="bx bx-mobile-alt"></i>{{$mobile}} </span>
        @if(!empty($lead)&&($lead->comments))<br><small class="text-info"> {{$lead->comments}} </small>@endif
    </h4>
    <div class="dropdown-divider"></div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            @php $ship=1; @endphp
            @foreach($shipments as $shipment)
            <a class="nav-link mb-2 font-size-12 @if($ship==1)active @endif" id="v-pills-{{$shipment->id}}-tab" data-bs-toggle="pill" href="#v-pills-{{$shipment->id}}" role="tab" aria-controls="v-pills-{{$shipment->id}}" aria-selected="true">{{$shipment->barcode}}  {{$shipment->description}}
            {{$shipment->commodity_name}}</a>
            @php $ship++; @endphp
            @endforeach
            </div>
        </div>
    </div>
    <div class="row bg-secondary bg-soft py-2">
        <div class="col-md-12">
            <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                @php $ship=1; @endphp
                @foreach($shipments as $shipment)
                <div class="tab-pane fade @if($ship==1)show active @endif" id="v-pills-{{$shipment->id}}" role="tabpanel" aria-labelledby="v-pills-{{$shipment->id}}-tab">
                    @include('customer.shipment')
                </div>
                @php $ship++; @endphp
                @endforeach
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
<div class="col-sm-9">
<div class="card">
    <div class="card-body">        
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#appt_info" role="tab">
                    Appointment Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#add_enquiry" role="tab">
                    Add Enquiry
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#appt_calls" onclick="getCallLogs();" role="tab">
                    Call Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#appt_logs" onclick="getAppLogs();" role="tab">
                    Appointment Logs
                </a>
            </li>
        </ul>
        <div class="tab-content p-3 text-muted">
            <div class="tab-pane active" id="appt_info" role="tabpanel">
                @if($appid>0)@include('customer.appointment_edit') @else @include('customer.appointment_info') @endif
            </div>
            <div class="tab-pane" id="add_enquiry" role="tabpanel">
                @include('customer.enquiry')
            </div>
            <div class="tab-pane" id="appt_calls" role="tabpanel">
                <div class="Logs_call"></div>
            </div>
            <div class="tab-pane" id="appt_logs" role="tabpanel">
                <div class="Logs_appt"></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
