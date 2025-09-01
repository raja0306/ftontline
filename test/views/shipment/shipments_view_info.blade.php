<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <h3>Lead & Shipment Info
                @if($shipment->appointment_id==0)
                    <a target="_blank" href="{{url('/booknew/customer')}}/{{$shipment->consignee_phone_upload}}/{{$shipment->agent ?? 'admin'}}/1000/OUTIN/OUTIN/{{$shipment->lead_id ?? 0}}" class="btn btn-sm btn-outline-danger edit-list">Book</a>
                @endif
                    <a target="_blank" href="{{url('/edit/shipment')}}/{{$shipment->id}}" class="btn btn-sm btn-outline-warning edit-list">Edit Shipment</a>
            </h3>
            <div class="row border p-3 mb-3">
                <div class="col-md-3 mb-3"><label>Barcode</label><br><span>{{ $shipment->barcode }}</span></div>
                <div class="col-md-3 mb-3"><label>Status</label><br><span>{{ $shipment->cstatus }}</span></div>
                <div class="col-md-3 mb-3"><label>Consignee Name</label><br><span>{{ $shipment->consignee_name }}</span></div>
                <div class="col-md-3 mb-3"><label>Customer Civil ID</label><br><span>{{ $shipment->customer_civil_Id }}</span></div>
                <div class="col-md-3 mb-3"><label>Consignee Phone</label><br><span>{{ $shipment->consignee_phone }}</span></div>
                <div class="col-md-3 mb-3"><label>Alternate Phone</label><br><span>{{ $shipment->alternate_phone }}</span></div>
                <div class="col-md-3 mb-3"><label>Card Type</label><br><span>{{ $shipment->description }}</span></div>

                <div class="col-md-3 mb-3"><label>Commodity Name</label><br><span>{{ $shipment->commodity_name }}</span></div>

                <div class="col-md-3 mb-3"><label>Guardian Name</label><br><span>{{ $shipment->guardian_name }}</span></div>
                <div class="col-md-3 mb-3"><label>Receiver Civil ID</label><br><span>{{ $shipment->receiver_civil_id }}</span></div>
                 <div class="col-md-3 mb-3"><label>Branch Name</label><br><span>{{ $shipment->branch_name }}</span></div>

                <div class="col-md-3 mb-3"><label>Pickup Date</label><br><span>{{ $shipment->pickup_date }}</span></div>
                <div class="col-md-3 mb-3"><label>Reference</label><br><span>{{ $shipment->reference }}</span></div>
                <div class="col-md-3 mb-3"><label>Tray No</label><br><span>{{ $shipment->tray->name ?? 'N/A' }}</span></div>
                <div class="col-md-3 mb-3"><label>Manifest Number</label><br><span>{{ $shipment->manifest_number }}</span></div> 

                <div class="col-md-3 mb-3"><label>Lead ID</label><br><span>{{ $shipment->lead_id }}</span></div>
                
                
            </div>

            @if($shipment->appointment)
            @php $appointment=$shipment->appointment; @endphp
            <h3>Appointment Info
                <a target="_blank" href="{{url('/booknew/customer')}}/{{$shipment->consignee_phone_upload}}/{{$shipment->agent ?? 'admin'}}/1000/OUTIN/OUTIN/{{$shipment->lead_id ?? 0}}/{{$shipment->appointment_id}}" class="btn btn-sm btn-outline-danger edit-list">Edit Appointment</a>
            </h3>
            <div class="row border p-3 mb-3">
                <div class="col-md-3 mb-3"><label>Appointment Date</label><br><span>{{ $appointment->appointment_date }}</span></div>
                <div class="col-md-3 mb-3"><label>Appointment Time</label><br><span>{{ $appointment->slot->name ?? ' ' }}</span></div>
                <div class="col-md-3 mb-3"><label>Note</label><br><span>{{ $appointment->notes }}</span></div>
                <div class="col-md-3 mb-3"><label>Current Appointment Status</label><br><span>-</span></div>
                <div class="col-md-3 mb-3"><label>Agent</label><br><span>{{ $appointment->agent}}</span></div>

                @if($appointment->address_type==2)
                    <div class="col-md-3 mb-3"><label>Preference</label><br><span>{{ $appointment->preference}}</span></div>

                    <div class="col-md-3 mb-3"><label>Request From</label><br><span>{{ $appointment->req_from}}</span></div>
                @endif
            </div>
            @if($appointment->address_type==2)
            <h3>Branch Info</h3>
            <div class="row border p-3 mb-3">
                <div class="col-md-3 mb-3"><label>Branch</label><br><span>{{ $appointment->branch->name ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Governate</label><br><span>{{ $appointment->branch->governate ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Working Days</label><br><span>{{ $appointment->branch->working_days ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Morning Branch Time</label><br><span>{{ $appointment->branch->morning_branch_time ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Evening Branch Time</label><br><span>{{ $appointment->branch->evening_branch_time ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Friday</label><br><span>{{ $appointment->branch->friday ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Note</label><br><span>{{ $appointment->branch->note ?? '-'}}</span></div>
            </div>
            @else
            <h3>Address Info</h3>
            <div class="row border p-3 mb-3">
                <div class="col-md-3 mb-3"><label>Area</label><br><span>{{ $appointment->useraddress->area->name ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Block</label><br><span>{{ $appointment->useraddress->block ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Street</label><br><span>{{ $appointment->useraddress->street ?? '-'}}</span></div>

                

                <div class="col-md-3 mb-3"><label>Avenue</label><br><span>{{ $appointment->useraddress->avenue ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>House No</label><br><span>{{ $appointment->useraddress->house_no ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Floor No</label><br><span>{{ $appointment->useraddress->floor_no ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Flat No</label><br><span>{{ $appointment->useraddress->flat_no ?? '-'}}</span></div>

                <div class="col-md-3 mb-3"><label>Landmark</label><br><span>{{ $appointment->useraddress->landmark ?? '-'}}</span></div> 

            </div>
            
            @endif

            @php
               $shipmentinfo = app('App\Http\Controllers\ShipmentController')->shipmentinfo($shipment->id);
            @endphp
            <h3>Shipment Logs</h3>
            <div class="row border p-3 mb-3">
                {!!$shipmentinfo!!}
            </div>
            @endif
        </div>
    </div>
</div>