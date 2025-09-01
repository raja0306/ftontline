<div class="row">
<div class="col-lg-12">
<h4 class="card-title mb-4">Last 5 Shipment details</h4>
<div class="table-responsive">
    <table class="table align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th>Barcode</th>
                <th>Status</th>
                <th>Update Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($last5shipments as $log)
                <tr>
                    <td>{{$log->barcode}}</td>
                    <td>{{$log->cstatus}}</td>
                    <td>{{ date('d M, Y H:i A',strtotime($log->updated_at)) }}</td>
                    <td>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#ship_history_{{$log->id}}" aria-expanded="false" aria-controls="ship_history_{{$log->id}}">History</a>
                    </div>
                    </td>
                </tr>
                <tr class="collapse" id="ship_history_{{$log->id}}">
                    <td colspan="4">
                    <div>
                        <div class="card border shadow-none card-body text-muted mb-0">
                        {!!app('App\Http\Controllers\ShipmentController')->shipmentinfo($log->id)!!}
                        </div>
                    </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h4 class="card-title mt-5 mb-4">Appointment details</h4>
<div class="table-responsive">
    <table class="table align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Shipment Qty</th>
                <th>Phone Number</th>
                <th>Lead ID</th>
                <th>Appointment Date</th>
                <th>Slot</th>
                <th>Area</th>
                <th>Address Type</th>
                <th>Branch</th>
                <th>Driver</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Created At</th>
                <th>Agent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $appointment)
            @php $lead = App\VicidialList::where('lead_id',$appointment->lead_id)->first(); @endphp
            <tr>
                <td>{{ $appointment->customer->name ?? ''}}</td>
                <td>{{ count($appointment->shipments) ?? '0'}}</td>
                <td>{{ $appointment->phone_number }}</td>
                <td>{{ $appointment->lead_id }}</td>
                <td>{{ date('d M, Y',strtotime($appointment->appointment_date)) }}</td>
                <td>{{ $appointment->slot->name ?? '-' }}</td>
                <td>{{ $appointment->area->name ?? '' }}</td>
                <td>@if($appointment->address_type==2) Branch @else Customer  @endif</td>
                <td>{{ $appointment->branch->name ?? '-'}}</td>
                <td>{{ $appointment->driver->name ?? '-'}}</td>
                
                <td>{{ $appointment->appointmentstatus->name ?? '-'}}</td>

                <td>{{ $appointment->notes }}</td>
                <td>{{ date('d M, Y H:i A',strtotime($appointment->created_at)) }}</td>
                <td>{{ $appointment->agent }}</td>
                <td>
                    <a href="{{url('book/customer')}}/{{$appointment->phone_number}}/{{$url}}/{{$appointment->id}}" class="btn btn-sm btn-outline-danger">Edit</a>
                    <a href="{{url('/book/apppointment/view/')}}/{{$appointment->id}}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>