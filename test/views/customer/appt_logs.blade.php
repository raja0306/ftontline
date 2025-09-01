<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Last 5 Shipment details</h4>
<div class="table-responsive">
    <table class="table align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th>Barcode</th>
                <th>Status</th>
                <th>Update Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($last5shipments as $log)
                <tr>
                    <td>{{$log->barcode}}</td>
                    <td>{{$log->cstatus}}</td>
                    <td>{{ date('d M, Y H:i A',strtotime($log->updated_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
<h4 class="card-title mb-4">Appointment details</h4>
<div class="table-responsive">
    <table class="table align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Lead ID</th>
                <th>Appointment Date</th>
                <th>Slot</th>
                <th>Area</th>
                <th>Address Type</th>
                <th>Branch</th>
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
                <td>{{ $appointment->phone_number }}</td>
                <td>{{ $appointment->lead_id }}</td>
                <td>{{ date('d M, Y',strtotime($appointment->appointment_date)) }}</td>
                <td>{{ $appointment->slot->name ?? '-' }}</td>
                <td>{{ $appointment->area->name ?? '' }}</td>
                <td>@if($appointment->address_type==2) Branch @else Customer  @endif</td>
                <td>{{ $appointment->branch->name ?? '-'}}</td>
                
                <td>{{ $appointment->appointmentstatus->name ?? '-'}}</td>

                <td>{{ $appointment->notes }}</td>
                <td>{{ date('d M, Y H:i A',strtotime($appointment->created_at)) }}</td>
                <td>{{ $appointment->agent }}</td>
                <td><a href="{{url('book/customer')}}/{{$appointment->phone_number}}/{{$url}}/{{$appointment->id}}" class="btn btn-sm btn-outline-danger">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>