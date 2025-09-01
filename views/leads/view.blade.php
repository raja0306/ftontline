<div class="row">
<div class="col-12">
    <div class="card" style="overflow-x: auto;">
        <div class="card-body">
            <h3>Lead & Shipment Info</h3>
            @foreach($shipments as $shipment)
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
                <div class="col-md-3 mb-3"><label>Tray No</label><br><span>{{ $shipment->tray_no }}</span></div>
                <div class="col-md-3 mb-3"><label>Manifest Number</label><br><span>{{ $shipment->manifest_number }}</span></div> 

                <div class="col-md-3 mb-3"><label>Lead ID</label><br><span>{{ $shipment->lead_id }}</span></div>
                
                
            </div>
            @endforeach
            @if($appointment)
            <h3>Appointment Info</h3>
            <div class="row border p-3 mb-3">
                <div class="col-md-3 mb-3"><label>Appointment Date</label><br><span>{{ $appointment->appointment_date }}</span></div>
                <div class="col-md-3 mb-3"><label>Appointment Time</label><br><span>{{ $appointment->slot->name ?? ' ' }}</span></div>
                <div class="col-md-3 mb-3"><label>Note</label><br><span>{{ $appointment->notes }}</span></div>
                <div class="col-md-3 mb-3"><label>Current Appointment Status</label><br><span>-</span></div>
                <div class="col-md-3 mb-3"><label>Agent</label><br><span>{{ $appointment->agent}}</span></div>
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

            <h3>Appointment Logs</h3>
            <div class="row border p-3 mb-3">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>User</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($appointment->appointmentlogs as $list)
                        <tr>
                            <td>{{date('d M, Y',strtotime($list->created_at))}}</td>

                            <td>{{date('H:i A',strtotime($list->created_at))}}</td>

                            <td>{{$list->user->name ?? '-'}}</td>

                            <td>{{$list->appointmentstatus->name ?? '-'}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>